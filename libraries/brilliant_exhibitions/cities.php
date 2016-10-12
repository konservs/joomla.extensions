<?php
//=================================================================
//
//
//=================================================================
class BCities{
	protected static $instance=NULL;
	protected $cities_cache;
	//=========================================================
	// Constructor
	//=========================================================
	public function __construct(){
		$this->cities_cache=array();
		}
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
		/*$cat=array();
		//-------------------------------------------------
		//Trying to get categories from internal cache
		//-------------------------------------------------
		$ids_c=array(); //left (not founded) IDs as integer
		$ids_k=array(); //left (not founded) IDs as external cache key
		foreach($ids as $id)
			if(isset($this->categories_cache[$id])){
				$cat[$id]=$this->categories_cache[$id];
				}else{
				if($id>0){
					$cat[$id]=NULL;
					$ids_c[$id]=$id;
					$ids_k[$id]='exhibitions:exb:'.$id;
					}
				}
		if(empty($ids_c))
			return $cat; //If we found all categories, return it
		//-------------------------------------------------
		//Trying to get left categories from external cache
		//-------------------------------------------------
		jimport('joomla.cache.cache');
		$cache=JCache::getInstance();
		if(empty($cache))return $cat;

		$ids_m=array();
		$ids_q='';
		$cat_c=$cache->mget($ids_k);
		foreach($ids_c as $id){
			$key='exhibitions:exb:'.$id;
			if((isset($cat_c[$key]))&&(!empty($cat_c[$key]))){
				$cat[$id]=new BContentCategory();
				$cat[$id]->load($cat_c[$key]);
				$this->categories_cache[$id]=$cat[$id];
				}else{
				array_push($ids_m,$id);
				$ids_q.=(empty($ids_q)?'':',').$id;
				}
			}
		if(empty($ids_m))
			return $cat;
		*/
		//-------------------------------------------------
		//Trying to get left categories from database
		//-------------------------------------------------
		//Get languages...
		jimport('brilliant.exhibitions.language');
		$l=BLanguages::getInstance();
		$languages=$l->languages_get_all();
		//
		$db=JFactory::getDBO();
		if(empty($db))return $cat;

		/*$ids_q='';
		foreach($ids_m as $id){
			$ids_q.=(empty($ids_q)?'':',').$id;
			}*/
		$query = $db->getQuery(true);
		//Get cities...
		$db->setQuery('SELECT ct_id, ct_people_count, ct_people_year FROM #__cities'); // WHERE ct_id in ('.$ids_q.')
		$cities=$db->loadObjectList();
		//Get Exhibition translates...
		$db->setQuery('SELECT ctl_id, ctl_city, ctl_lang, ctl_name FROM #__cities_lang'); //WHERE exbl_exhibition in ('.$ids_q.')
		$citiesl=$db->loadObjectList();
		//
		foreach($cities as $city){
			$ctq=new stdClass();
			$ctq->id=$city->ct_id;
			foreach($citiesl as $cityl)
				if($cityl->ctl_city == $ctq->id){
					$ctq->name[$cityl->ctl_lang]=$cityl->ctl_name;
					}
			//Set unsetted items to empty
			foreach($languages as $l){
				if(!isset($ctq->name[$l->lang_code]))
					$ctq->name[$l->lang_code]='';
				}
			$ctx=new BCity();
			$ctx->load($ctq);
			//$cat[$exbx->id]=$exbx;
			$this->cities_cache[$ctx->id]=$ctx;
			//$cache->set('exhibitions:exb:'.$id,$exbq,3600);//1 hour
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
		if(empty($lang)){
			return $this->name[$this->lang];
			}else{
			return $this->name[$lang];	
			}
		}
	public function setName($val,$lang=''){
		if(empty($lang)){
			$this->name[$this->lang]=$val;
			}else{
			$this->name[$lang]=$val;	
			}
		}
	public function update(){	
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		//Query1 - update general data...
		$qr1=$db->getQuery(true);
		$qr1
		->update($db->quoteName('#__cities'))
		->set($db->quoteName('ct_name').' = "'.$db->quote($this->name[$this->lang]).'"')
		->where($db->quoteName('ct_id').'='.((int)$this->id));

		//Query2 - delete translated data...
		$qr2=$db->getQuery(true);
		$qr2->delete($db->quoteName('#__cities_lang'))->where($db->quoteName('ctl_city').'='.((int)$this->id));

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
		$qr3='INSERT INTO #__cities_lang (`ctl_city`, `ctl_lang`, `ctl_name`) VALUES'.$qr3;
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
		$qr1='INSERT INTO #__cities (`ct_name`) VALUES '.'('.$db->quote($this->name[$lang->getTag()]).')';	
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
		$qr2='INSERT INTO #__cities_lang (`ctl_city`, `ctl_lang`, `ctl_name`) VALUES '.$qr2;		
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
		if($this->id){
			return $this->update();
			}else{
			return $this->insert();	
			}
		}
	} //end of BCity

?>