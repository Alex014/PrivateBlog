#!/bin/sh

php config.php -f="${HOME}/.emercoin/emercoin.conf"
php -S localhost:8000 pblog.phar