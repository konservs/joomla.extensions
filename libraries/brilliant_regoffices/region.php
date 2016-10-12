<?php
/**
 * Sets of functions and classes to work with single forum.
 *
 * @author Andrii Biriev
 *
 * @copyright Copyright © Brilliant IT corporation, www.it.brilliant.ua
 */

jimport('brilliant.items.item');

class BRegofficesRegion extends BItemsItem{
	//protected $tablename='#__regoffices_countries_lang';
	protected $langtable='';
	protected $collectionname='BRegofficesRegions';
	/**
	 * Constructor - init fields...
	 */
	function __construct() {
		parent::__construct();
		//
		$this->fieldAddRaw('country','int');
		$this->fieldAddRaw('status','enum',array('P','N','D'));
		//
		$this->fieldAddRaw('name','string',array('multilang'=>3));
		$this->fieldAddRaw('alias','string',array('multilang'=>3));
		$this->fieldAddRaw('description','string',array('multilang'=>3));
		$this->fieldAddRaw('h1','string',array('multilang'=>3));
		$this->fieldAddRaw('title','string',array('multilang'=>3));
		$this->fieldAddRaw('metadesc','string',array('multilang'=>3));
		$this->fieldAddRaw('metakeyw','string',array('multilang'=>3));
		$this->fieldAddRaw('created','dt',array('multilang'=>3));
		$this->fieldAddRaw('modified','dt',array('multilang'=>3));
		//Statistics (all fields are readonly)
		$this->fieldAddRaw('cities','int',array('readonly'=>true));
		$this->fieldAddRaw('offices','int',array('readonly'=>true));
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
		$qr1='UPDATE `#__regoffices_regions` SET ';
		$qr1.='`status`="'.$this->status.'", ';
		$qr1.='`country`='.(int)$this->country.', ';
		$qr1.='`admin1code`=NULL';
		$qr1.=' WHERE (`id`='.$this->id.')';

		$qr2='DELETE FROM #__regoffices_regions_lang WHERE (region='.$this->id.')';
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
			$db->quote($this->getlangvar('alias',$language->lang_code)).','.
			$db->quote($this->getlangvar('description',$language->lang_code)).','.
			$db->quote($this->getlangvar('h1',$language->lang_code)).','.
			$db->quote($this->getlangvar('title',$language->lang_code)).','.
			$db->quote($this->getlangvar('metadesc',$language->lang_code)).','.
			$db->quote($this->getlangvar('metakeyw',$language->lang_code)).','.
			'"'.$created->format('Y-m-d H:i:s').'",'.
			'"'.$modified->format('Y-m-d H:i:s').'")';
			}
		$qr3='INSERT INTO #__regoffices_regions_lang (`region`,`language`,`name`,`alias`,`description`,`h1`,`title`,`metadesc`,`metakeyw`,`created`,`modified`) VALUES'.implode(',',$qr3vals);
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
		$qr1='INSERT INTO `#__regoffices_regions` (`status`,`country`,`admin1code`) VALUES ("'.$this->status.'",'.(int)$this->country.',NULL)';
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
		$qr2='DELETE FROM #__regoffices_regions_lang WHERE (region='.$this->id.')';
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
			$db->quote($this->getlangvar('alias',$language->lang_code)).','.
			$db->quote($this->getlangvar('description',$language->lang_code)).','.
			$db->quote($this->getlangvar('h1',$language->lang_code)).','.
			$db->quote($this->getlangvar('title',$language->lang_code)).','.
			$db->quote($this->getlangvar('metadesc',$language->lang_code)).','.
			$db->quote($this->getlangvar('metakeyw',$language->lang_code)).','.
			'"'.$created->format('Y-m-d H:i:s').'",'.
			'"'.$modified->format('Y-m-d H:i:s').'")';
			}
		$qr3='INSERT INTO #__regoffices_regions_lang (`region`,`language`,`name`,`alias`,`description`,`h1`,`title`,`metadesc`,`metakeyw`,`created`,`modified`) VALUES'.implode(',',$qr3vals);
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
	/**
	 *
	 */
	public function getUrl($absolute=false){
		if($absolute){
			return JRoute::_('index.php?option=com_regoffices&view=region&id='.$this->id,true,-1);
			}
		return JRoute::_('index.php?option=com_regoffices&view=region&id='.$this->id);
		}
	}