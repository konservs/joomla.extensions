<?php
//=================================================================
//
//
//=================================================================
class BTours{
	protected static $instance=NULL;
	protected $tours_cache;
	//=========================================================
	// Constructor
	//=========================================================
	public function __construct(){
		$this->tours_cache=array();
		}
	//=========================================================
	// Returns the global Session object, only creating it
	// if it doesn't already exist.
	//=========================================================
	public static function getInstance(){
		if (!is_object(self::$instance))
			self::$instance=new BTours();
		return self::$instance;
		}
	//=========================================================
	// Get the all tours
	//=========================================================
	public function tours_get_all(){
		$db=JFactory::getDBO();
		if(empty($db))return $cat;
		$db->setQuery('SELECT id FROM #__tours ORDER BY dt_start DESC');
		$idsq = $db->loadObjectList();

		$ids=array();
		foreach($idsq as $id){
			array_push($ids,(int)$id->id);
			}
		return $this->tours_get($ids);
		}
	//=========================================================
	// Get the all tours that starts/ends after today.
	//=========================================================
	public function tours_get_actual(){
		$db=JFactory::getDBO();
		if(empty($db))return NULL;
		$db->setQuery('SELECT exb_id FROM #__tours WHERE(exb_end>=NOW()) ORDER BY exb_start ASC');
		$idsq = $db->loadObjectList();

		$ids=array();
		foreach($idsq as $id)
			array_push($ids,(int)$id->exb_id);
		return $this->tours_get($ids);
		}
	//=========================================================
	// Get the all tours by filter
	//=========================================================
	public function tours_get_filter($filter){
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		$wh='';
		$jn='';
		if((isset($filter['city']))&&($filter['city'])){
			$wh.=(empty($wh)?'':'&&').'(exb_city='.(int)$filter['city'].')';
			}
		$qr='SELECT id FROM #__tours'.
			$jn.
			(empty($wh)?'':' WHERE('.$wh.')').
			' ORDER BY dt_start ASC';
		$db->setQuery($qr);
		$idsq = $db->loadObjectList();
		if(empty($idsq)){
			return NULL;
			}
		$ids=array();
		foreach($idsq as $id){
			$id=(int)$id->id;
			$ids[$id]=$id;
			}
		$list=$this->tours_get($ids);
		return $list;
		}
	//=========================================================
	// Get the categories list...
	//=========================================================
	public function tours_get($ids){
		$ret=array();
		$db=JFactory::getDBO();
		if(empty($db)){
			return $ret;
			}
		$ids_q='';
		foreach($ids as $id){
			$ids_q.=(empty($ids_q)?'':',').$id;
			}
		$query = $db->getQuery(true);
		$db->setQuery('SELECT * FROM #__tours WHERE id in ('.$ids_q.')');
		$tours=$db->loadObjectList();
		//
		foreach($tours as $tour){
			$id=(int)$tour->id;
			$tourx=new BToursTour();
			$tourx->load($tour);
			$this->tours_cache[$id]=$tourx;
			$ret[$id]=$tourx;
			}
		return $ret;
		}
	//================================================================================
	// Get single category
	//================================================================================
	public function tour_get($id){
		$list=$this->tours_get(array($id));
		return isset($list[$id])?$list[$id]:NULL;
		}
	} //end of BTours
//=================================================================
//
//
//=================================================================
class BToursTour{
	private $lang;
	public $id;
	public $name;
	public $start;
	private $date_start;
	private $date_end;
	private $date_created;
	private $date_modified;
	private $introtext;
	private $fulltext;
	private $metadesc;
	private $metakeyw;
	private $title;
	//=========================================================
	// Constructor
	//=========================================================
	public function __construct(){
		$lang = JFactory::getLanguage();
		$this->lang=$lang->getTag();
		}
	//=========================================================
	// Constructor
	//=========================================================
	public function load($tour){
		$this->id=(int)$tour->id;
		$this->name=$tour->name;
		$this->alias=$tour->alias;
		$this->date_start=new DateTime($tour->dt_start);
		$this->city=$tour->city;
		}//end of load
	//=========================================================
	// Get the name in language (current lang if empty)
	//=========================================================
	public function getName($lng=''){
		return $this->name;
		}
	//=========================================================
	// Set the name in language (current lang if empty)
	//=========================================================
	public function setName($val,$lng=''){		
		if(empty($lng)){
			$this->name[$this->lang]=$val;
			}else{
			$this->name[$lng]=$val;
			}
		}
	//=========================================================
	// Get HTML title in language (current lang if empty)
	//=========================================================
	public function getTitle($lng=''){
		if(empty($lng))
			$title=$this->title[$this->lang];else
			$title=$this->title[$lng];
		if(empty($title))
			$title=$this->getName($lng);
		return $title;
		}
	//=========================================================
	// Set HTML title in language (current lang if empty)
	//=========================================================
	public function setTitle($val,$lng=''){
        	if(empty($lng)){
			$this->title[$this->lang]=$val;
			}else{
			$this->title[$lng]=$val;
			}           
		}
	//=========================================================
	// Get the alias in language (current lang if empty)
	//=========================================================
	public function getAlias($lng=''){
		if(empty($lng))
			$alias=$this->alias[$this->lang];else
			$alias=$this->alias[$lng];
		return $alias;
		}
	//=========================================================
	// Set the alias in language (current lang if empty)
	//=========================================================
	public function setAlias($val,$lng=''){
		if(empty($lng)){
			$this->alias[$this->lang]=$val;
			}else{
			$this->alias[$lng]=$val;
			}
		}
	//=========================================================
	//
	//=========================================================
	public function getIntrotext($lang=''){
		if(empty($lang)){
			return $this->introtext[$this->lang];
			}else{
			return $this->introtext[$lang];
			}
		}
	//=========================================================
	//
	//=========================================================
	public function setIntrotext($val,$lng=''){
		if(empty($lng)){
			$this->introtext[$this->lang]=$val;
			}else{
			$this->introtext[$lng]=$val;	
			}
		}        
	//=========================================================
	//
	//=========================================================
	public function getFulltext($lang=''){
		if(empty($lang)){
			return $this->fulltext[$this->lang];
			}else{
			return $this->fulltext[$lang];
			}
		}
    //=========================================================
    //
    //=========================================================
    public function setFulltext($val,$lang=''){
    	if(empty($lang)){
        	$this->fulltext[$this->lang]=$val;
    	  	}else{
    	  	$this->fulltext[$lang]=$val;		
    	  	}
        }        
	//=========================================================
	//
	//=========================================================
	public function getMetaDesc($lang=''){
		if(empty($lang)){
			return $this->metadesc[$this->lang];
			}else{
			return $this->metadesc[$lang];	
			}
		}
    //=========================================================
    //
    //=========================================================
    public function setMetaDesc($val,$lang=''){
    	if(empty($lang)){
        	$this->metadesc[$this->lang]=$val;
    	   	}else{
    	   	$this->metadesc[$lang]=$val;	
    	   	}
        }
	//=========================================================
	//
	//=========================================================
	public function getMetaKeyw($lang=''){
		if(empty($lang)){
			return $this->metakeyw[$this->lang];
			}else{
			return $this->metakeyw[$lang];	
			}
		}
    //=========================================================
    //
    //=========================================================
    public function setMetaKeyw($val,$lang=''){
    	if(empty($lang)){
        	$this->metakeyw[$this->lang]=$val;
    		}else{
    		$this->metakeyw[$lang]=$val;	
    		}
        }
	//=========================================================
	//
	//=========================================================
	public function getIntroimg(){
		return $this->introimg;
		}
    //=========================================================
    //
    //=========================================================
    public function setIntroimg($val){
        $this->introimg=$val;
        }
	//=========================================================
	//
	//=========================================================
	public function getFullimg(){
		return $this->fullimg;
		}
	//=========================================================
	//
	//=========================================================
	public function setFullimg($val){
		$this->fullimg=$val;
        	}
	//=========================================================
	//
	//=========================================================
	public function getStartDate(){
		return $this->date_start;
		}
	//=========================================================
	//
	//=========================================================
	public function setStartdate($val){
		if(is_string($val)){
			$this->date_start=new DateTime($val);
			}else{
			$this->date_start=$val;
			}
		return true;
		}
	//=========================================================
	//
	//=========================================================
	public function getEnddate(){
		return $this->date_end;
		}
	//=========================================================
	//
	//=========================================================
	public function setEnddate($val){
		//if(is_string($val)){
		//	$this->date_end=new DateTime($val);
		//	}else{
			$this->date_end=$val;
		//	}
		return true;
		}
	//=========================================================
	//
	//=========================================================
	public function getCreated(){
		return $this->date_created;
		}
	//=========================================================
	//
	//=========================================================
	public function setCreated($val){
		$this->date_created=$val;
		}
	//=========================================================
	//
	//=========================================================
	public function getModified(){
		return $this->date_modified;
		}
	//=========================================================
	//
	//=========================================================
	public function setModified($val){
		$this->date_modified=$val;
		}
	//=========================================================
	//
	//=========================================================
	public function getPrettydate(){
		jimport('brilliant.datetime');
		return brill_datediapason($this->getStartdate(),$this->getEnddate());
		}

	//=========================================================
	//
	//=========================================================
	public function getStartEndDate(){
		jimport('brilliant.datetime');
		return brill_date($this->getStartdate(),$this->getEnddate());
		}
	//=========================================================
	//
	//=========================================================
	public function getCity(){
		jimport('brilliant.tours.cities');
		$cities=BCities::getInstance();
		$city=$cities->city_get($this->city);
		return $city;
		}
	//=========================================================
	//
	//=========================================================
	public function setCity($city){	
		$this->city=$city;		
		}
	//=========================================================
	//
	//=========================================================
	public function getCategories(){
		//jimport('brilliant.tours.categories');
		//$ind=BToursCategories::getInstance();
		//$list=$ind->categories_get_by_tour($this->id);
		//return $list;
		return array();
		}
	//=========================================================
	//
	//=========================================================
	public function updateIndustries($inds){
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		$qr1=$db->getQuery(true);
		$qr1->delete($db->quoteName('#__tours_industries'))->where($db->quoteName('exbi_tour').'='.((int)$this->id));
		//var_dump($qr1);die();
		$qr2='';
		foreach($inds as $ind){
			$qr2.=$qr2==''?'(':', (';
			$qr2.=((int)$this->id).', ';
			$qr2.=$ind;
			$qr2.=')';
			}		
		//var_dump($qr2);die();
		//Start transaction
		$db->setQuery("START TRANSACTION");
		if(!$db->query()){
			return false;
			}
		//Delete industries from DB;
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery("ROLLBACK");
			$db->query();
			return false;
			}
		//Add industries to DB;
		if(!empty($qr2)){
			$qr2='INSERT INTO #__tours_industries (`exbi_tour`, `exbi_industry`) VALUES'.$qr2;			
			$db->setQuery($qr2);
			if(!$db->query()){
				$db->setQuery("ROLLBACK");
				$db->query();
				return false;
				}
			}
		$db->setQuery("COMMIT");
		if(!$db->query()){
			return false;
			}
		return true;
		}
	//=========================================================
	//
	//=========================================================
	public function url($absolute=false){
		if($absolute)
			return JRoute::_('index.php?option=com_tours&view=tour&id='.$this->id,true,-1);else
			return JRoute::_('index.php?option=com_tours&view=tour&id='.$this->id);
		}
	//=========================================================
	// Insert record into database.
	//=========================================================
	public function insert(){
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		//var_dump($this); die('Saving....');
		//Insert columns.
		$columns = array('exb_city', 'exb_start', 'exb_end', 'exb_introimg', 'exb_fullimg', 'exb_created', 'exb_modified');
		//Insert values.
		$now=date('Y-m-d H:i:s');
		$values = array(
			(int)$this->city, 
			$db->quote($this->date_start->format('Y-m-d')), 
			$db->quote($this->date_end), 
			$db->quote($this->introimg),
			$db->quote($this->fullimg),
			$db->quote($now),
			$db->quote($now)
			);
		//var_dump($values);die();
		//Prepare the insert query.
		//Create a new query object.
		$qr1=$db->getQuery(true);
		$qr1
    	->insert($db->quoteName('#__tours'))
	    ->columns($db->quoteName($columns))
	    ->values(implode(',', $values));
	   	//die($qr2);
	   	$db->setQuery("START TRANSACTION");
		if(!$db->query()){
			return false;
			}
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery("ROLLBACK");
			$db->query();
			return false;
			}
		//
		$exbid=(int)$db->insertid();
		$l=BLanguages::getInstance();
		$languages=$l->languages_get_all();
		$qr2='';
		foreach($languages as $l){
			$qr2.=empty($qr2)?'(':', (';
			$qr2.=$exbid.', ';
			$qr2.=$db->quote($l->lang_code).', ';
			$qr2.=$db->quote(empty($this->alias[$l->lang_code])?'':$this->alias[$l->lang_code]).', ';
			$qr2.=$db->quote(empty($this->name[$l->lang_code])?'':$this->name[$l->lang_code]).', ';
			$qr2.=$db->quote(empty($this->introtext[$l->lang_code])?'':$this->introtext[$l->lang_code]).', ';
			$qr2.=$db->quote(empty($this->fulltext[$l->lang_code])?'':$this->fulltext[$l->lang_code]).', ';
			$qr2.=$db->quote(empty($this->metakeyw[$l->lang_code])?'':$this->metakeyw[$l->lang_code]).', ';
			$qr2.=$db->quote(empty($this->metadesc[$l->lang_code])?'':$this->metadesc[$l->lang_code]);			
			$qr2.=')';
			}
		$qr2='INSERT INTO #__tours_lang (`exbl_tour`,`exbl_lang`,`exbl_alias`,`exbl_name`,`exbl_introtext`,`exbl_fulltext`,`exbl_metakey`,`exbl_metadesc`) VALUES '.$qr2;

		$db->setQuery($qr2);
		if(!$db->query()){
			$db->setQuery("ROLLBACK");
			$db->query();			
			return false;
			}
		$db->setQuery("COMMIT");
		if(!$db->query()){
			return false;
			}
		$this->id=$exbid;
		return true;
		}
	//=========================================================
	// Update record in the database
	//=========================================================
	public function update(){		
		$db=JFactory::getDBO();
		if(empty($db)){
			return false;
			}
		//Query1 - update general data...
		$qr1=$db->getQuery(true);
		$fields=array(		   
			$db->quoteName('exb_city').    '='.((int)$this->city),
			$db->quoteName('exb_start').   '= "'.$this->date_start->format('Y-m-d').'"',
			$db->quoteName('exb_end').     '='.$db->quote($this->date_end),
			$db->quoteName('exb_fullimg'). '='.$db->quote($this->fullimg),
			$db->quoteName('exb_introimg').'='.$db->quote($this->introimg)
			);
		$qr1->update($db->quoteName('#__tours'))->set($fields)->where($db->quoteName('exb_id').'='.((int)$this->id));
		//Query2 - delete translated data...
		$qr2=$db->getQuery(true);
		$qr2->delete($db->quoteName('#__tours_lang'))->where($db->quoteName('exbl_tour').'='.((int)$this->id));
		//Query3 - insert translated data...
		jimport('brilliant.tours.language');
		$l=BLanguages::getInstance();
		$languages=$l->languages_get_all();
		$qr3='';
		foreach($languages as $l){
			$qr3.=empty($qr3)?'(':', (';
			$qr3.=((int)$this->id).', ';
			$qr3.=$db->quote($l->lang_code).', ';
			$qr3.=$db->quote(empty($this->alias[$l->lang_code])?'':$this->alias[$l->lang_code]).', ';
			$qr3.=$db->quote(empty($this->name[$l->lang_code])?'':$this->name[$l->lang_code]).', ';
			$qr3.=$db->quote(empty($this->introtext[$l->lang_code])?'':$this->introtext[$l->lang_code]).', ';
			$qr3.=$db->quote(empty($this->fulltext[$l->lang_code])?'':$this->fulltext[$l->lang_code]).', ';
			$qr3.=$db->quote(empty($this->metakeyw[$l->lang_code])?'':$this->metakeyw[$l->lang_code]).', ';
			$qr3.=$db->quote(empty($this->metadesc[$l->lang_code])?'':$this->metadesc[$l->lang_code]);			
			$qr3.=')';
			}
		$qr3='INSERT INTO #__tours_lang (`exbl_tour`,`exbl_lang`,`exbl_alias`,`exbl_name`,`exbl_introtext`,`exbl_fulltext`,`exbl_metakey`,`exbl_metadesc`) VALUES '.$qr3;
		//Starting transaction
		$db->setQuery("START TRANSACTION");
		if(!$db->query()){
			return false;
			}
		$db->setQuery($qr1);
		if(!$db->query()){
			$db->setQuery("ROLLBACK");
			$db->query();
			return false;
			}
		$db->setQuery($qr2);
		if(!$db->query()){
			$db->setQuery("ROLLBACK");
			$db->query();
			return false;
			}
		$db->setQuery($qr3);
		if(!$db->query()){
			//echo('CANT SAVE TO DB QUERY3!');die();
			$db->setQuery("ROLLBACK");
			$db->query();
			return false;
			}
		$db->setQuery("COMMIT");
		if(!$db->query()){
			return false;
			}
		return true;
		}
	//=========================================================
	// Detect tour state (new or old) and call insert
	// or update query...
	//=========================================================
	public function savetodb(){
		if(empty($this->id)){
			return $this->insert();
			}else{
			return $this->update();
			}
		}
	} //end of BToursTour
