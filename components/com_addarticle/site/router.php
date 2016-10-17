<?php
defined('_JEXEC') or die('No direct access!');
jimport('joomla.application.categories');

function AddarticleBuildRoute(&$query){
	$segments	= array(); 
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	$params		= JComponentHelper::getParams('com_addarticle');
	$advanced	= $params->get('sef_advanced_link', 0);

	if(isset($query['view']))
		$view = $query['view'];else
		return $segments;

	if($view=='addarticle'){
		//Seek menu with addarticle page...
		$url='';
		$items=$menu->getItems();
		foreach($items as $itm)
			if(($itm->component=='com_addarticle')&&($itm->query['view']=='addarticle')){
				$Itemid=$itm->id;
				break;
				}
		if(empty($Itemid)){
			return $segments;
			}
		unset($query['view']);
		$query['Itemid']=$Itemid;
		}
	return $segments;
	}


function AddarticleParseRoute($segments){
	$vars = array();
	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_addarticle');
	$advanced = $params->get('sef_advanced_link', 0);
	// Count route segments
	$count = count($segments);
	if($count <= 0){
		return $vars;
		}

	return $vars;
	}
