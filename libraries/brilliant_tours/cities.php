<?php
//=================================================================
//
//
//=================================================================
class BCities{
	protected static $instance=NULL;
	protected $cities_cache=NULL;
	//=========================================================
	// Returns the global Session object, only creating it
	// if it doesn't already exist.
	//=========================================================
	public static function getInstance(){
		if (!is_object(self::$instance))
			self::$instance=new BCities();
		return self::$instance;
		}
	//=========================================================
	// Get the all exhibitions
	//=========================================================
	public function cities_get_all(){
		if($this->cities_cache!==NULL){
			return $this->cities_cache;
			}
		$this->cities_cache=array();
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		$query=$db->getQuery(true);
		//Get cities...
		$db->setQuery('SELECT * FROM #__tours_cities'); // WHERE ct_id in ('.$ids_q.')
		$cities=$db->loadObjectList();
		foreach($cities as $city){
			$ctx=new BCity();
			$ctx->load($city);
			$this->cities_cache[$city->id]=$ctx;
			}
		return $this->cities_cache;
		}
	//================================================================================
	// Get single city
	//================================================================================
	public function city_get($id){
		//cities_get_all
		if(!isset($this->cities_cache[$id]))
			$this->cities_get_all();
		return $this->cities_cache[$id];
		}
	} //end of BContent


class BCity{
	public $id;
	private $lang;
	private $name;
	//=========================================================
	// Constructor
	//=========================================================
	public function __construct(){
		$lang = JFactory::getLanguage();
		$this->lang=$lang->getTag();
		}
	//================================================================================
	// Get single category
	//================================================================================
	public function load($city){
		$this->id=$city->id;
		$this->name=$city->name;
		}//end of load
	public function getName($lang=''){
		return $this->name;
		}
	public function setName($val,$lang=''){
		$this->name=$val;
		}
	public function getCountry(){
		return NULL;
		}
	public function update(){	
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		//Query1 - update general data...
		$qr1=$db->getQuery(true);
		$qr1
		->update($db->quoteName('#__tours_cities'))
		->set($db->quoteName('ct_name').' = "'.$db->quote($this->name[$this->lang]).'"')
		->where($db->quoteName('ct_id').'='.((int)$this->id));

		//Query2 - delete translated data...
		$qr2=$db->getQuery(true);
		$qr2->delete($db->quoteName('#__tours_cities_lang'))->where($db->quoteName('ctl_city').'='.((int)$this->id));

		$db->setQuery('START TRANSACTION');
		if(!$db->query()){
			return false;
			}
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			$db->query();
			return false;
			}
		$db->setQuery($qr2);
		if(!$db->query()){	
			$db->setQuery('ROLLBACK');
			$db->query();
			return false;
			}

		jimport('brilliant.exhibitions.languages');
		$languages=BLanguages::getInstance();
		$langs=$languages->languages_get_all();

		$qr3='';
		foreach($langs as $l) {
			$qr3.=empty($qr3)?'(':', (';
			$qr3.=((int)$this->id).', ';
			$qr3.=$db->quote($l->lang_code).', ';
			$qr3.=$db->quote(empty($this->name[$l->lang_code])?'':$this->name[$l->lang_code]);	
			$qr3.=')';
			}
		$qr3='INSERT INTO #__tours_cities_lang (`ctl_city`, `ctl_lang`, `ctl_name`) VALUES'.$qr3;
		$db->setQuery($qr3);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			$db->query();
			return false;
			}
		$db->setQuery('COMMIT');
		if(!$db->query()){				
			return false;
			}
		return true;
		}
	public function insert(){		
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		//Starting transaction	
		$db->setQuery('START TRANSACTION');
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			$db->query();
			return false;
			}
		$lang =& JFactory::getLanguage();
		$qr1='INSERT INTO #__tours_cities (`ct_name`) VALUES '.'('.$db->quote($this->name[$lang->getTag()]).')';	
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			$db->query();
			return false;
			}
		$this->id=(int)$db->insertid();		

		jimport('brilliant.exhibitions.cities');
		$l=BLanguages::getInstance();
		$languages=$l->languages_get_all();

		$qr2='';
		foreach($languages as $l){
			$qr2.=empty($qr2)?'(':', (';
			$qr2.=((int)$this->id).', ';
			$qr2.=$db->quote($l->lang_code).', ';			
			$qr2.=$db->quote(empty($this->name[$l->lang_code])?'':$this->name[$l->lang_code]);		
			$qr2.=')';
			}
		$qr2='INSERT INTO #__tours_cities_lang (`ctl_city`, `ctl_lang`, `ctl_name`) VALUES '.$qr2;		
		$db->setQuery($qr2);
		if(!$db->query()){			
			$db->setQuery('ROLLBACK');
			$db->query();
			return false;
			}
		$db->setQuery('COMMIT');
		if(!$db->query()){			
			return false;
			}		
		return true;
		}
	public function savetodb(){
		die('Libraries -> tours -> cities: saving...');
		if($this->id){
			return $this->update();
			}else{
			return $this->insert();	
			}
		}
	} //end of BCity
