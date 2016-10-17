#!/usr/bin/env php
<?php
/**
 * Joomla extensions make file
 */
echo "-----------------------------------------------------\r\n";
echo "           Joomla extensions make file\r\n";
echo "-----------------------------------------------------\r\n";


function addDirectoryToZip($zip, $dir, $base){
	$newFolder = str_replace($base, '', $dir);
	$zip->addEmptyDir($newFolder);
	foreach(glob($dir . '/*') as $file){
        	if(is_dir($file)){
			$zip = addDirectoryToZip($zip, $file, $base);
			}
        	else{
			$newFile = str_replace($base, '', $file);
			$zip->addFile($file, $newFile);
			}
		}
	return $zip;
	}

function ZipComponent($comname,$shortname){
	echo ' -= '.$comname.' =- '.PHP_EOL;
	$zip = new ZipArchive();
	$filename = './'.$shortname.'.zip';
	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("Could not open file <$filename>\n");
		}
	$directory = realpath('./components/'.$shortname.'/');
	addDirectoryToZip($zip,$directory,$directory);
	echo "numfiles: " . $zip->numFiles . "\n";
	echo "status:" . $zip->status . "\n";
	$zip->close();
	}

ZipComponent('AddArticle','com_addarticle');
ZipComponent('Brillcallback','com_brillcallback');
