<?php
//=================================================================
//
//
//=================================================================
class BExhibitionsIndustries{
	protected static $instance=NULL;
	protected $industries_cache;
	protected $exb_industries_cache;

	//=========================================================
	// Constructor
	//=========================================================
	public function __construct(){
		$this->industries_cache=array();
		$this->exb_industries_cache=array();
		}
	//=========================================================
	// Returns the global Session object, only creating it
	// if it doesn't already exist.
	//=========================================================
	public static function getInstance(){
		if (!is_object(self::$instance))
			self::$instance=new BExhibitionsIndustries();
		return self::$instance;
		}
	//=========================================================
	// Get the industries list by exhibitions IDs.
	// Returns list of list of industries:
	// $r[exhibition id][industry id]=object
	//=========================================================
	public function industries_get_by_exhibitions($ids){
		$exbind=array();
		$ids_m=array();
		foreach($ids as $id){
			$exbind[$id]=NULL;
			if(!isset($this->exb_industries_cache[$id])){
				$this->exb_industries_cache[$id]=array();
				array_push($ids_m,$id);
				}
			}
		$db=JFactory::getDBO();
		if(empty($db)){
			return $ind;
			}
		$ids_q='';
		foreach($ids_m as $id)
			$ids_q.=(empty($ids_q)?'':',').$id;
		$query = $db->getQuery(true);
		//Get industries...
		$db->setQuery('SELECT exbi_id,exbi_exhibition,exbi_industry FROM #__exhibitions_industries WHERE exbi_exhibition in ('.$ids_q.')');
		$exbidsq=$db->loadObjectList();
		//Collect industries IDS by exhibition in the cache 
		foreach($exbidsq as $l)
			array_push($this->exb_industries_cache[(int)$l->exbi_exhibition],(int)$l->exbi_industry);
		//Collect all industries IDS at single list (to) 
		$ind_ids=array();
		foreach($ids as $id){
			foreach($this->exb_industries_cache[$id] as $ind_id)
				$ind_ids[$ind_id]=$ind_id;
			}
		//Preload industries list...
		$this->industries_get($ind_ids);
		foreach($ids as $id)
			$exbind[$id]=$this->industries_get($this->exb_industries_cache[$id]);
		return $exbind;
		}
	//=========================================================
	// Get the industries list by exhibition ID.
	//=========================================================
	public function industries_get_by_exhibition($id){
		$list=$this->industries_get_by_exhibitions(array($id));
		return $list[$id];
		}
	//=========================================================
	// Get the industries list by ID of industries...
	//=========================================================
	public function industries_get_all(){
		$ids=$this->get_industries_ids();		
		return $this->industries_get($ids);
		//return $this->industries_get(array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15));
		}
	//=========================================================
	// Get the industries list by ID of industries...
	//=========================================================
	public function industry_get($id){
		$arr=$this->industries_get(array($id));
		return isset($arr[$id])?$arr[$id]:NULL;
		}
	//=========================================================
	// Get industries ids from database
	//=========================================================
	public function get_industries_ids(){
		//-------------------------------------------------
		//Trying to get industries ids from database
		//-------------------------------------------------
		$db=JFactory::getDBO();
		if(empty($db)){
			echo('DB CONNECTION FAILED...');die();
			return false;
			}
		$query=$db->getQuery(true);
		$query->select($db->quoteName('ind_id'));
		$query->from($db->quoteName('#__industries'));
		$db->setQuery($query);
		$list=$db->loadObjectList();		
		foreach ($list as $item){
			$ids[]=(int)$item->ind_id;
			}
		return $ids;
		}
	//=========================================================
	// Get the industries list by ID of industries...
	//=========================================================
	public function industries_get($ids){
		$industries=array();
		//-------------------------------------------------
		//Trying to get industries from internal cache
		//-------------------------------------------------
		$ids_c=array(); //left (not founded) IDs as integer
		$ids_k=array(); //left (not founded) IDs as external cache key

		foreach($ids as $id)
			if(isset($this->industries_cache[$id])){
				$industries[$id]=$this->industries_cache[$id];
				}else{
				if($id>0){
					$ind[$id]=NULL;
					$ids_c[$id]=$id;
					$ids_k[$id]='exhibitions:ind:'.$id;
					}
				}
		if(empty($ids_c))
			return $industries; //If we found all industries, return it
		//-------------------------------------------------
		//Trying to get left categories from external cache
		//-------------------------------------------------
		jimport('brilliant.cache.general');
		if(BCache::isEnabled()){
			$cache=BCache::getInstance();
			$ids_m=array();
			$ids_q='';
			$ind_c=$cache->getMultiple($ids_k);
			foreach($ids_c as $id){
				$key='exhibitions:ind:'.$id;
				if((isset($ind_c[$key]))&&(!empty($ind_c[$key]))){
					$industries[$id]=new BExhibitionsIndustry();
					$industries[$id]->load($ind_c[$key]);
					$this->industries_cache[$id]=$ind[$id];
					}else{
					array_push($ids_m,$id);
					$ids_q.=(empty($ids_q)?'':',').$id;
					}
				}
			}else{
			$cache=NULL;
			$ids_m=$ids_c;
			}
		if(empty($ids_m))
			return $ind;
		//-------------------------------------------------
		//Trying to get left industries from database
		//-------------------------------------------------
		//jimport('brilliant.exhibitions.language');
		//$l=BLanguages::getInstance();
		//$languages=$l->languages_get_all();


		jimport('joomla.language.language');
		$lang=JLanguage::getInstance('');
		$languages=$lang->getKnownLanguages();

		$db=JFactory::getDBO();
		if(empty($db))return $ind;
		$ids_q='';
		foreach($ids_m as $id)
			$ids_q.=(empty($ids_q)?'':',').$id;
		$query = $db->getQuery(true);
		//Get industries...
		$db->setQuery('SELECT ind_id FROM #__industries WHERE ind_id in ('.$ids_q.')');
		$inds=$db->loadObjectList();
		//Get industries translates...
		$db->setQuery('SELECT indl_id,indl_industry,indl_lang,indl_name FROM #__industries_lang WHERE indl_industry in ('.$ids_q.')');
		$indl=$db->loadObjectList();
		//
		foreach($inds as $ind){
			$indq=new stdClass();
			$indq->id=(int)$ind->ind_id;
			$indq->name=array();

			foreach($indl as $el)
				if($el->indl_industry == $indq->id){
					$indq->name[$el->indl_lang]=$el->indl_name;
					}
			//Set unsetted items to empty
			foreach($languages as $l){
				if(!isset($indq->name[$l['tag']]))
					$indq->name[$l['tag']]='';
				}
			$indx=new BExhibitionsIndustry();
			$indx->load($indq);
			$industries[$indx->id]=$indx;
			$this->industries_cache[$indx->id]=$indx;
			if($cache){
				$cache->set('exhibitions:ind:'.$id,$indq,3600);//1 hour
				}
			}
		return $industries;
		}
	} //end of BIndustries

//=================================================================
//
//
//=================================================================
class BExhibitionsIndustry{
	private $lang;
	public $id;
	public $name;
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
	public function load($ind){
		$this->id=$ind->id;
		$this->name=$ind->name;
		}//end of load
	public function getName($lang=''){
		if(!empty($lang)){
			return $this->name[$lang];			
		}else{
			return $this->name[$this->lang];
			}
		}
	public function setName($name, $lang=''){
		if(!empty($lang)){
			$this->name[$lang]=$name;			
		}else{
			$this->name[$this->lang]=$name;
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
		->update($db->quoteName('#__industries'))
		->set($db->quoteName('ind_name').' = "'.$db->quote($this->name[$this->lang]).'"')
		->where($db->quoteName('ind_id').'='.((int)$this->id));

		//Query2 - delete translated data...
		$qr2=$db->getQuery(true);
		$qr2->delete($db->quoteName('#__industries_lang'))->where($db->quoteName('indl_industry').'='.((int)$this->id));

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
		$qr3='INSERT INTO #__industries_lang (`indl_industry`, `indl_lang`, `indl_name`) VALUES'.$qr3;
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
		$qr1='INSERT INTO #__industries (`ind_name`) VALUES '.'('.$db->quote($this->name[$lang->getTag()]).')';	
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			$db->query();
			return false;
			}
		$this->id=(int)$db->insertid();		

		jimport('brilliant.exhibitions.language');
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
		$qr2='INSERT INTO #__industries_lang (`indl_industry`, `indl_lang`, `indl_name`) VALUES '.$qr2;		
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
	} //end of BExhibitionsIndustry

?>