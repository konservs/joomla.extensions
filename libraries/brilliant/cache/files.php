<?php
//============================================================
// Sets of functions and classes to work with cache as files
//
// Author: Andrii Biriev, b@brilliant.ua
// Copyright � Brilliant IT corporation, www.it.brilliant.ua
//============================================================
define('PATH_CACHE',BROOTPATH.DIRECTORY_SEPARATOR.'/filecache/');
bimport('cache.general');
class BCacheFiles extends BCache{
	protected $cachedir='';
	//================================================================================
	//
	//================================================================================
	public function __construct(){
		$this->cachedir=PATH_CACHE;
		}
	//================================================================================
	// Garbage collector
	// Garbage collect expired cache data
	// return  boolean  True on success, false otherwise.
	//================================================================================
	public function gc(){
		$result = true;
		return $result;
		}
	//================================================================================
	// Test to see if the cache storage is available.
	// return  boolean  True on success, false otherwise.
	//================================================================================
	public static function selftest(){
		return True;
		}
	//================================================================================
	// Get the data from cache...
	//================================================================================
	public function get($key){
		if(DEBUG_CACHE){
			bimport('debug.general');
			BDebug::message('[BCache]: get('.$key.')');
			}
		$this->queries_get_count++;
		$fn=$this->cachedir.sha1($key).'.dat';
		if(!file_exists($fn))
			return false;
		$f=@fopen($fn,'r');
		if(empty($f))
			return false;

		$dt_exp=new DateTime(fgets($f));
		$dt_now=new DateTime();
		if($dt_exp<$dt_now){
			fclose($f);
			@unlink($fn);
			return false;
			}
		$ss='';
		while($s=fread($f,1024))
			$ss.=$s;
		$data=unserialize($ss);
		return $data;
		}
	//================================================================================
	// Set the data to the cache...
	//================================================================================
	public function set($key,$value,$expired){
		if(DEBUG_CACHE){
			bimport('debug.general');
			//BDebug::message('[BCache]: set('.$key.','.var_export($value,true).')');
			BDebug::message('[BCache]: set('.$key.',...)');
			}
		$dt_exp=new DateTime();
		$dt_exp->add(new DateInterval('PT'.$expired.'S'));

		$fn=$this->cachedir.sha1($key).'.dat';
		$f=@fopen($fn,'w');
		if(empty($f))
			return false;
		fwrite($f,$dt_exp->format('Y-m-d H:i:s').PHP_EOL);
		fwrite($f,serialize($value).PHP_EOL);
		fclose($f);
		return true;
		}
	//================================================================================
	// Delete the data in the cache...
	//================================================================================
	public function delete($key){
		if(DEBUG_MODE){
			bimport('debug.general');
			BDebug::message('[BCache]: delete('.$key.')...)');
			}
		$fn=$this->cachedir.sha1($key).'.dat';
		if(!file_exists($fn)){
			return true;
			}
		return unlink($fn);
		}
	}
