<?php
//=================================================================
//
//
//=================================================================
class BExhibitions{
	protected static $instance=NULL;
	protected $exhibitions_cache;
	//=========================================================
	// Constructor
	//=========================================================
	public function __construct(){
		$this->exhibitions_cache=array();
		}
	//=========================================================
	// Returns the global Session object, only creating it
	// if it doesn't already exist.
	//=========================================================
	public static function getInstance(){
		if (!is_object(self::$instance))
			self::$instance=new BExhibitions();
		return self::$instance;
		}
	//=========================================================
	// Get the all exhibitions
	//=========================================================
	public function exhibitions_get_all(){
		$db=JFactory::getDBO();
		if(empty($db))return $cat;
		$db->setQuery('SELECT exb_id FROM #__exhibitions ORDER BY exb_start DESC');
		$idsq = $db->loadObjectList();

		$ids=array();
		foreach($idsq as $id)
			array_push($ids,(int)$id->exb_id);
		return $this->exhibitions_get($ids);
		}
	//=========================================================
	// Get the all exhibitions that starts/ends after today.
	//=========================================================
	public function exhibitions_get_actual(){
		$db=JFactory::getDBO();
		if(empty($db))return NULL;
		$db->setQuery('SELECT exb_id FROM #__exhibitions WHERE(exb_end>=NOW()) ORDER BY exb_start ASC');
		$idsq = $db->loadObjectList();

		$ids=array();
		foreach($idsq as $id)
			array_push($ids,(int)$id->exb_id);
		return $this->exhibitions_get($ids);
		}
	//=========================================================
	// Get the all exhibitions by filter
	//=========================================================
	public function exhibitions_get_filter($filter){
		$db=JFactory::getDBO();
		if(empty($db))return NULL;
		$wh='';
		$jn='';
		if($filter['city'])
			$wh.=(empty($wh)?'':'&&').'(exb_city='.(int)$filter['city'].')';
		if($filter['industry']){
			$wh.=(empty($wh)?'':'&&').'(exbi_industry='.(int)$filter['industry'].')';
			$jn.=' INNER JOIN #__exhibitions_industries on exbi_exhibition=exb_id';
			}
		if(!empty($filter['date_from']))
			$wh.=(empty($wh)?'':'&&').'(exb_end>="'.$filter['date_from']->format('Y-m-d').'")';
		if(!empty($filter['date_to']))
			$wh.=(empty($wh)?'':'&&').'(exb_start<="'.$filter['date_to']->format('Y-m-d').'")';
		$qr='SELECT exb_id FROM #__exhibitions'.
			$jn.
			(empty($wh)?'':' WHERE('.$wh.')').
			' ORDER BY exb_start ASC';
		//echo('qr='.$qr);
		//exb_end>=NOW()
		$db->setQuery($qr);
		$idsq = $db->loadObjectList();
		if(empty($idsq))
			return NULL;
		$ids=array();
		foreach($idsq as $id)
			array_push($ids,(int)$id->exb_id);
		//var_dump($ids);
		$list=$this->exhibitions_get($ids);

		//var_dump($list);
		return $list;
		}

	//=========================================================
	// Get the categories list...
	//=========================================================
	public function exhibitions_get($ids){
		$cat=array();
		//-------------------------------------------------
		//Trying to get categories from internal cache
		//-------------------------------------------------
		$ids_c=array(); //left (not founded) IDs as integer
		$ids_k=array(); //left (not founded) IDs as external cache key
		foreach($ids as $id)
			if(isset($this->exhibitions_cache[$id])){
				$cat[$id]=$this->exhibitions_cache[$id];
				}else{
				if($id>0){
					$cat[$id]=NULL;
					$ids_c[$id]=$id;
					$ids_k[$id]='exhibitions:exb:'.$id;
					}
				}
		if(empty($ids_c)){
			return $cat; //If we found all categories, return it
			}
		//-------------------------------------------------
		//Trying to get left categories from external cache
		//-------------------------------------------------
		jimport('brilliant.cache.general');
		if(BCache::isEnabled()){
			$cache=BCache::getInstance();
			$ids_m=array();
			$ids_q='';
			$cat_c=$cache->getMultiple($ids_k);
			foreach($ids_c as $id){
				$key='exhibitions:exb:'.$id;
				if((isset($cat_c[$key]))&&(!empty($cat_c[$key]))){
					$cat[$id]=new BExhibitionsExhibition();
					$cat[$id]->load($cat_c[$key]);
					$this->exhibitions_cache[$id]=$cat[$id];
					}else{
					array_push($ids_m,$id);
					$ids_q.=(empty($ids_q)?'':',').$id;
					}
				}
			if(empty($ids_m))
				return $cat;
			}else{
			$cache=NULL;
			$ids_m=$ids_c;
			}
		//-------------------------------------------------
		//Trying to get left categories from database
		//-------------------------------------------------
		jimport('brilliant.exhibitions.language');
		$l=BLanguages::getInstance();
		$languages=$l->languages_get_all();

		$db=JFactory::getDBO();
		if(empty($db)){
			return $cat;
			}
		$ids_q='';
		foreach($ids_m as $id){
			$ids_q.=(empty($ids_q)?'':',').$id;
			}
		$query = $db->getQuery(true);
		//Get exhibitions...
		$db->setQuery('SELECT * FROM #__exhibitions WHERE exb_id in ('.$ids_q.')');
		$exbs=$db->loadObjectList();
		//Get Exhibition translates...
		$db->setQuery('SELECT * FROM #__exhibitions_lang WHERE exbl_exhibition in ('.$ids_q.')');
		$exbl=$db->loadObjectList();
		//
		foreach($exbs as $exb){
			$exbq=new stdClass();
			$exbq->id=(int)$exb->exb_id;
			$exbq->introimg=$exb->exb_introimg;
			$exbq->fullimg=$exb->exb_fullimg;
			$exbq->city=$exb->exb_city;
			$exbq->start=$exb->exb_start;
			$exbq->end=$exb->exb_end;
			$exbq->created=$exb->exb_created;
			$exbq->modified=$exb->exb_modified;

			$exbq->name=array();
			$exbq->alias=array();
			$exbq->introtext=array();
			$exbq->fulltext=array();

			foreach($exbl as $el)
				if($el->exbl_exhibition == $exbq->id){
					$exbq->name[$el->exbl_lang]=$el->exbl_name;
					$exbq->alias[$el->exbl_lang]=$el->exbl_alias;
					$exbq->introtext[$el->exbl_lang]=$el->exbl_introtext;
					$exbq->fulltext[$el->exbl_lang]=$el->exbl_fulltext;
					$exbq->title[$el->exbl_lang]=$el->exbl_title;
					$exbq->metadesc[$el->exbl_lang]=$el->exbl_metadesc;
					$exbq->metakeyw[$el->exbl_lang]=$el->exbl_metakey;
					}
			//Set unsetted items to empty
			foreach($languages as $l){
				if(!isset($exbq->name[$l->lang_code]))
					$exbq->name[$l->lang_code]='';
				if(!isset($exbq->alias[$l->lang_code]))
					$exbq->alias[$l->lang_code]='';
				if(!isset($exbq->introtext[$l->lang_code]))
					$exbq->introtext[$l->lang_code]='';
				if(!isset($exbq->fulltext[$l->lang_code]))
					$exbq->fulltext[$l->lang_code]='';
				if(!isset($exbq->title[$l->lang_code]))
					$exbq->title[$l->lang_code]='';
				if(!isset($exbq->metadesc[$l->lang_code]))
					$exbq->metadesc[$l->lang_code]='';
				if(!isset($exbq->metakeyw[$l->lang_code]))
					$exbq->metakeyw[$l->lang_code]='';
				}
			$exbx=new BExhibitionsExhibition();
			$exbx->load($exbq);
			$cat[$exbx->id]=$exbx;
			$this->exhibitions_cache[$exbx->id]=$exbx;
			if($cache){
				$cache->set('exhibitions:exb:'.$id,$exbq,3600);//1 hour
				}
			}
		return $cat;
		}
	//================================================================================
	// Get single category
	//================================================================================
	public function exhibition_get($id){
		$list=$this->exhibitions_get(array($id));
		return isset($list[$id])?$list[$id]:NULL;
		}
	} //end of BExhibitions
//=================================================================
//
//
//=================================================================
class BExhibitionsExhibition{
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
	public function load($exb){
		$this->id=$exb->id;
		$this->name=$exb->name;
		$this->alias=$exb->alias;
		$this->date_start=new DateTime($exb->start);
		$this->date_end=new DateTime($exb->end);
		$this->date_created=new DateTime($exb->created);
		$this->date_modified=new DateTime($exb->modified);
		$this->introtext=$exb->introtext;
		$this->fulltext=$exb->fulltext;
		$this->introimg=$exb->introimg;
		$this->fullimg=$exb->fullimg;
		$this->title=$exb->title;
		$this->metadesc=$exb->metadesc;
		$this->metakeyw=$exb->metakeyw;
		$this->city=$exb->city;
		}//end of load
	//=========================================================
	// Get the name in language (current lang if empty)
	//=========================================================
	public function getName($lng=''){
		if(empty($lng))
			return $this->name[$this->lang];else
			return $this->name[$lng];
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
	public function getStartdate(){
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
		jimport('brilliant.exhibitions.cities');
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
	public function getIndustries(){
		jimport('brilliant.exhibitions.industries');
		$ind=BExhibitionsIndustries::getInstance();
		$list=$ind->industries_get_by_exhibition($this->id);
		return $list;
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
		$qr1->delete($db->quoteName('#__exhibitions_industries'))->where($db->quoteName('exbi_exhibition').'='.((int)$this->id));
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
			$qr2='INSERT INTO #__exhibitions_industries (`exbi_exhibition`, `exbi_industry`) VALUES'.$qr2;			
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
			return JRoute::_('index.php?option=com_exhibitions&view=exhibition&id='.$this->id,true,-1);else
			return JRoute::_('index.php?option=com_exhibitions&view=exhibition&id='.$this->id);
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
    	->insert($db->quoteName('#__exhibitions'))
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
		$qr2='INSERT INTO #__exhibitions_lang (`exbl_exhibition`,`exbl_lang`,`exbl_alias`,`exbl_name`,`exbl_introtext`,`exbl_fulltext`,`exbl_metakey`,`exbl_metadesc`) VALUES '.$qr2;

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
		$qr1->update($db->quoteName('#__exhibitions'))->set($fields)->where($db->quoteName('exb_id').'='.((int)$this->id));
		//Query2 - delete translated data...
		$qr2=$db->getQuery(true);
		$qr2->delete($db->quoteName('#__exhibitions_lang'))->where($db->quoteName('exbl_exhibition').'='.((int)$this->id));
		//Query3 - insert translated data...
		jimport('brilliant.exhibitions.language');
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
		$qr3='INSERT INTO #__exhibitions_lang (`exbl_exhibition`,`exbl_lang`,`exbl_alias`,`exbl_name`,`exbl_introtext`,`exbl_fulltext`,`exbl_metakey`,`exbl_metadesc`) VALUES '.$qr3;
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
	// Detect exhibition state (new or old) and call insert
	// or update query...
	//=========================================================
	public function savetodb(){
		if(empty($this->id)){
			return $this->insert();
			}else{
			return $this->update();
			}
		}
	} //end of BExhibitionsExhibition
