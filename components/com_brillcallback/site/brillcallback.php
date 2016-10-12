<?php
defined('_JEXEC') or die;

JLog::addLogger(array('text_file' => 'com_brillcallback.php'), JLog::ALL, array('com_brillcallback'));
jimport('joomla.application.component.controller');
if(!class_exists('JControllerLegacy')){
	class_alias('JController', 'JControllerLegacy');
	}


$controller = JControllerLegacy::getInstance('Brillcallback');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
$controller->redirect();
