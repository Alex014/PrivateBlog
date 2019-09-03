#!/bin/sh
GREEN='\033[1;32m'
WHITE='\033[1;37m'
RED='\033[1;31m'
NC='\033[0m' # No Color

file=${HOME}/pblog


file=${HOME}/.emercoin

if ! test -d "${file}"; then
    echo -e "${RED}ERROR !${NC}";
    echo -e "Emercoin directory ${GREEN}${file}${NC} not found exit.";
    echo -e "Is emercoin wallet insatalled ?";
    exit 1;
fi;

file=${HOME}/pblog

if ! test -d "${file}"; then
    mkdir ${file};
else
    echo -e "Directory ${GREEN}${file}${NC}' olready exists";
fi;

srcfile=../../pblog.phar

if ! test -f "${srcfile}"; then
    echo -e "${RED}ERROR !${NC}";
    echo -e "File ${GREEN}${srcfile}${NC} not found exit.";
    echo -e "Run ${GREEN}php compile.php${NC} first";
    exit 1;
fi;

cp ${srcfile} ${file};
chmod 0770 ${file};

file=${HOME}/pblog/run.sh
cp run.sh.cpy ${file};
chmod 0770 ${file};

file=${HOME}/pblog/config.php
cp ../config.php ${file};
chmod 0770 ${file};



echo -e "${WHITE}COMPLETED !${NC}";
echo -e "type ${GREEN}cd ${HOME}/pblog${NC} and then ${GREEN}./run.sh${NC} ";