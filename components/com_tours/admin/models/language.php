<?php
//=================================================================
//
//
//=================================================================
class BLanguages{
	protected static $instance=NULL;
	protected $languages_cache;
	//=========================================================
	// Constructor
	//=========================================================
	public function __construct(){
		}
	//=========================================================
	// Returns the global Session object, only creating it
	// if it doesn't already exist.
	//=========================================================
	public static function getInstance(){
		if (!is_object(self::$instance))
			self::$instance=new BLanguages();
		return self::$instance;
		}
	//=========================================================
	// Get the all exhibitions
	//=========================================================
	public function languages_get_all(){
		if(isset($this->languages_cache))
			return $this->languages_cache;

		$db=&JFactory::getDBO();
		if(empty($db))return array();
		//Get languages...
		//id lang_id
		$db->setQuery('SELECT lang_code, sef, title_native FROM #__languages ORDER BY sef ASC');
		$this->languages_cache=$db->loadObjectList();
		//var_dump($this->languages_cache); die();
		return $this->languages_cache;
		}
	} //end of BLanguages
