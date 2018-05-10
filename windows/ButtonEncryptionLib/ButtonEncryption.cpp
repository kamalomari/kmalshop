// This is the main DLL file.

#include "stdafx.h"
#include <windows.h>

#using <mscorlib.dll> 
using namespace System;
using namespace System::Text;
using namespace System::Runtime::InteropServices;

#include "ButtonEncryption.h"


String* ButtonEncryption::SignAndEncrypt(String* sCmdTxt, String* certPath, String* keyPath, String* payPalCertPath, String*& sResult )
{
	BIO		*bio;
	X509	*x509 = NULL;
	X509	*PPx509 = NULL;
	RSA		*rsa = NULL;
	char	*enc = NULL;
	char	*sTempStr = NULL;
	String* sEnc;

	ERR_load_crypto_strings();
	OpenSSL_add_all_algorithms();

	// Load the PayPal Cert File
	sTempStr = StringToCharPtr(payPalCertPath);
	if ((bio = BIO_new_file(sTempStr, "rt")) == NULL) {
		sResult = String::Concat( sResult, String::Format("Fatal Error: Failed to open ({0})\n", payPalCertPath) );
		goto end;
	}
	if ((PPx509 = PEM_read_bio_X509(bio, NULL, NULL, NULL)) == NULL) 
	{
		sResult = String::Concat( sResult, String::Format("Fatal Error: Failed to read Paypal certificate from ({0})\n", payPalCertPath) );
		goto end;
	}
	BIO_free(bio);
	delete[] sTempStr;
	sTempStr = NULL;

	// Load the User Cert File
	sTempStr = StringToCharPtr(certPath);
	if ((bio = BIO_new_file(sTempStr, "rt")) == NULL)
	{
		sResult = String::Concat( sResult, String::Format("Fatal Error: Failed to open ({0})\n", certPath) );
		goto end;
	}
	if ((x509 = PEM_read_bio_X509(bio, NULL, NULL, NULL)) == NULL) 
	{
		sResult = String::Concat( sResult, String::Format("Fatal Error: Failed to read certificate from ({0})\n", certPath) );
		goto end;
	}
	BIO_free(bio);
	delete[] sTempStr;
	sTempStr = NULL;

	// Load the User Key File
	sTempStr = StringToCharPtr(keyPath);
	if ((bio = BIO_new_file(sTempStr, "rt")) == NULL)
	{			
		sResult = String::Concat( sResult, String::Format("Fatal Error: Failed to open ({0})\n", keyPath) );
		goto end;
	}
	if ((rsa = PEM_read_bio_RSAPrivateKey(bio, NULL, NULL, NULL)) == NULL) 
	{
		sResult = String::Concat( sResult, String::Format("Fatal Error: Unable to read RSA key ({0}).\n", keyPath) );
		goto end;
	}
	BIO_free(bio);
	bio = NULL;
	delete[] sTempStr;
	sTempStr = NULL;

	// Process payload into blob.
	sTempStr = StringToCharPtr(sCmdTxt);
	enc = sign_and_encrypt(Reformat(sTempStr), rsa, x509, PPx509);
	sEnc = new String(enc);
	if (enc) {
		sResult = String::Concat( sResult, String::Format("Button code = {0}\n", sEnc ) );
	} else {
		sResult = String::Concat( sResult, "Fatal Error: Failed to generate PKCS7.\n" );
	}
	delete[] enc;

end:
	if (sTempStr) {
		delete[] sTempStr;
		sTempStr = NULL;
	}
	if (bio) {
		BIO_free_all(bio);
	}
	if (x509) {
		X509_free(x509);
	}
	if (PPx509) {
		X509_free(PPx509);
	}
	if (rsa) {
		RSA_free(rsa);
	}
	return sEnc;
}

// Convert "Blah,Blah,Blah" to "Blah\nBlah\nBlah" in place.
char* ButtonEncryption::Reformat(char* input)
{
	char *rep = strchr(input, ',');
	while (rep) {
		*rep = '\n';
		rep = strchr(rep, ',');
	}
	return input;
}

// Convert a String to a char[]. NOTE: should manually delete[] the returned string.
char* ButtonEncryption::StringToCharPtr(String *value) {
	int length = value->Length;
	unsigned short arrayLen = length+1;
	char* in_string = new char[arrayLen]; 
	for(unsigned short i = 0; i<length; i++) 
	{ 
		in_string[i] = (char)value->Chars[i];
	} 
	in_string[length] = '\0'; 
	return in_string;
}

char* ButtonEncryption::sign_and_encrypt(const char *data, RSA *rsa, X509 *x509, X509 *PPx509)
{	
	char *ret;
	EVP_PKEY *pkey;
	PKCS7 *p7;
	BIO *memBio;
	BIO *p7bio;
	BIO *bio;

	pkey = EVP_PKEY_new();

	if (EVP_PKEY_set1_RSA(pkey, rsa) == 0)
	{
		printf("Fatal Error: Unable to create EVP_KEY from RSA key\n");
		goto end;
	} else if (verbose) {
		printf("Successfully created EVP_KEY from RSA key\n");
	}

	// Create a signed and enveloped PKCS7
	p7 = PKCS7_new();
	PKCS7_set_type(p7, NID_pkcs7_signedAndEnveloped);

	PKCS7_SIGNER_INFO* si = PKCS7_add_signature(p7, x509, pkey, EVP_sha1());

	if (si) {
		if (PKCS7_add_signed_attribute(si, NID_pkcs9_contentType, V_ASN1_OBJECT,
			OBJ_nid2obj(NID_pkcs7_data)) <= 0)
		{
			printf("Fatal Error: Unable to add signed attribute to certificate\n");
			printf("OpenSSL Error: %s\n", ERR_error_string(ERR_get_error(), NULL));
			goto end;
		} else if (verbose) {
			printf("Successfully added signed attribute to certificate\n");
		}

	} else {
		printf("Fatal Error: Failed to sign PKCS7\n");
		goto end;
	}

	//Encryption
	if (PKCS7_set_cipher(p7, EVP_des_ede3_cbc()) <= 0)
	{
		printf("Fatal Error: Failed to set encryption algorithm\n");
		printf("OpenSSL Error: %s\n", ERR_error_string(ERR_get_error(), NULL));
		goto end;
	} else if (verbose) {
		printf("Successfully added encryption algorithm\n");
	}

	if (PKCS7_add_recipient(p7, PPx509) <= 0)
	{
		printf("Fatal Error: Failed to add PKCS7 recipient\n");
		printf("OpenSSL Error: %s\n", ERR_error_string(ERR_get_error(), NULL));
		goto end;
	} else if (verbose) {
		printf("Successfully added recipient\n");
	}

	if (PKCS7_add_certificate(p7, x509) <= 0)
	{
		printf("Fatal Error: Failed to add PKCS7 certificate\n");
		printf("OpenSSL Error: %s\n", ERR_error_string(ERR_get_error(), NULL));
		goto end;
	} else if (verbose) {
		printf("Successfully added certificate\n");
	}

	memBio = BIO_new(BIO_s_mem());
	p7bio = PKCS7_dataInit(p7, memBio);

	if (!p7bio) {
		printf("OpenSSL Error: %s\n", ERR_error_string(ERR_get_error(), NULL));
		goto end;
	}

	//Pump data to special PKCS7 BIO. This encrypts and signs it.
	BIO_write(p7bio, data, strlen(data));
	BIO_flush(p7bio);
	PKCS7_dataFinal(p7, p7bio);		

	//Write PEM encoded PKCS7
	bio = BIO_new(BIO_s_mem());

	if (!bio || (PEM_write_bio_PKCS7(bio, p7) == 0))
	{
		printf("Fatal Error: Failed to create PKCS7 PEM\n");
	} else if (verbose) {
		printf("Successfully created PKCS7 PEM\n");
	}

	BIO_flush(bio);		

	char *str;
	int len = BIO_get_mem_data(bio, &str);

	ret = new char [len + 1];
	memcpy(ret, str, len);
	ret[len] = 0;

end:
	//Free everything
	if (p7)
		PKCS7_free(p7);
	if (bio)
		BIO_free_all(bio);
	if (memBio)
		BIO_free_all(memBio);
	if (p7bio)
		BIO_free_all(p7bio);
	if (pkey)
		EVP_PKEY_free(pkey);

	return ret;
}
