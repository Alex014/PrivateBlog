#!/bin/sh
GREEN='\033[1;32m'
WHITE='\033[1;37m'
RED='\033[1;31m'
NC='\033[0m' # No Color

rm build/pblog.phar
rm build/install/other/pblog.phar

echo -e "${WHITE}Compiling ...${NC}";
if php compile.php; then
    echo -e "${WHITE}DONE !${NC}";
    cd build/install/linux;
    bash install.sh
else
    echo -e "${RED}ERROR !${NC}";
fi