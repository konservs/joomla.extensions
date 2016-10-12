<?php
abstract class BItemsTree extends BItems{
	protected $leftkey='lft';
	protected $rightkey='rgt';
	protected $levelkey='level';
	protected $parentkey='parent';
	/**
	 *
	 */
	public function items_filter_sql($params,&$wh,&$jn){
		//
		$db=BFactory::getDBO();
		//Call parent method.
		parent::items_filter_sql($params,$wh,$jn);

		//Select categories only with some level.
		if(isset($params['level'])){
			$wh[]='(`'.$this->levelkey.'`='.(int)$params['level'].')';
			}
		//Select categories only with parentid=$params['parent'].
		if(isset($params['parent'])){
			$wh[]='(`'.$this->parentkey.'`='.(int)$params['parent'].')';
			}
		//Entire parents tree. 
		if(isset($params['parenttree'])){
			$itemid=(int)$params['parenttree'];
			$item=$this->item_get($itemid);
			if(empty($item)){
				return false;
				}
			$wh[]='(`'.$this->leftkey.'`>='.$item->lft.')';
			$wh[]='(`'.$this->rightkey.'`<='.$item->rgt.')';
			}
		//Entire parents tree, second version. 
		if((isset($params['parenttree_lft']))&&(isset($params['parenttree_rgt']))){
			$lft=$params['parenttree_lft'];
			$rgt=$params['parenttree_rgt'];
			if(($lft<1)||($rgt<1)||($lft>=$rgt)){
				return false;
				}
			$wh[]='(`'.$this->leftkey.'`>='.$lft.')';
			$wh[]='(`'.$this->rightkey.'`<='.$rgt.')';
			}
		//Entire parents chain. 
		if(isset($params['parentchain'])){
			$itemid=(int)$params['parentchain'];
			$item=$this->item_get($itemid);
			if(empty($item)){
				return false;
				}
			$wh[]='(`'.$this->leftkey.'`<='.$item->lft.')';
			$wh[]='(`'.$this->rightkey.'`>='.$item->rgt.')';
			}
		return true;
		}
	/**
	 * News categories tree cache hash.
	 *
	 */
	public function items_filter_hash($params){
		$db=BFactory::getDBO();
		$itemshash=parent::items_filter_hash($params);

		//Select categories only with some level
		if(isset($params['level'])){
			$itemshash.=':level='.$params['level'];
			}
		//Select categories only with parentid=$params['parent'].
		if(isset($params['parent'])){
			$itemshash.=':parent='.$params['parent'];
			}
		//Entire parents tree. 
		if(isset($params['parenttree'])){
			$itemid=(int)$params['parenttree'];
			$item=$this->item_get($itemid);
			if(empty($item)){
				return false;
				}
			$itemshash.=':parenttree-'.$item->lft.'-'.$item->rgt;
			}
		//Entire parents tree, second version. 
		if((isset($params['parenttree_lft']))&&(isset($params['parenttree_rgt']))){
			$lft=$params['parenttree_lft'];
			$rgt=$params['parenttree_rgt'];
			if(($lft<1)||($rgt<1)||($lft>=$rgt)){
				return false;
				}
			$itemshash.=':parenttree-'.$lft.'-'.$rgt;
			}
		//Entire parents chain. 
		if(isset($params['parentchain'])){
			$itemid=(int)$params['parentchain'];
			$item=$this->item_get($itemid);
			if(empty($item)){
				return false;
				}
			$itemshash.=':parentchain-'.$item->lft.'-'.$item->rgt;
			}
		//Entire parents chain, second version. 
		if((isset($params['parentchain_lft']))&&(isset($params['parentchain_rgt']))){
			$lft=$params['parentchain_lft'];
			$rgt=$params['parentchain_rgt'];
			if(($lft<1)||($rgt<1)||($lft>=$rgt)){
				return false;
				}
			$itemshash.=':parentchain-'.$lft.'-'.$rgt;
			}
		return $itemshash;
		}
	/**
	 *
	 */
	public function getitembyaliaschain($aliases,$lang=''){
		$hash='';
		foreach($aliases as $alias){
			$hash.=(empty($hash)?'':':').$alias;
			}
		$key=$this->tablename.':chain:'.$hash;
		//External cache... 

		//items tree.
		$item1=$this->item_get(1);
		if(empty($item1)){
			BDebug::error('[BItemsTree] getitembyaliaschain(): Could not get root items!');
			return NULL;
			}
		//Aliases
		foreach($aliases as $alias){
			$item1=$item1->children($lang,$alias);
			if(empty($item1)){
				BDebug::error('[BItemsTree]: getitembyaliaschain() Could not get news item children!');
				return NULL;
				}
			}
		return $item1;
		}

	}
