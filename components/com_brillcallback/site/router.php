<?php
defined('_JEXEC') or die;
jimport('joomla.application.categories');

function BrillcallbackBuildRoute(&$query){
	$segments	= array(); 
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	$params		= JComponentHelper::getParams('com_brillcallback');
	$advanced	= $params->get('sef_advanced_link', 0);

	if(isset($query['view']))
		$view = $query['view'];else
		return $segments;

	//if($view=='sendform'){
	//	unset($query['view']);
	//	}
	if($view=='sendform'){
		//Seek menu with exhibitions archive...
		$url='';
		$items=$menu->getItems();
		foreach($items as $itm)
			if(($itm->component=='com_brillcallback')&&($itm->query['view']=='sendform')){
				$Itemid=$itm->id;
				//var_dump($itm->params);
				break;
				}
		if(empty($Itemid))return $segments;
		unset($query['view']);
		$query['Itemid']=$Itemid;
		}


	return $segments;
	}


function ExhibitionsParseRoute($segments){
	$vars = array();
	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_brillcallback');
	$advanced = $params->get('sef_advanced_link', 0);
	//$db = JFactory::getDBO();
	// Count route segments
	$count = count($segments);

	if($count<=0)return $vars;

	return $vars;
	}
