<?php
//============================================================
// Abstract class for collections
//
// Author: Andrii Biriev
// Author: Andrii Karepin
// Copyright © Brilliant IT corporation, www.it.brilliant.ua
//============================================================
jimport('brilliant.items.item');
define('CACHE_TYPE',0);

abstract class BItems{
	protected $tablename='';
	protected $lngtablename='';
	protected $lngtablekey='';
	protected $lngtablelang='language';
	protected $itemclassname='';
	protected $primarykey='id';
	/**
	 * Detect necessary language, if $lang is not set.
	 * If the $lang is set - return it;
	 * 
	 * @param string $lang
	 * @return string detected language
	 */
	protected function detectlang($lang){
		if(empty($lang)){
			bimport('cms.language');
			$lang=BLang::$langcode;
			}
		return $lang;
		}
	/**
	 * Load data from db/cache array.
	 */
	public function items_get($ids){
		if(!is_array($ids)){
			return array();
			}
		$items=array();
		//-------------------------------------------------
		//Trying to get items from internal cache
		//-------------------------------------------------
		$ids_c=array(); //IDs as integer
		$ids_k=array(); //IDs as external cache key
		foreach($ids as $id)
			if(isset($this->cache_items[$id])){
				$items[$id]=$this->cache_items[$id];
				}else{
				if($id>0){
					$items[$id]=NULL;
					$ids_c[$id]=$id;
					$ids_k[$id]=$this->tablename.':itemid:'.$id;
					}
				}
		if(empty($ids_c)){
			return $items;
			}
		//-------------------------------------------------
		//Trying to get left items from external cache
		//-------------------------------------------------
		$item_obj=array();//cache objects...
		$ids_q='';
		if(CACHE_TYPE){
			bimport('cache.general');
			$cache=BCache::getInstance();
			if(empty($cache))return $items;
			$ids_m=array();
			$items_c=$cache->mget($ids_k);
			foreach($ids_c as $id){
				$key=$this->tablename.':itemid:'.$id;
				if((isset($items_c[$key]))&&(!empty($items_c[$key]))){
					$classname=$this->itemclassname;
					$items[$id]=new $classname();
					$items[$id]->load($items_c[$key]);
					$this->cache_items[$id]=$items[$id];
					}else{
					array_push($ids_m,$id);
					$ids_q.=(empty($ids_q)?'':',').$id;
					}
				}
			}else{
			$ids_m=$ids_c;
			foreach($ids_m as $id)
				$ids_q.=(empty($ids_q)?'':',').$id;
			}
		if(empty($ids_m)){
			return $items;
			}
		//-------------------------------------------------
		// Trying to get left items from database
		//-------------------------------------------------
		$db=JFactory::GetDBO();
		if(empty($db)){
			return $items;
			}
		//
		$qr='SELECT * from `'.$this->tablename.'` WHERE (id in ('.$ids_q.'))';
		$q=$db->setQuery($qr);
		if(empty($q)){
			/*if(DEBUG_MODE){
				bimport('debug.general');
				BDebug::error('[items]: items_get(): Could not execute query! MySQL error: '.$db->lasterror());
				}*/
			return $items;
			}
		$tocache=array();
		$dbitems=$db->loadAssocList();
		foreach($dbitems as $l){
			$id=(int)$l['id'];
			$item_obj[$id]=$l;
			}

		//
		if(!empty($this->lngtablename)){
			$languages = JLanguageHelper::getLanguages();
			foreach($item_obj as &$itm){
				$itm['_translations']=array();
				foreach($languages as $lng){
					$itm['_translations'][$lng->lang_code]=array();
					}
				}
			$qr='SELECT * from `'.$this->lngtablename.'` WHERE (`'.$this->lngtablekey.'` in ('.$ids_q.'))';
			$q=$db->setQuery($qr);
			if(empty($q)){
				return $items;
				}
			$dbitemsl=$db->loadAssocList();
			foreach($dbitemsl as $ll){
				$id=(int)$ll[$this->lngtablekey];
				$lng=$ll[$this->lngtablelang];
				unset($ll[$this->lngtablekey]);
				unset($ll[$this->lngtablelang]);
				$item_obj[$id]['_translations'][$lng]=$ll;
				}
			//var_dump($item_obj); die();
			}

		//
		foreach($item_obj as $k=>$l){
			$classname=$this->itemclassname;
			if(!class_exists($classname)){
				$msg='Class "'.$classname.'" does not exist!';
				//BLog::addtolog($msg,LL_ERROR);
				die($msg);
				return false;
				}
			$items[$k]=new $classname();
			$items[$k]->load($l);
			$this->cache_items[$k]=$items[$k];
			if(CACHE_TYPE){
				$tocache[$this->tablename.':itemid:'.$k]=$l;
				}
			}
		if(CACHE_TYPE&&count($tocache)!=0){
			$cache->mset($tocache,3600);//1 hour
			}
		//var_dump($items); die();
		return $items;
		}
	/**
	 * Get single item
	 */
	public function item_get($id){
		$list=$this->items_get(array($id));
		$item=reset($list);
		return $item;
		}
	/**
	 *
	 */
	public function items_filter($params){
		$ids=$this->items_filter_ids($params);
		return $this->items_get($ids);
		}
	/**
	 *
	 */
	public function items_get_all(){
		$params=array();
		return $this->items_filter($params);
		}
	/**
	 *
	 */
	public function items_filter_sql($params,&$wh,&$jn){
		$wh=array();
		$jn=array();
		if(isset($params['exclude'])&&(is_array($params['exclude']))){
			$wh[]= '(`'.$this->primarykey.'` not in ('.implode(',', $params['exclude']).'))';
			}
		return true;
		}
	/**
	 * Items list cache hash.
	 */
	public function items_filter_hash($params){
		$itemshash=$this->tablename.':list';
		if(isset($params['exclude'])&&(is_array($params['exclude']))){
			$itemshash.=':exclude-'.implode('-',$params['exclude']);
			}
		return $itemshash;
		}
	/**
	 *
	 */
	public function items_filter_ids($params){
		if(!$db=JFactory::getDBO()){
			return false;
			}
		$qr='select `'.$this->tablename.'`.`'.$this->primarykey.'` from `'.$this->tablename.'`';
		$this->items_filter_sql($params,$wh,$jn);
		if(!empty($jn)){
			$qr.=' '.implode(' ',$jn);
			}
		if(!empty($wh)){
			$qr.=' WHERE ('.implode(' AND ',$wh).')';
			}
		if(isset($params['orderby'])){
			$orderasc=isset($params['orderdir'])?' '.$params['orderdir']:'';
			$qr.=' ORDER BY `'.$params['orderby'].'`'.$orderasc;
			}
		//Limit & offset
		if(!empty($params['limit'])){
			$limit=(int)$params['limit'];
			$offset=(int)$params['offset'];
			$qr.=' LIMIT '.$limit;
			if($offset){
				$qr.=' OFFSET '.$offset;
				}
			}
		//Execute query
		$q=$db->setQuery($qr);
		if(empty($q)){
			return false;
			}
		$ids=array();
		$list=$db->loadAssocList();
		foreach($list as $l){
			$id=(int)$l['id'];
			$ids[]=$id;
			}
		return $ids;
		}
	//========================================================
	// Search - internal cache variable
	// Keys - necessary IDs
	// notexists - IDs to load from database or external cache
	// 
	// returns values by keys
	//========================================================
	protected function split_intcache($intcache,$keys,&$notexist){
		if(!is_array($intcache)){
			$notexist=$keys;
			return array();
			}
		$notexist=array();
		$res=array();
		foreach($keys as $key){
			if(isset($intcache[$key])){
				$res[$key]=$intcache[$key];
				}else{
				$notexist[$key]=$key;
				}
			}
		return $res;
		}
	//========================================================
	// $extcache - array
	// 
	// returns associative array of loaded data
	//========================================================
	protected function split_extcache($data,$keys,&$notexist){
		$extcache=array();
		foreach($keys as $id=>$key){
			if(!empty($data[$key])){
				$extcache[$id]=$data[$key];
				}else{
				$notexist[$id]=$id;
				}
			}
		return $extcache;
		}
	//========================================================
	//
	//========================================================
	protected function generatekeys($ids,$prefix){
		$keys=array();
		foreach($ids as $id){
			$keys[$id]=$prefix.$id;
			}
		return $keys;
		}
	}
