<?php
//============================================================
// Sets of functions and classes to work with cache as
// MemCached
//
// Author: Andrii Biriev, b@brilliant.ua
// Copyright © Brilliant IT corporation, www.it.brilliant.ua
//============================================================
if(DEBUG_MODE){
	bimport('debug.general');
	}
class BCacheMemcached extends BCache{
	protected $memcached=NULL;
	protected $memcached_ver='';
	//================================================================================
	// Returns the global Cache object, creating only if it doesn't already exist.
	//================================================================================
	public function getMemcached(){
		if(!is_object($this->memcached)){
			if(!class_exists('Memcached')){
				if(DEBUG_MODE){
					BDebug::error('Memcached class not found!');
					}
				return NULL;
				}
			$this->memcached=new Memcached();
			$mc=$this->memcached;
			$mc->addServer("127.0.0.1",11211);
			$this->memcached_ver=$mc->getVersion();
			}
		return $this->memcached;
		}
	//================================================================================
	// Get the data from cache...
	//================================================================================
	public function get($key){
		if(DEBUG_MODE){
			bimport('debug.general');
			BDebug::message('[Memcached]: Get key('.$key.')');
			}
		$mc=$this->getMemcached();
		if(empty($mc))return NULL;
		$this->queries_get_count++;
		return $mc->get($key);
		}
	//================================================================================
	// Get array of the data from cache by array of keys...
	//================================================================================
	public function mget($keys){
		if(DEBUG_MODE){
			bimport('debug.general');
			BDebug::message('[Memcached]: Mget keys('.implode(' | ',$keys).')');
			}
		$mc=$this->getMemcached();
		if(empty($mc))return NULL;
		$this->queries_mget_count++;
		return $mc->getMulti($keys);
		}
	//================================================================================
	// Set the data to the cache...
	//================================================================================
	public function set($key,$value,$expired){
		if(DEBUG_MODE){
			bimport('debug.general');
			BDebug::message('[Memcached]: Set key('.$key.')');
			}
		$mc=$this->getMemcached();
		if(empty($mc))return false;
		$this->queries_set_count++;
		return $mc->set($key,$value,$expired);
		}
	//================================================================================
	// Multi set the data to the cache...
	//================================================================================
	public function mset($values,$expired){
		if(DEBUG_MODE){
			bimport('debug.general');
			BDebug::message('[Memcached]: MSet'.var_export($values,true));
			}
		$mc=$this->getMemcached();
		if(empty($mc))return false;
		$this->queries_mset_count++;
		return $mc->setMulti($values,$expired);
		}
	//================================================================================
	// Delete the data in the cache...
	//================================================================================
	public function delete($key){
		if(DEBUG_MODE){
			bimport('debug.general');
			BDebug::message('[Memcached]: Delete key('.$key.')');
			}
		$mc=$this->getMemcached();
		if(empty($mc))return false;
		$this->queries_delete_count++;
		return $mc->delete($key);
		}
	}
