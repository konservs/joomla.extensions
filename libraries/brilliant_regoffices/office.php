<?php
/**
 * Sets of functions and classes to work with single office.
 *
 * @author Andrii Biriev
 *
 * @copyright Copyright © Brilliant IT corporation, www.it.brilliant.ua
 */

jimport('brilliant.items.item');

class BRegofficesOffice extends BItemsItem{
	//protected $tablename='#__regoffices_countries_lang';
	protected $tablename='#__regoffices_offices';
	protected $lngtablename='#__regoffices_offices_lang';
	protected $lngtablekey='office';
	protected $collectionname='BRegofficesOffices';
	/**
	 * Constructor - init fields...
	 */
	function __construct() {
		parent::__construct();
		//$this->fieldAddRaw('status','string');
		//Statistics (all fields are readonly)
		$this->fieldAddRaw('country','int');
		$this->fieldAddRaw('region','int');
		$this->fieldAddRaw('city','int');
		$this->fieldAddRaw('lat','float');
		$this->fieldAddRaw('lng','float');
		//
		$this->fieldAddRaw('name','string',array('multilang'=>3));
		$this->fieldAddRaw('address','string',array('multilang'=>3));
		$this->fieldAddRaw('phone','string',array('multilang'=>3));
		$this->fieldAddRaw('site','string',array('multilang'=>3));
		$this->fieldAddRaw('created','dt',array('multilang'=>3));
		$this->fieldAddRaw('modified','dt',array('multilang'=>3));
		}
	/**
	 *
	 */
	public function getname($lang=''){
		return $this->getlangvar('name',$lang);
		}
	/**
	 *
	 */
	public function getcountry($lang=''){
		if(empty($this->country)){
			return NULL;
			}
		jimport('brilliant_regoffices.countries');
		$brc=BRegofficesCountries::GetInstance();
		$country=$brc->item_get($this->country);
		return $country;
		}
	/**
	 *
	 */
	public function getregion($lang=''){
		if(empty($this->region)){
			return NULL;
			}
		jimport('brilliant_regoffices.regions');
		$brr=BRegofficesRegions::GetInstance();
		$region=$brr->item_get($this->region);
		return $region;
		}
	/**
	 *
	 */
	public function getcity($lang=''){
		if(empty($this->city)){
			return NULL;
			}
		jimport('brilliant_regoffices.cities');
		$brc=BRegofficesCities::GetInstance();
		$city=$brc->item_get($this->city);
		return $city;
		}
	/**
	 * Run Update query in the database & reload cache
	 * returns true if OK and false if not
	 *
	 */
	public function dbupdate(){
		if(empty($this->id)){
			return false;
			}
		$db=JFactory::GetDBO();
		if(empty($db)){
			return false;
			}
		//
		//$this->modified=new DateTime();
		$lang_list=JLanguageHelper::getLanguages();
		//Get query
		$qr1='UPDATE `'.$this->tablename.'` SET ';
		$qr1.='`status`="'.$this->status.'", ';
		$qr1.='`country`='.(int)$this->country.', ';
		$qr1.='`region`='.(int)$this->region.', ';
		$qr1.='`city`='.(int)$this->city.', ';
		$qr1.='`lat`='.(float)$this->lat.', ';
		$qr1.='`lng`='.(float)$this->lng;
		$qr1.=' WHERE (`id`='.$this->id.')';

		$qr2='DELETE FROM '.$this->lngtablename.' WHERE ('.$this->lngtablekey.'='.$this->id.')';
		//
		$qr3vals=array();
		foreach($lang_list as $language){
			$created=$this->{'created_'.$language->lang_code};
			if(empty($created)){
				$created=new DateTime();;
				}
			$modified=new DateTime();
			$this->{'modified_'.$language->lang_code}=$modified;

			$qr3vals[]='('.$this->id.', '.
			'"'.$language->lang_code.'",'.
			$db->quote($this->getlangvar('name',$language->lang_code)).','.
			$db->quote($this->getlangvar('address',$language->lang_code)).','.
			$db->quote($this->getlangvar('phone',$language->lang_code)).','.
			$db->quote($this->getlangvar('site',$language->lang_code)).','.
			'"'.$created->format('Y-m-d H:i:s').'",'.
			'"'.$modified->format('Y-m-d H:i:s').'")';
			}
		$qr3='INSERT INTO '.$this->lngtablename.' (`'.$this->lngtablekey.'`,`language`,`name`,`address`,`phone`,`site`,`created`,`modified`) VALUES'.implode(',',$qr3vals);
		//
		$db->setQuery('START TRANSACTION');
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			return false;
			}
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');               
			return false;
			}
		$db->setQuery($qr2);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');               
			return false;
			}
		$db->setQuery($qr3);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');               
			return false;
			}
		$db->setQuery('COMMIT');
		if(!$db->query()){
			$db->setQuery('ROLLBACK');               
			return false;
			}
		//Updating cache...
		//$this->updatecache();
		//Return result
		return true;
		}
	/**
	 * Run Update query in the database & reload cache
	 * returns true if OK and false if not
	 *
	 */
	public function dbinsert(){
		$db=JFactory::GetDBO();
		if(empty($db)){
			return false;
			}
		//
		//$this->modified=new DateTime();
		$lang_list=JLanguageHelper::getLanguages();
		//Get query
		$qr1='INSERT INTO `'.$this->tablename.'` (`status`,`country`,`region`,`city`,`lat`,`lng`) VALUES ("'.
			$this->status.'",'.(int)$this->country.','.(int)$this->region.','.(int)$this->city.','.
			(float)$this->lat.','.(float)$this->lng.')';
		//
		$db->setQuery('START TRANSACTION');
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			return false;
			}
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			return false;
			}
		$this->id=$db->insertid();

		//
		$qr2='DELETE FROM '.$this->lngtablename.' WHERE ('.$this->lngtablekey.'='.$this->id.')';
		//
		$qr3vals=array();
		foreach($lang_list as $language){
			$created=$this->{'created_'.$language->lang_code};
			if(empty($created)){
				$created=new DateTime();;
				}
			$modified=new DateTime();
			$this->{'modified_'.$language->lang_code}=$modified;

			$qr3vals[]='('.$this->id.', '.
			'"'.$language->lang_code.'",'.
			$db->quote($this->getlangvar('name',$language->lang_code)).','.
			$db->quote($this->getlangvar('address',$language->lang_code)).','.
			$db->quote($this->getlangvar('phone',$language->lang_code)).','.
			$db->quote($this->getlangvar('site',$language->lang_code)).','.
			'"'.$created->format('Y-m-d H:i:s').'",'.
			'"'.$modified->format('Y-m-d H:i:s').'")';
			}
		$qr3='INSERT INTO '.$this->lngtablename.' (`'.$this->lngtablekey.'`,`language`,`name`,`address`,`phone`,`site`,`created`,`modified`) VALUES'.implode(',',$qr3vals);
		//
		$db->setQuery($qr2);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			return false;
			}
		$db->setQuery($qr3);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			return false;
			}
		$db->setQuery('COMMIT');
		if(!$db->query()){
			$db->setQuery('ROLLBACK');               
			return false;
			}
		//Updating cache...
		//$this->updatecache();
		//Return result
		return true;
		}


	}