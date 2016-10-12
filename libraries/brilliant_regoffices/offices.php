<?php
/**
 * Sets of functions and classes to work with Offices lists.
 *
 * @author Andrii Biriev
 *
 * @copyright Copyright © Brilliant IT corporation, www.it.brilliant.ua
 */
jimport('brilliant.items.general');
jimport('brilliant.items.list');
jimport('brilliant_regoffices.office');

class BRegofficesOffices extends BItemsList{
	protected $tablename='#__regoffices_offices';
	protected $lngtablename='#__regoffices_offices_lang';
	protected $lngtablekey='office';
	protected $itemclassname='BRegofficesOffice';
	protected static $instance=NULL;
	/**
	 * Returns the global BRegofficesOffices object, only creating it
	 * if it doesn't already exist.
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance=new BRegofficesOffices();
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
		if(isset($params['city'])){
			$wh[]= '(`city`='.(int)$params['city'].')';
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

	}