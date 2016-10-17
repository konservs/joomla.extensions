<?php 
defined('_JEXEC') or die;

$vt=strtolower($params->get('view_type', 'Callback'));
switch($vt){
	case 'callback':
		require JModuleHelper::getLayoutPath('mod_brillcallback', $params->get('layout', 'default'));
		break;
	case 'feedback':
		require JModuleHelper::getLayoutPath('mod_brillcallback', $params->get('layout', 'feedback'));
		break;
	case 'simple':
		require JModuleHelper::getLayoutPath('mod_brillcallback', $params->get('layout', 'simple'));
		break;
	default:
		require JModuleHelper::getLayoutPath('mod_brillcallback', $params->get('layout', 'default'));
		break;
	}
