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
jimport('brilliant_regoffices.region');

class BRegofficesRegions extends BItemsList{
	protected $tablename='#__regoffices_regions';
	protected $lngtablename='#__regoffices_regions_lang';
	protected $lngtablekey='region';
	protected $itemclassname='BRegofficesRegion';
	protected static $instance=NULL;
	/**
	 * Returns the global BRegofficesRegions object, only creating it
	 * if it doesn't already exist.
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance=new BRegofficesRegions();
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
		if(isset($params['country'])){
			$wh[]= '(`country`='.(int)$params['country'].')';
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
		return $itemshash;
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