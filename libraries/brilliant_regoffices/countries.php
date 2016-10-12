<?php
/**
 * Sets of functions and classes to work with general forum functions.
 *
 * @author Andrii Biriev
 *
 * @copyright Copyright © Brilliant IT corporation, www.it.brilliant.ua
 */
jimport('brilliant.items.general');
jimport('brilliant.items.list');
jimport('brilliant_regoffices.country');

class BRegofficesCountries extends BItemsList{
	protected $tablename='#__regoffices_countries';
	protected $lngtablename='#__regoffices_countries_lang';
	protected $lngtablekey='country';
	protected $itemclassname='BRegofficesCountry';
	protected static $instance=NULL;
	/**
	 * Returns the global BRegofficesCountries object, only creating it
	 * if it doesn't already exist.
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance=new BRegofficesCountries();
			}
		return self::$instance;
		}
	/**
	 *
	 */
	public function items_filter_sql($params,&$wh,&$jn){
		$wh=array();
		$jn=array();
		//
		parent::items_filter_sql($params,$wh,$jn);
		$db=JFactory::GetDBO();
		$languages = JLanguageHelper::getLanguages();
		//
		if(isset($params['iso'])){
			$wh[]= '(`iso`='.$db->quote($params['iso']).')';
			}
		//
		foreach($languages as $lng){
			if(isset($params['alias:'.$lng->lang_code])){
				$jn[]='LEFT JOIN `'.$this->lngtablename.'` on `'.$this->lngtablename.'`.`'.$this->lngtablekey.'`=`'.$this->tablename.'`.`'.$this->primarykey.'`';
				$wh[]= '(`'.$this->lngtablename.'`.`language`='.$db->quote($lng->lang_code).')';
				$wh[]= '(`'.$this->lngtablename.'`.`alias`='.$db->quote($params['alias:'.$lng->lang_code]).')';
				}
			}
		return true;
		}
	/**
	 * Items list cache hash.
	 */
	public function items_filter_hash($params){
		$itemshash=parent::items_filter_hash($params);
		if(isset($params['iso'])){
			$itemshash.=':iso-'.$params['iso'];
			}
		return $itemshash;
		}
	/**
	 *
	 */
	public function item_get_by_iso($iso){
		$list=$this->items_filter(array('iso'=>$iso));
		$item=reset($list);
		return $item;
		}
	/**
	 *
	 */
	public function item_get_by_alias($alias,$lang){
		$list=$this->items_filter(array('alias:'.$lang=>$alias));
		$item=reset($list);
		return $item;
		}

	}