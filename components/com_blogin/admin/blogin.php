<?php
defined('_JEXEC') or die;
JError::$legacy = false; 
jimport('joomla.application.component.controller');
if(!class_exists('JControllerLegacy')){
	class_alias('JController', 'JControllerLegacy');
	}
$controller = JControllerLegacy::getInstance('BLogin');
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task', 'display'));
$controller->redirect();
