#!/bin/sh
GREEN='\033[1;32m'
WHITE='\033[1;37m'
RED='\033[1;31m'
NC='\033[0m' # No Color


if ! test -f "build/pblog.phar"; then
    rm build/pblog.phar
fi

if ! test -f "build/install/other/pblog.phar"; then
    rm build/install/other/pblog.phar
fi

echo -e "${WHITE}Compiling ...${NC}";
if php compile.php; then
    echo -e "${WHITE}DONE !${NC}";
    echo -e "${WHITE}Installing ...${NC}";
    cd build/install/linux;
    bash install.sh
else
    echo -e "${RED}ERROR !${NC}";
fi