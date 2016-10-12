<?php
//=================================================================
//
//
//=================================================================
class BToursCategories{
	protected static $instance=NULL;
	protected $categories_cache=NULL;
	protected $exb_categories_cache=NULL;
	//=========================================================
	// Returns the global Session object, only creating it
	// if it doesn't already exist.
	//=========================================================
	public static function getInstance(){
		if (!is_object(self::$instance))
			self::$instance=new BToursCategories();
		return self::$instance;
		}
	//=========================================================
	// Get the categories list by Tours IDs.
	// Returns list of list of categories:
	// $r[Tour id][category id]=object
	//=========================================================
	public function categories_get_by_Tours($ids){
		/*$exbind=array();
		$ids_m=array();
		foreach($ids as $id){
			$exbind[$id]=NULL;
			if(!isset($this->exb_categories_cache[$id])){
				$this->exb_categories_cache[$id]=array();
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
		//Get categories...
		$db->setQuery('SELECT exbi_id,exbi_Tour,exbi_category FROM #__Tours_categories WHERE exbi_Tour in ('.$ids_q.')');
		$exbidsq=$db->loadObjectList();
		//Collect categories IDS by Tour in the cache 
		foreach($exbidsq as $l)
			array_push($this->exb_categories_cache[(int)$l->exbi_Tour],(int)$l->exbi_category);
		//Collect all categories IDS at single list (to) 
		$ind_ids=array();
		foreach($ids as $id){
			foreach($this->exb_categories_cache[$id] as $ind_id)
				$ind_ids[$ind_id]=$ind_id;
			}
		//Preload categories list...
		$this->categories_get($ind_ids);
		foreach($ids as $id)
			$exbind[$id]=$this->categories_get($this->exb_categories_cache[$id]);
		return $exbind;*/
		}
	//=========================================================
	// Get the categories list by Tour ID.
	//=========================================================
	public function categories_get_by_Tour($id){
		$list=$this->categories_get_by_Tours(array($id));
		return $list[$id];
		}
	//=========================================================
	// Get the categories list by ID of categories...
	//=========================================================
	public function categories_get_all(){
		$ids=$this->get_categories_ids();		
		return $this->categories_get($ids);
		}
	//=========================================================
	// Get the categories list by ID of categories...
	//=========================================================
	public function category_get($id){
		$arr=$this->categories_get(array($id));
		return isset($arr[$id])?$arr[$id]:NULL;
		}
	//=========================================================
	// Get categories ids from database
	//=========================================================
	public function get_categories_ids(){
		//-------------------------------------------------
		//Trying to get categories ids from database
		//-------------------------------------------------
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		$query=$db->getQuery(true);
		$query->select($db->quoteName('id'));
		$query->from($db->quoteName('#__tours_categories'));
		$db->setQuery($query);
		$list=$db->loadObjectList();		
		foreach ($list as $item){
			$ids[]=(int)$item->id;
			}
		return $ids;
		}
	//=========================================================
	// Get the categories list by ID of categories...
	//=========================================================
	public function categories_get($ids){
		$categories=array();
		if(empty($ids)){
			return $categories;
			}
		$db=JFactory::getDBO();
		if(empty($db)){
			return $categories;
			}
		$ids_q='';
		foreach($ids as $id){
			$ids_q.=(empty($ids_q)?'':',').$id;
			}
		$query=$db->getQuery(true);
		//Get categories...
		$db->setQuery('SELECT * FROM #__tours_categories WHERE id in ('.$ids_q.')');
		$cats=$db->loadObjectList();
		foreach($cats as $cat){
			$catx=new BToursCategory();
			$catx->load($cat);
			$categories[$cat->id]=$catx;
			$this->categories_cache[$catx->id]=$catx;
			}
		return $categories;
		}
	} //end of BCategories

//=================================================================
//
//
//=================================================================
class BToursCategory{
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
		return $this->name;			
		}
	public function setName($name, $lang=''){
		$this->name=$name;			
		}
	public function update(){
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		//Query1 - update general data...
		$qr1=$db->getQuery(true);
		$qr1
		->update($db->quoteName('#__tours_categories'))
		->set($db->quoteName('ind_name').' = "'.$db->quote($this->name[$this->lang]).'"')
		->where($db->quoteName('ind_id').'='.((int)$this->id));

		//Query2 - delete translated data...
		$qr2=$db->getQuery(true);
		$qr2->delete($db->quoteName('#__tours_categories_lang'))->where($db->quoteName('indl_category').'='.((int)$this->id));

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

		jimport('brilliant.Tours.languages');
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
		$qr3='INSERT INTO #__tours_categories_lang (`indl_category`, `indl_lang`, `indl_name`) VALUES'.$qr3;
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
		$qr1='INSERT INTO #__tours_categories (`ind_name`) VALUES '.'('.$db->quote($this->name[$lang->getTag()]).')';	
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery('ROLLBACK');
			$db->query();
			return false;
			}
		$this->id=(int)$db->insertid();		

		jimport('brilliant.Tours.language');
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
		$qr2='INSERT INTO #__tours_categories_lang (`indl_category`, `indl_lang`, `indl_name`) VALUES '.$qr2;		
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
	} //end of BToursCategory
