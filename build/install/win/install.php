<?php
$uid = posix_geteuid();
$data = posix_getpwuid($uid);
$userdir = $data['dir'];

$Wshshell= new COM('WScript.Shell');
$desktopFolder= $Wshshell->regRead('HKEY_CURRENT_USER\\Software\\Microsoft\\Windows\\CurrentVersion\\Explorer\\User Shell Folders\\Desktop');

//mkdir %USERPROFILE%/pblog;
mkdir("$userdir\pblog");
//copy ../../pblog.phar %USERPROFILE%/pblog/pblog.phar;
copy(__DIR__."\..\..\pblog.phar", "$userdir\pblog\pblog.phar");
//copy ../config.php %USERPROFILE%/pblog/config.php;
copy(__DIR__."\..\..\config.php", "$userdir\pblog\config.php");
//copy run.bat.cpy %DESKTOP%/run-private-blog.bat;
copy(__DIR__."\run.bat.cpy", "$desktopFolder\run-private-blog.bat");

echo "Run private blog from '$desktopFolder\run-private-blog.bat' !";