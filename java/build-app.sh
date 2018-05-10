#!/bin/bash

export CRYPTO_HOME="/x/home/dhan/java_client/crypto-124"

if [ ! -d ${CRYPTO_HOME} ]; then
	echo cannot find crypto distribution ...
	return 1;
fi

CLASSPATH="."
CLASSPATH="$CLASSPATH:$CRYPTO_HOME/jars/bcprov-jdk14-124.jar"
CLASSPATH="$CLASSPATH:$CRYPTO_HOME/jars/bcpg-jdk14-124.jar"
CLASSPATH="$CLASSPATH:$CRYPTO_HOME/jars/bctest-jdk14-124.jar"
CLASSPATH="$CLASSPATH:$CRYPTO_HOME/jars/bcmail-jdk14-124.jar"
export CLASSPATH

JAVA=/x/contrib/j2sdk1.4.0/bin/java
JAVAC=/x/contrib/j2sdk1.4.0/bin/javac
JAR=/x/contrib/j2sdk1.4.0/bin/jar

echo
echo "Be sure Java version is at least 1.4"
$JAVA -version
echo ; echo ; #sleep 3


echo "CLASSPATH="
echo $CLASSPATH
echo

echo compiling java files ...
$JAVAC -g -classpath "$CLASSPATH" 	                \
	ButtonEncryption.java	\
	com/paypal/crypto/sample/*.java

echo "Done!!!!!"

