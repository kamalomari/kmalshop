#/bin/csh
# Please source this file.

setenv PATH /x/contrib/j2sdk1.4.0/jre/bin:$PATH

setenv CRYPTO_HOME "../crypto-124"

if (-d $CRYPTO_HOME) then
	echo setting crypto home to $CRYPTO_HOME
else	
        echo cannot find crypto distribution ...
        exit 1
endif

setenv CLASSPATH "."
setenv CLASSPATH "${CLASSPATH}:${AXIS_HOME}/lib/axis.jar"
setenv CLASSPATH "${CLASSPATH}:${AXIS_HOME}/lib/jaxrpc.jar"
setenv CLASSPATH "${CLASSPATH}:${AXIS_HOME}/lib/saaj.jar"
setenv CLASSPATH "${CLASSPATH}:${AXIS_HOME}/lib/wsdl4j.jar"
setenv CLASSPATH "${CLASSPATH}:${AXIS_HOME}/lib/commons-logging.jar"
setenv CLASSPATH "${CLASSPATH}:${AXIS_HOME}/lib/commons-discovery.jar"
setenv CLASSPATH "${CLASSPATH}:${CRYPTO_HOME}/jars/bcprov-jdk14-124.jar"
setenv CLASSPATH "${CLASSPATH}:${CRYPTO_HOME}/jars/bcpg-jdk14-124.jar"
setenv CLASSPATH "${CLASSPATH}:${CRYPTO_HOME}/jars/bctest-jdk14-124.jar"
setenv CLASSPATH "${CLASSPATH}:${CRYPTO_HOME}/jars/bcmail-jdk14-124.jar"
setenv CLASSPATH "${CLASSPATH}:./ppapi.jar"
echo $CLASSPATH
