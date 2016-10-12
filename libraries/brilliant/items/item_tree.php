<?php
/**
 * Sets of functions and classes to work with tree item.
 *
 * @author Andrii Biriev
 *
 * @copyright Copyright © Brilliant IT corporation, www.it.brilliant.ua
 */
jimport('brilliant.items.item');

abstract class BItemsItemTree extends BItemsItem{
	protected $parentkeyname='parent';
	protected $leftkeyname='lft';
	protected $rightkeyname='rgt';
	protected $levelkeyname='level';

	//public $parentid=0;
	//public $oldparentid=0;
	/**
	 * Constructor - init fields...
	 */
	function __construct() {
		parent::__construct();
		$this->fieldAddRaw($this->parentkeyname,'item',array('class'=>get_class($this)));
		$this->fieldAddRaw($this->leftkeyname,'int');
		$this->fieldAddRaw($this->rightkeyname,'int');
		$this->fieldAddRaw($this->levelkeyname,'int');
		}
	/**
	 *
	 */
	public function getparentchain(){
		$collname=$this->collectionname;
		$bitems=$collname::GetInstance();
		$fchain=$bitems->items_filter(array('parentchain'=>$this->id));
		return $fchain;
		}
	/**
	 * Get children items by alias.
	 */
	public function children($lang='',$alias=''){
		$collname=$this->collectionname;
		$bitems=$collname::GetInstance();
		$children=$bitems->items_filter(array('parent'=>$this->id));
		if(empty($alias)){
			return $children;
			}
		foreach($children as $ch){
			$chalias=$ch->getalias($lang);
			if($chalias==$alias){
				return $ch;
				}
			}
		return NULL;
		}

	}