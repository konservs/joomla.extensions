<?php
defined('_JEXEC') or die;

jimport('joomla.application.categories');


function RegofficesGetMainMenu(){
	$app=JFactory::getApplication();
	$menu=$app->getMenu();
	$Itemid=0;
	$items=$menu->getItems(array(),array());
	foreach($items as $itm){
		if(($itm->component=='com_regoffices')&&($itm->query['view']=='home')){
			$url='/'.$itm->route.'/';
			$Itemid=$itm->id;
			break;
			}
		}
	return $Itemid;
	}

function RegofficesBuildRoute(&$query){
	$segments		= array(); 
	$params			= JComponentHelper::getParams('com_regoffices');
	$show_page_country	= $params->get('show_page_country', 0);
	$show_page_region	= $params->get('show_page_region', 0);
	$show_page_city		= $params->get('show_page_city', 0);

	if(isset($query['view'])){
		$view = $query['view'];
		}else{
		return $segments;
		}

	$format='';
	if(isset($query['format'])){
		$format=$query['format'];
		}

	if($view=='home'){
		unset($query['view']);
		}

	if(($view=='offices')&&($format=='json')){
		unset($query['view']);
		unset($query['format']);
		$Itemid=RegofficesGetMainMenu();
		if(empty($Itemid))return $segments;
		$segments[]='json';
		$segments[]='offices';
		}
	if(($view=='country')&&(isset($query['id']))){
		$id=(int)$query['id'];
		//Seek menu with regorrices...
		$Itemid=RegofficesGetMainMenu();
		if(empty($Itemid))return $segments;
		//
		jimport('brilliant_regoffices.countries');
		$roc=BRegofficesCountries::getInstance();
		$country=$roc->item_get($id);
		if(empty($country)){
			return $segments;
			}
		//Forming URL...
		unset($query['view']);
		unset($query['id']);
		$query['Itemid']=$Itemid;
		if($show_page_country){
			$segments[]=strtolower($country->getlangvar('alias'));
			}
		}
	if(($view=='region')&&(isset($query['id']))){
		$id=(int)$query['id'];
		//Seek menu with regorrices...
		$Itemid=RegofficesGetMainMenu();
		if(empty($Itemid))return $segments;
		//
		jimport('brilliant_regoffices.regions');
		$rorr=BRegofficesRegions::getInstance();
		$region=$rorr->item_get($id);
		if(empty($region)){
			return $segments;
			}
		unset($query['view']);
		unset($query['id']);
		$query['Itemid']=$Itemid;
		if(!$show_page_region){
			return $segments;
			}
		//Forming URL...
		if($show_page_country){
			jimport('brilliant_regoffices.countries');
			$roc=BRegofficesCountries::getInstance();
			$country=$roc->item_get($city->country);
			if(empty($country)){
				return $segments;
				}
			$segments[]=strtolower($country->getlangvar('alias'));
			}
		$segments[]=strtolower($region->getlangvar('alias'));
		}
	if(($view=='city')&&(isset($query['id']))){
		$id=(int)$query['id'];
		//Seek menu with regorrices...
		$Itemid=RegofficesGetMainMenu();
		if(empty($Itemid))return $segments;
		//
		jimport('brilliant_regoffices.cities');
		$rocc=BRegofficesCities::getInstance();
		$city=$rocc->item_get($id);
		if(empty($city)){
			return $segments;
			}
		//
		unset($query['view']);
		unset($query['id']);
		$query['Itemid']=$Itemid;
		//If we are not showing city pages - generate ROOT url.
		if(!$show_page_city){
			return $segments;
			}
		//Forming URL...
		if($show_page_country){
			jimport('brilliant_regoffices.countries');
			$roc=BRegofficesCountries::getInstance();
			$country=$roc->item_get($city->country);
			if(empty($country)){
				return $segments;
				}
			$segments[]=strtolower($country->getlangvar('alias'));
			}
		//Forming URL...
		if($show_page_region){
			jimport('brilliant_regoffices.regions');
			$ror=BRegofficesRegions::getInstance();
			$region=$ror->item_get($city->region);
			if(empty($region)){
				return $segments;
				}
			$segments[]=strtolower($region->getlangvar('alias'));
			}
		$segments[]=strtolower($city->getlangvar('alias'));
		}
	return $segments;
	}


function RegofficesParseRoute($segments){
	$vars = array();
	//Get the active menu item.
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$item	= $menu->getActive();
	//Get params
	$params = JComponentHelper::getParams('com_regoffices');
	$show_page_country	= $params->get('show_page_country', 0);
	$show_page_region	= $params->get('show_page_region', 0);
	$show_page_city		= $params->get('show_page_city', 0);
	//
	$langx=JFactory::getLanguage();
	$lang=$langx->getTag();


	//$db = JFactory::getDBO();
	// Count route segments
	$count = count($segments);

	if(empty($segments)){
		$vars['view'] = 'home';
		return $vars;
		}
	//
	if(($segments[0]=='json')&&($segments[1]=='offices')){
		$vars['view'] = 'offices';
		$vars['format']= 'json';
		return $vars;
		}
	//Country...
	$country=NULL;
	$region=NULL;
	$city=NULL;
	if($show_page_country){
		if(strlen($segments[0])>0){
			$alias=str_replace(':','-',$segments[0]);
			jimport('brilliant_regoffices.countries');
			$roc=BRegofficesCountries::getInstance();
			$country=$roc->item_get_by_alias($alias,$lang);
			array_shift($segments);
			}
		if(empty($country)){
			return $vars;
			}
		//
		if(empty($segments)){
			$vars['view'] = 'country';
			$vars['id'] = $country->id;
			return $vars;
			}
		}
	//Region
	if($show_page_region){
		if(strlen($segments[0])>0){
			$alias=str_replace(':','-',$segments[0]);
			jimport('brilliant_regoffices.regions');
			$ror=BRegofficesRegions::getInstance();
			$params=array();
			if($country){
				$params['country']=$country->id;
				}
			$params['alias:'.$lang]=$alias;
			$region=$ror->items_filter($params);
			$region=reset($region);
			array_shift($segments);
			}
		if(empty($region)){
			return $vars;
			}
		//
		if(empty($segments)){
			$vars['view'] = 'region';
			$vars['id'] = $region->id;
			return $vars;
			}
		}
	//City...
	if($show_page_city){
		if(strlen($segments[0])>0){
			$alias=str_replace(':','-',$segments[0]);
			jimport('brilliant_regoffices.cities');
			$rocc=BRegofficesCities::getInstance();
			$params=array();
			if($country){
				$params['country']=$country->id;
				}
			if($region){
				$params['region']=$region->id;
				}
			$params['alias:'.$lang]=$alias;
			$city=$rocc->items_filter($params);
			$city=reset($city);
			array_shift($segments);
			}
		if(empty($city)){
			return $vars;
			}
		//
		if(empty($segments)){
			$vars['view'] = 'city';
			$vars['id'] = $city->id;
			return $vars;
			}
		}
	return $vars;
	}
