<?php
defined('_JEXEC')or die( 'Restricted access' );

class xmap_com_regoffices{
	/**
	 *
	 */
	static function getTree($xmap, $parent, &$params){
		jimport('brilliant_regoffices.countries');
		$brc=BRegofficesCountries::getInstance();
		$list=$brc->items_get_all();
		foreach($list as $itm){
			$node = new stdClass();
			$name=$itm->getName();
			$metarobots=$itm->getlangvar('metarobots');
			if((!empty($name))&&($metarobots==0)){
				$url=$itm->getUrl(true);
				if(!empty($url)){
					$node->name=$name;
					$node->expandible = false;
					$node->link = $url;
					$xmap->printNode($node);
					}
				}
			}
		//
		jimport('brilliant_regoffices.cities');
		$brct=BRegofficesCities::getInstance();
		$list=$brct->items_get_all();
		foreach($list as $itm){
			$node = new stdClass();
			$name=$itm->getName();
			$metarobots=$itm->getlangvar('metarobots');
			if((!empty($name))&&($metarobots==0)){
				$url=$itm->getUrl(true);
				if(!empty($url)){
					$node->name=$name;
					$node->expandible = false;
					$node->link = $url;
					$xmap->printNode($node);
					}
				}
			}
		//
		return true;
		}
	}
