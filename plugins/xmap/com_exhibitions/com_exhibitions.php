<?php
defined('_JEXEC')or die( 'Restricted access' );

class xmap_com_exhibitions{
	static function getTree($xmap, $parent, &$params){
		jimport('brilliant.exhibitions.exhibitions');
		$exhibitions=BExhibitions::getInstance();
		$list=$exhibitions->exhibitions_get_all();
		foreach($list as $itm){
			$node = new stdclass();
			$name=$itm->getName();
			if(!empty($name)){
				$node->name = $itm->name;
        	        	$node->expandible = false;
        	        	$node->link = $itm->url(true);
				$xmap->printNode($node);
				}
			}
		return true;
		}
	}
?>
