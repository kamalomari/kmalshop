echo off

rem Set below to your BC
set CRYPTO_HOME=C:\crypto-124
echo Using CRYPTO 1.2.4


set CLASSPATH=.
set CLASSPATH=%CLASSPATH%;%CRYPTO_HOME%\jars\bcprov-jdk14-124.jar
set CLASSPATH=%CLASSPATH%;%CRYPTO_HOME%\ljars\bcpg-jdk14-124.jar
set CLASSPATH=%CLASSPATH%;%CRYPTO_HOME%\jars\bctest-jdk14-124.jar
set CLASSPATH=%CLASSPATH%;%CRYPTO_HOME%\jars\bcmail-jdk14-124.jar

set JAVA=C:\j2sdk1.4.2_04\jre\bin\java.exe
set JAVAC=C:\j2sdk1.4.2_04\bin\javac.exe
set JAR=C:\j2sdk1.4.2_04\bin\jar.exe


rem echo finding java ...
rem which javac || return
rem which java || return
rem which jar || return
rem echo ; sleep 1

echo Be sure Java version is at least 1.4
%JAVA% -version

echo CLASSPATH is
echo %CLASSPATH%

echo compiling java files ...
%JAVAC% -classpath %CLASSPATH% ButtonEncryption.java com\paypal\crypto\sample\*.java

echo DONE!!!

