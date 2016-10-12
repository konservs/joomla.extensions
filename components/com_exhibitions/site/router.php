<?php
defined('_JEXEC') or die;
jimport('joomla.application.categories');
jimport('brilliant_exhibitions.exhibitions');


function ExhibitionsBuildRoute(&$query){
	$segments	= array(); 
	$app		= JFactory::getApplication();
	$menu		= $app->getMenu();
	$params		= JComponentHelper::getParams('com_exhibitions');
	$advanced	= $params->get('sef_advanced_link', 0);

	if(isset($query['view']))
		$view = $query['view'];else
		return $segments;

	if($view=='exhibitions'){
		unset($query['view']);
		}

	if($view=='archive'){
		//Seek menu with exhibitions archive...
		$url='';
		$items=$menu->getItems();
		foreach($items as $itm)
			if(($itm->component=='com_exhibitions')&&($itm->query['view']=='archive')){
				$Itemid=$itm->id;
				//var_dump($itm->params);
				break;
				}
		if(empty($Itemid))return $segments;
		unset($query['view']);
		$query['Itemid']=$Itemid;
		}


	if(($view=='exhibition')&&(isset($query['id']))){
		$id=(int)$query['id'];
		//Seek menu with exhibitions...
		$url='';
		$items=$menu->getItems();
		foreach($items as $itm){
			if(($itm->component=='com_exhibitions')&&($itm->query['view']=='exhibitions')){
				$url='/'.$itm->route.'/';
				$Itemid=$itm->id;
				//var_dump($itm);
				break;
				}
			}
		if(empty($url))return $segments;

		$exhibitions=BExhibitions::getInstance();
		$exhibition=$exhibitions->exhibition_get($id);
		if(empty($exhibition))return $segments;
		//Forming URL...
		unset($query['view']);
		unset($query['id']);
		$query['Itemid']=$Itemid;

		$segments[]=$id.'-'.$exhibition->getAlias();
		}
	return $segments;
	}


function ExhibitionsParseRoute($segments){
	$vars = array();
	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	$params = JComponentHelper::getParams('com_exhibitions');
	$advanced = $params->get('sef_advanced_link', 0);
	$db = JFactory::getDBO();
	// Count route segments
	$count = count($segments);



	if($count<=0)return $vars;

	$a1=explode(':',$segments[$count-1]);
	if((count($a1)>1)&&(is_numeric($a1[0])))
		$id=(int)$a1[0];
	$vars['view'] = 'exhibition';
	$vars['id'] = $id;

	return $vars;
	}
