This document describes the usage of the Windows sample code for Button Encryption.
Included in this package are three items:

1) The ButtonEncryptionLib project. This project will generate a DLL that encapsulates the encrypted code creation. Building this requires the "OpenSSL for Windows" package, which is available from http://www.openssl.org/related/binaries.html

2) The ButtonEncryptionLibTest project. This is a lightweight C# application that builds around the ButtonEncryptionLib project to make a command line application that will generate an html file which you can use to test your settings.

3) key.bat, a batch file that will generate a public and private key pair. Just run "key <basename>" and it will genereate basename-prvkey.pem and basename-pubcert.pem. This also requires the "OpenSSL for Windows" package.