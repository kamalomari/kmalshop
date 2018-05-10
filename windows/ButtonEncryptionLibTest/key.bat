\openssl\bin\openssl genrsa -out %1-prvkey.pem 1024
\openssl\bin\openssl req -new -key %1-prvkey.pem -x509 -days 365 -out %1-pubcert.pem
