<?php
//============================================================
// Abstract class for collection item
//
// Author: Andrii Biriev
// Author: Andrii Karepin
// Copyright © Brilliant IT corporation, www.it.brilliant.ua
//============================================================

abstract class BItemsItem{
	public $id=0; //Necessary field
	protected $tablename='';
	protected $collectionname='';
	protected $primarykey='id';
	protected $fields=array();
	/**
	 * Simple constructor.
	 */
	function __construct() {
		//Do nothing.
		}
	/**
	 *
	 */
	protected function fieldAddRaw($name,$type,$params=array()){
		if(empty($name)){
			return false;
			}
		$fldobj=(object)$params;
		$fldobj->name=$name;
		$fldobj->type=$type;
		$this->fields[$name]=$fldobj;
		}
	/**
	 *
	 */
	protected function updatecache(){
		}
	/**
	 *
	 */
	protected function fieldFromRaw($value,$type){
		switch($type){
			case 'int':
			case 'integer':
				return (int)$value;
			case 'float':
			case 'double':
				return (float)$value;
			case 'itm':
			case 'item':
				return (int)$value;
			case 'bool':
			case 'boolean':
				return (bool)$value;
			case 'str':
			case 'string':
				return $value;
			case 'enum':
				return $value;
			case 'dt':
				$obj=NULL;
				if(!empty($value)){
					//jimport('cms.datetime');
					$obj=new DateTime($value);
					}
				return $obj;
			case 'json':
				return json_encode($value);
			}
		die('[BItem] FieldFromRaw Unknown field type "'.$type.'"!');
		}
	/**
	 *
	 */
	public function load($obj){
		$this->{$this->primarykey}=(int)$obj[$this->primarykey];
		//Get languages list
		$languages = JLanguageHelper::getLanguages();
		//$languages=array('ru','en','ua');//BLang::langlist();

		//Process additional fields
		foreach($this->fields as $fld){
			//Multi-language field + general field
			if((isset($fld->multilang))&&($fld->multilang===2)){
				$this->{$fld->name}=$this->fieldFromRaw($obj[$fld->name],$fld->type);
				foreach($languages as $lng){
					$nn=$fld->name.'_'.$lng;
					$this->$nn=$this->fieldFromRaw($obj[$nn],$fld->type);
					}
				}
			//Multi-language field in external table
			elseif((isset($fld->multilang))&&($fld->multilang===3)){
				foreach($languages as $lng){
					//.'_'.$lng
					$nn=$fld->name.'_'.$lng->lang_code;
					$this->$nn=$this->fieldFromRaw($obj['_translations'][$lng->lang_code][$fld->name],$fld->type);
					}
				}

			//Simple multi-language
			elseif((isset($fld->multilang))&&($fld->multilang)){
				foreach($languages as $lng){
					$nn=$fld->name.'_'.$lng;
					$this->$nn=$this->fieldFromRaw($obj[$nn],$fld->type);
					}
				}
			//Single language
			else{
				$this->{$fld->name}=$this->fieldFromRaw($obj[$fld->name],$fld->type);
				}
			}
		//var_dump($this); die();
		return true;
		}
	/**
	 *
	 */
	protected function loaditems(&$obj,$list){
		$arr=explode(',',$list);
		if(!is_array($arr)){
			return false;
			}
		foreach($arr as $itm){
			$this->loaditem($obj,trim($itm));
			}
		}
	//====================================================
	// Load item by type
	//====================================================
	protected function loaditem(&$obj,$item){
		switch($item){
			case 'id':$this->id=(int)$obj['id']; return true;
			case 'published': $this->published=$obj['published']; return true;
			case 'name':
				$this->name_ru=$obj['name_ru'];
				$this->name_ua=$obj['name_ua'];
				return true;
			case 'alias':
				$this->alias_ru=$obj['alias_ru'];
				$this->alias_ua=$obj['alias_ua'];
				return true;
			case 'created': $this->created=new DateTime($obj['created']); return true;
			case 'modified': $this->modified=new DateTime($obj['modified']); return true;
			}
		return false;
		}
	/**
	 *
	 */
	protected function detectlang($lang){
		if(empty($lang)){
			bimport('cms.language');
			$lang=BLang::$langcode;
			}
		return $lang;
		}
	/**
	 *
	 */
	public function getlangvar($varname,$lang=''){
		if(empty($lang)){
			$langx=JFactory::getLanguage();
			$lang=$langx->getTag();
			}
		$name=$varname.'_'.$lang;
		//var_dump($lang); die('b');
		$result=isset($this->$name)?$this->$name:'';
		return $result;
		}
	/**
	 *
	 */
	public function setlangvar($varname,$value,$lang=''){
		if(empty($lang)){
			$langx=JFactory::getLanguage();
			$lang=$langx->getTag();
			}
		$name=$varname.'_'.$lang;
		//var_dump($lang); die('b');
		$this->$name=$value;
		return true;
		}
	/**
	 * Get RAW field value from DB.
	 */
	protected function fieldToSQL($name,$lang=''){
		$fldname=$name;
		if($lang!=''){
			$fldname.='_'.$lang;
			}
		$db=JFactory::GetDBO();
		if(empty($db)){
			return false;
			}
		if(!isset($this->fields[$name])){
			return '';
			}
		$type=$this->fields[$name]->type;
		switch($type){
			case 'int':
			case 'integer':
				return (int)$this->{$fldname};
			case 'float':
			case 'double':
				return (float)$this->{$fldname};
			case 'itm':
			case 'item':
				$itemid=(int)$this->{$fldname};
				return empty($itemid)?'NULL':$itemid;
			case 'bool':
			case 'boolean':
				return (int)$this->{$fldname};
			case 'str':
			case 'string':
				return $db->quote($this->{$fldname});
			case 'enum':
				//???
				return $db->quote($this->{$fldname});
			case 'dt':
				$obj=$this->{$fldname};
				if(!is_object($obj)){
					return '""';
					}
				return '"'.$obj->format('Y-m-d h:i:s').'"';
			case 'image':
				$obj=$this->{$fldname};
				if(!is_object($obj)){
					return NULL;
					}
				return $db->quote($obj->url);
			case 'json':
				$obj=$this->{$fldname};
				if(is_object($obj)){
					return $db->quote(json_encode($obj));
					}
				elseif(is_array($obj)){
					return $db->quote(json_encode($obj));
					}
				return '""';
				}
		die('[BItem] fieldToSQL() Unknown field type "'.$type.'"!');
		}
	/**
	 *
	 */
	protected function getfieldsvalues(&$qr_fields,&$qr_values){
		$qr_fields=array();
		$qr_values=array();
		//Get languages list
		$languages=JLanguageHelper::getLanguages();
		//Process additional fields

		foreach($this->fields as $fld){
			//Multi-language field + general field
			if($fld->multilang===2){
				$qr_fields[]='`'.$fld->name.'`';
				$qr_values[]=$this->fieldToSQL($fld->name);
				foreach($languages as $lng){
					$qr_fields[]='`'.$fld->name.'_'.$lng.'`';
					$qr_values[]=$this->fieldToSQL($fld->name,$lng->lang_code);
					}
				}
			//
			if($fld->multilang===3){
				//Skip.
				//
				//$qr_fields[]='`'.$fld->name.'`';
				//$qr_values[]=$this->fieldToSQL($fld->name);
				//foreach($languages as $lng){
				//	$qr_fields[]='`'.$fld->name.'_'.$lng->lang_code.'`';
				//	$qr_values[]=$this->fieldToSQL($fld->name,$lng->lang_code);
				//	}
				}

			//Simple multi-language
			elseif($fld->multilang){
				foreach($languages as $lng){
					$qr_fields[]='`'.$fld->name.'_'.$lng.'`';
					$qr_values[]=$this->fieldToSQL($fld->name,$lng->lang_code);
					}
				}
			//Single language
			else{
				$qr_fields[]='`'.$fld->name.'`';
				$qr_values[]=$this->fieldToSQL($fld->name);
				}
			}
		//var_dump($qr_fields); die();
		return true;
		}
	/**
	 *
	 */
	protected function dbinsertquery(){
		$qr_fields=array();
		$qr_values=array();
		$this->getfieldsvalues($qr_fields,$qr_values);
		$qr='INSERT INTO `'.$this->tablename.'` ('.implode(',',$qr_fields).') VALUES ('.implode(',',$qr_values).')';
		return $qr;
		}
	/**
	 *
	 */
	protected function dbupdatequery(){
		$qr_fields=array();
		$qr_values=array();
		$this->getfieldsvalues($qr_fields,$qr_values);
		$qr='UPDATE `'.$this->tablename.'` SET ';
		$first=true;
		foreach($qr_fields as $i=>$field){
			$qr.=($first?'':', ').$field.'='.$qr_values[$i];
			$first=false;
			}
		$qr.=' WHERE `'.$this->primarykey.'`='.$this->{$this->primarykey};
		//var_dump($qr); die();
		return $qr;
		}
	//====================================================
	// Run Insert query in the database & reload cache
	// 
	// returns true if OK and false if not
	//====================================================
	public function dbinsert(){
		BLog::addtolog('[Items.Item]: Inserting data...');
		$db=JFactory::GetDBO();
		if(empty($db)){
			return false;
			}
		//Forming query...
		$this->modified=new DateTime();
		$this->created=new DateTime();
		$qr=$this->dbinsertquery();
		//Running query...
		$q=$db->query($qr);
		if(empty($q)){
			return false;
			}
		$this->id=$db->insert_id();
		//Updating cache...
		$this->updatecache();
		//Return result
		return true;
		}
	//====================================================
	// Run Update query in the database & reload cache
	// 
	// returns true if OK and false if not
	//====================================================
	public function dbupdate(){
		if(empty($this->id)){
			return false;
			}
		$db=JFactory::GetDBO();
		if(empty($db)){
			return false;
			}
		//
		$this->modified=new DateTime();
		//Get query
		$qr=$this->dbupdatequery();
		//Running query...
		$q=$db->setQuery($qr);
		if(empty($q)){
			return false;
			}
		if(!$db->query()){
			return false;
			}
		//Updating cache...
		$this->updatecache();
		//Return result
		return true;
		}
	//====================================================
	// Check is and run insert or update query, reload
	// cache.
	//====================================================
	public function savetodb(){
		if(empty($this->id)){
			return $this->dbinsert();
			}else{
			return $this->dbupdate();
			}
		}
	}
