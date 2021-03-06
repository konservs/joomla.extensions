<?php
//============================================================
// Sets of functions and classes to work with cache
//
// Author: Andrii Biriev, b@brilliant.ua
// Copyright � Brilliant IT corporation, www.it.brilliant.ua
//============================================================
define('DEBUG_CACHE',0);
//============================================================
//
//============================================================
class BCache{
	protected static $instance=NULL;
	public $queries_set_count=0;
	public $queries_mset_count=0;
	public $queries_get_count=0;
	public $queries_mget_count=0;
	public $queries_gc_count=0;
	public $queries_delete_count=0;
	//====================================================
	//
	//====================================================
	public static function isEnabled(){
		$config=JFactory::getConfig();
		$caching=$config->getValue('config.caching');
		return $caching;
		}
	//====================================================
	// Returns the global Cache object, creating only if
	// it doesn't already exist.
	//====================================================
	public static function getInstance(){
		if(!is_object(self::$instance)){
			$config=JFactory::getConfig();
			$caching=$config->getValue('config.caching');
			$cache_handler=$config->getValue('config.cache_handler');
			if(!$caching){
				return NULL;
				}
			switch($cache_handler){
				case 'file':
					bimport('brilliant.cache.files');
					self::$instance=new BCacheFiles();
					break;
				case 'memcache':
					bimport('brilliant.cache.memcache');
					self::$instance=new BCacheMemcache();
					break;
				case 'memcached':
					bimport('brilliant.cache.memcached');
					self::$instance=new BCacheMemcached();
					break;
				default:
					self::$instance=NULL;
					break;
				}
			}
		return self::$instance;
		}
	//====================================================
	// The count of MySQL queries...
	//====================================================
	public static function getQueriesCount(){
		$qc=array(
			'mset'=>0,
			'set'=>0,
			'get'=>0,
			'mget'=>0,
			'gc'=>0);
		if (!is_object(self::$instance))
			return $qc;
		$cache=self::$instance;
		$qc['mset']=$cache->queries_mset_count;
		$qc['set']=$cache->queries_set_count;
		$qc['get']=$cache->queries_get_count;
		$qc['mget']=$cache->queries_mget_count;
		$qc['gc']=$cache->queries_gc_count;
		return $qc;
		}
	//====================================================
	// Get the data from cache...
	//====================================================
	public function get($key){
		$this->queries_set_count++;
		return NULL;
		}
	//====================================================
	// Get array of the data from cache by array of keys
	//====================================================
	public function mget($keys){
		$this->queries_mget_count++;
		$r=array();
		foreach($keys as $key)
			$r[$key]=$this->get($key);
		return $r;
		}
	//====================================================
	// Set the data to the cache...
	//====================================================
	public function set($key,$value,$expired){
		$this->queries_get_count++;
		return false;
		}
	//====================================================
	// Set the array of the data to the cache
	//====================================================
	public function mset($kv,$expired){
		$this->queries_mset_count++;
		$result=true;
		foreach($kv as $key=>$value){
			$r=$this->set($key,$value,$expired);
			$result=$result & $r;
			}
		return $result;
		}

	//====================================================
	// Delete the data in the cache...
	//====================================================
	public function delete($key){
		$this->queries_delete_count++;
		return false;
		}
	//====================================================
	// Garbage collector
	//====================================================
	public function gc(){
		$this->queries_gc_count++;
		}
	}
