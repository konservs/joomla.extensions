<?php
defined('_JEXEC') or die;
jimport('joomla.application.categories');

function CalculatorBuildRoute(&$query){
	$segments	= array(); 
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	$params		= JComponentHelper::getParams('com_calculator');
	$advanced	= $params->get('sef_advanced_link', 0);
	if(isset($query['view']))
		$view = $query['view'];else
		return $segments;
	if($view=='calculator'){
		//Seek menu with Calculator...
		$url='';
		$items=$menu->getItems(NULL,NULL);
		foreach($items as $itm)
			if(($itm->component=='com_calculator')&&($itm->query['view']=='calculator')){
				$Itemid=$itm->id;
				break;
				}
		if(empty($Itemid))return $segments;
		unset($query['view']);
		$query['Itemid']=$Itemid;
		}
	return $segments;
	}


function CalculatorParseRoute($segments){
	$vars = array();
	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_calculator');
	$advanced = $params->get('sef_advanced_link', 0);
	$db = JFactory::getDBO();
	// Count route segments
	$count = count($segments);

	return $vars;
	}
