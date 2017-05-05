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

function ZipModule($comname,$shortname){
	echo ' -= '.$comname.' =- '.PHP_EOL;
	$zip = new ZipArchive();
	$filename = './'.$shortname.'.zip';
	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("Could not open file <$filename>\n");
		}
	$directory = realpath('./modules/'.$shortname.'/');
	addDirectoryToZip($zip,$directory,$directory);
	echo "numfiles: " . $zip->numFiles . "\n";
	echo "status:" . $zip->status . "\n";
	$zip->close();
	}

function ZipLibrary($comname,$shortname){
	echo ' -= '.$comname.' =- '.PHP_EOL;
	$zip = new ZipArchive();
	$filename = './lib_'.$shortname.'.zip';
	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("Could not open file <$filename>\n");
		}
	$directory = realpath('./libraries/'.$shortname.'/');
	addDirectoryToZip($zip,$directory,$directory);
	echo "numfiles: " . $zip->numFiles . "\n";
	echo "status:" . $zip->status . "\n";
	$zip->close();
	}


function ZipPlugin($comname,$shortgroup,$shortname){
	echo ' -= '.$shortgroup.'/'.$shortname.' =- '.PHP_EOL;
	$zip = new ZipArchive();
	$filename = './plg_'.$shortgroup.'_'.$shortname.'.zip';
	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("Could not open file <$filename>\n");
		}
	$directory = realpath('./plugins/'.$shortgroup.'/'.$shortname.'/');
	addDirectoryToZip($zip,$directory,$directory);
	echo "numfiles: " . $zip->numFiles . "\n";
	echo "status:" . $zip->status . "\n";
	$zip->close();
	}


function ZipPackage($pkgname,$shortname,$fls){
	echo ' Adding package -= '.$pkgname.' =- '.PHP_EOL;
	$zip = new ZipArchive();
	$filename = './'.$shortname.'.zip';
	$directory = realpath('./');

	if($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("Could not open file <$filename>\n");
		}
	if(isset($fls['components'])){
		foreach($fls['components'] as $fc){
			echo ' Adding component "'.$fc.'"...'.PHP_EOL;
			$zip->addFile($directory.'/'.$fc.'.zip','packages/'.$fc.'.zip');
			}
		}
	if(isset($fls['modules'])){
		foreach($fls['modules'] as $fc){
			echo ' Adding module "'.$fc.'.zip'.'"...'.PHP_EOL;
			$zip->addFile($directory.'/'.$fc.'.zip','packages/'.$fc.'.zip');
			}
		}
	if(isset($fls['plugins'])){
		foreach($fls['plugins'] as $fc){
			echo ' Adding plugin "'.$fc.'.zip'.'"...'.PHP_EOL;
			$zip->addFile($directory.'/'.$fc.'.zip','packages/'.$fc.'.zip');
			}
		}
	if(isset($fls['libraries'])){
		foreach($fls['libraries'] as $fc){
			echo ' Adding library "'.$fc.'.zip'.'"...'.PHP_EOL;
			$zip->addFile($directory.'/'.$fc.'.zip','packages/'.$fc.'.zip');
			}
		}


	$zip->addFile($directory.'/packages/'.$shortname.'.xml',$shortname.'.xml');
	echo "numfiles: " . $zip->numFiles . "\n";
	echo "status:" . $zip->status . "\n";
	$zip->close();
	}

ZipLibrary('Brilliant Library','brilliant');

ZipComponent('AddArticle','com_addarticle');
ZipModule('AddArticle','mod_addarticle');
ZipPackage('AddArticle','pkg_addarticle',array('components'=>array('com_addarticle'),'modules'=>array('mod_addarticle')));

ZipLibrary('Regoffices Library','brilliant_regoffices');
ZipComponent('Regoffices','com_regoffices');
ZipPlugin('Regoffices XMAP','xmap','com_regoffices');
ZipPackage('Regoffices package','pkg_regoffices',array('components'=>array('com_regoffices'),'plugins'=>array('plg_xmap_com_regoffices'),'libraries'=>array('lib_brilliant','lib_brilliant_regoffices')));

ZipPlugin('Virtuemart 2 - shipment by categories','vmshipment','vm2_categories_shipping');
ZipPlugin('Virtuemart 3 - shipment by categories','vmshipment','vm3_categories_shipping');

ZipComponent('BLogin','com_blogin');