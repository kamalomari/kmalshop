echo off

rem Set below to your BC
set CRYPTO_HOME=C:\crypto-124
echo Using CRYPTO 1.2.4


set CLASSPATH=.
set CLASSPATH=%CLASSPATH%;%CRYPTO_HOME%\jars\bcprov-jdk14-124.jar
set CLASSPATH=%CLASSPATH%;%CRYPTO_HOME%\ljars\bcpg-jdk14-124.jar
set CLASSPATH=%CLASSPATH%;%CRYPTO_HOME%\jars\bctest-jdk14-124.jar
set CLASSPATH=%CLASSPATH%;%CRYPTO_HOME%\jars\bcmail-jdk14-124.jar
