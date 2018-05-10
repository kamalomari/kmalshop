// ButtonEncryptionLib.h

#pragma once
//#include <stdio.h>

#include "openssl/buffer.h"
#include "openssl/bio.h"
#include "openssl/sha.h"
#include "openssl/rand.h"
#include "openssl/err.h"
#include "openssl/rsa.h"
#include "openssl/evp.h"
#include "openssl/x509.h"
#include "openssl/x509v3.h"
#include "openssl/pkcs7.h"
#include "openssl/pem.h"

//using namespace System;

public __gc class ButtonEncryption
{
private:
	bool verbose;
	char* Reformat(char* input);
	char* StringToCharPtr(String *value);
	char* sign_and_encrypt(const char *data, RSA *rsa, X509 *x509, X509 *PPx509);
public:
	ButtonEncryption() : verbose( false ) {};
	ButtonEncryption( bool bVerbose ) : verbose(bVerbose) {};
	String* SignAndEncrypt(String* sCmdTxt, String* certPath, String* keyPath, String* payPalCertPath, String*& sResult );
};
