\openssl\bin\openssl genrsa -out %1-prvkey.pem 1024
\openssl\bin\openssl req -new -key %1-prvkey.pem -x509 -days 365 -out %1-pubcert.pem
\openssl\bin\openssl pkcs12 -export -inkey %1-prvkey.pem -in %1-pubcert.pem -out %1-prvkey.p12
 


