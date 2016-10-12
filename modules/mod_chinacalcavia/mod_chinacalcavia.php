<?php 
/**
 * Модуль подсчёта стоимости доставки.
 *
 */

defined('_JEXEC') or die('No direct access!');
$vt=strtolower($params->get('view_type', 'sea'));

switch($vt){
	case 'sea':
		require JModuleHelper::getLayoutPath('mod_chinacalcavia', $params->get('layout', 'calculator_sea'));
		break;
	case 'avia':
		require JModuleHelper::getLayoutPath('mod_chinacalcavia', $params->get('layout', 'calculator_avia'));
		break;
	case 'express':
		require JModuleHelper::getLayoutPath('mod_chinacalcavia', $params->get('layout', 'calculator_express'));
		break;
	default:
		require JModuleHelper::getLayoutPath('mod_chinacalcavia', $params->get('layout', 'default'));
		break;
	}
