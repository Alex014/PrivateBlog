<?php
$files = glob('__require/windows-registry/*.php');
foreach ($files as $file) {
    require $file;
}

$hklm = \Windows\Registry\Registry::connect()->getCurrentUser();
$keyPath = 'Software\\Microsoft\\Windows\\CurrentVersion\\Explorer\\User Shell Folders';
$mySubKey = $hklm->getSubKey($keyPath);

$userdir = $_SERVER['HOMEPATH'];
$desktopFolder = $mySubKey->getValue('Desktop');

$falsestr = 'system32\\config\\systemprofile';
$strpos = strpos($desktopFolder, $falsestr);
if($strpos !== false) {
	$lenfalse = strlen($falsestr);
	$desktopFolder = /*substr($desktopFolder, 0, 2) .*/ $userdir . substr($desktopFolder, $strpos + $lenfalse, strlen($desktopFolder) - $strpos - $lenfalse);
}


//mkdir %USERPROFILE%/pblog;
$dirname = "$userdir\\pblog";
if(!file_exists($dirname)) mkdir($dirname);

$dstfilename = "$userdir\\pblog\pblog.phar";
if(file_exists($dstfilename)) unlink($dstfilename);
copy(__DIR__."\\..\\..\\pblog.phar", $dstfilename);

$dstfilename = "$userdir\\pblog\config.php";
if(file_exists($dstfilename)) unlink($dstfilename);
copy(__DIR__."\\..\\config.php", $dstfilename);

$dstfilename = "$desktopFolder\\run-private-blog.bat";
if(file_exists($dstfilename)) unlink($dstfilename);
copy(__DIR__."\\run.bat.cpy", $dstfilename);

echo "Run private blog from '$desktopFolder\\run-private-blog.bat' !";