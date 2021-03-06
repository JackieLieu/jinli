#!/bin/bash

#
# apk 签名工具
# 依赖 java 1.5 +
# 位置 /Data/Bin/Signature/sign.sh
#

JarPATH=`dirname $0`
SUCCESS=0
E_NOFILE=64
E_NOARGS=65
E_WRONGFILE=66
filename=""

signrom()
{
    echo "Sign $1$EXT_NAME"
    java -jar "$JarPATH"/signapk.jar "$JarPATH"/platform.x509.pem "$JarPATH"/platform.pk8 "$1$EXT_NAME" "$1"_signed"$EXT_NAME"
    echo "Done "$1"_signed"$EXT_NAME
}

if [ -z "$1" ];then
    echo "Usage: `basename $0` unsigned.apk signed.apk"
    exit $E_NOARGS
elif [ -e "$1" ];then
    filename=$1
else
    echo "File $1 Not Exists!"
    exit $E_NOFILE
fi

EXT_NAME=${filename:(-4)}
ext_name=`echo $EXT_NAME|tr 'A-Z' 'a-z'`
ROM_SIZE=${#filename}
filename=${filename:0:(($ROM_SIZE-4))}

if [[ "$ROM_SIZE" -lt "4" ]] || [[ "$ext_name" != ".apk" ]]
then
    echo "Wrong File Type!(Only Support APK Files.)"
    exit $E_WRONGFILE
else
	zip --delete $1 "/META-INF/*" >> /dev/null
    signrom $filename $EXT_NAME
    exit 0
fi
