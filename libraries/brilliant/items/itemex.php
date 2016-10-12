<?php
//============================================================
// Abstract class for collection item
//
// Author: Andrii Biriev
// Author: Andrii Karepin
// Copyright © Brilliant IT corporation, www.it.brilliant.ua
//============================================================
if(DEBUG_MODE){
	bimport('debug.general');
	}
//============================================================
//
//============================================================
abstract class BItemsItemEx extends BItemsItem{
	protected $names_ru=array();
	protected $names_ua=array();
	protected $h1_ru='';
	protected $h1_ua='';
	protected $description_ru='';
	protected $description_ua='';
	protected $title_ru='';
	protected $title_ua='';
	protected $metadesc_ru='';
	protected $metadesc_ua='';
	protected $metakeyw_ru='';
	protected $metakeyw_ua='';
	//====================================================
	// Load some items (h1, description, title, etc.)
	//====================================================
	protected function loaditem(&$obj,$item){
		switch($item){
			case 'h1':$this->h1_ru=$obj['h1_ru']; $this->h1_ua=$obj['h1_ua']; return true;
			case 'description': $this->description_ru=$obj['description_ru']; $this->description_ua=$obj['description_ua']; return true;
			case 'title':$this->title_ru=$obj['title_ru']; $this->title_ua=$obj['title_ua']; return true;
			case 'metadesc':$this->metadesc_ru=$obj['metadesc_ru']; $this->metadesc_ua=$obj['metadesc_ua']; return true;
			case 'metakeyw':$this->metakeyw_ru=$obj['metakeyw_ru']; $this->metakeyw_ua=$obj['metakeyw_ua']; return true;
			case 'names':$this->names_ru=$this->decodenames($obj['names_ru']); $this->names_ua=$this->decodenames($obj['names_ua']); return true;
			}
		return parent::loaditem($obj, $item);
		}
	//====================================================
	// Get the H1 header of the region SEO page.
	// Detect language, if necessary.
	//====================================================
	public function geth1($lang=''){
		if($this->detectlang($lang)=='ru'){
			$res=$this->h1_ru;
			}else{
			$res=$this->h1_ua;
			}
		//if empty h1 - 
		if(empty($res)){
			$res=$this->getname($lang);
			}
		return $res;
		}
	//====================================================
	// Check & set the H1 header
	//
	// returns false wnen the H1 text is wrong and true,
	// when H1 text is good
	//====================================================
	public function seth1($value,$lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			$this->h1_ru=$value;
			}else{
			$this->h1_ua=$value;
			}
		return true;
		}
	//====================================================
	// Get the title of the region SEO page.
	// Detect language, if necessary.
	//====================================================
	public function gettitle($lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			return $this->title_ru;
			}else{
			return $this->title_ua;
			}
		}
	//====================================================
	// Check & set the HTML title
	//
	// returns false wnen the title is wrong and
	// true, when title is good
	//====================================================
	public function settitle($value,$lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			$this->title_ru=$value;
			}else{
			$this->title_ua=$value;
			}
		return true;
		}
	//====================================================
	// Get the description (article) of the region SEO
	// page. Detect language, if necessary.
	//====================================================
	public function getdescription($lang=''){
		$lang=$this->detectlang($lang);
		if ($lang=='ru'){
			return $this->description_ru;
			}else{
			return $this->description_ua;
			}
		}
	//====================================================
	// Check & set the description
	//
	// returns false wnen the description is wrong and
	// true, when description is good
	//====================================================
	public function setdescription($value,$lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			$this->description_ru=$value;
			}else{
			$this->description_ua=$value;
			}
		return true;
		}
	//====================================================
	// Get the META description tag value of the region
	// SEO page. Detect language, if necessary.
	//====================================================
	public function getmetadesc($lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			return $this->metadesc_ru;
			}else{
			return $this->metadesc_ua;
			}
		}
	//====================================================
	//
	//====================================================
	public function setmetadesc($value,$lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			$this->metadesc_ru=$value;
			}else{
			$this->metadesc_ua=$value;
			}
		return true;
		}
	//====================================================
	//
	//====================================================
	public function getmetakeyw($lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			return $this->metakeyw_ru;
			}else{
			return $this->metakeyw_ua;
			}
		}
	//====================================================
	//
	//====================================================
	public function setmetakeyw($value,$lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			$this->metakeyw_ru=$value;
			}else{
			$this->metakeyw_ua=$value;
			}
		return true;
		}
	//====================================================
	// Decode JSON names into array. Do some checks
	//====================================================
	public function decodenames($json){
		$names=(array)json_decode($json);
		$result=array();
		$result['genitive']=isset($names['genitive'])?$names['genitive']:'';
		$result['dative']=isset($names['dative'])?$names['dative']:'';
		$result['accusative']=isset($names['accusative'])?$names['accusative']:'';
		$result['ablative']=isset($names['ablative'])?$names['ablative']:'';
		$result['prepositional']=isset($names['prepositional'])?$names['prepositional']:'';
		return $result;
		}
	//====================================================
	//
	//====================================================
	public function getnames($case,$lang=''){
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			return isset($this->names_ru[$case])?$this->names_ru[$case]:'';
			}else{
			return isset($this->names_ua[$case])?$this->names_ua[$case]:'';
			}
		}
	//====================================================
	//
	//====================================================
	public function setnames($case,$value,$lang=''){
		$lang=$this->detectlang($lang);
		if(($case!='genitive')&&($case!='dative')&&
		   ($case!='accusative')&&($case!='ablative')&&
		   ($case!='prepositional')){
			return false;
			}
		if($lang=='ru'){
			$this->names_ru[$case]=$value;
			}else{
			$this->names_ua[$case]=$value;
			}
		return true;
		}
	//====================================================
	//
	//====================================================
	public function setnames_raw($values,$lang=''){
		if(!is_array($values)){
			return false;
			}
		//Checking values...
		$arr=array();
		$arr['genitive']=isset($values['genitive'])?$values['genitive']:'';
		$arr['dative']=isset($values['dative'])?$values['dative']:'';
		$arr['accusative']=isset($values['accusative'])?$values['accusative']:'';
		$arr['ablative']=isset($values['ablative'])?$values['ablative']:'';
		$arr['prepositional']=isset($values['prepositional'])?$values['prepositional']:'';
		//Detecting language...
		$lang=$this->detectlang($lang);
		if($lang=='ru'){
			$this->names_ru=$arr;
			}else{
			$this->names_ua=$arr;
			}
		//Returning result
		return true;
		}
	}