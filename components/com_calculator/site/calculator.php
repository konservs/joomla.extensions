<?php
defined('_JEXEC') or die;

JLog::addLogger(array('text_file' => 'com_calculator.php'), JLog::ALL, array('com_calculator'));
jimport('joomla.application.component.controller');
if(!class_exists('JControllerLegacy')){
	class_alias('JController', 'JControllerLegacy');
	}

$controller = JControllerLegacy::getInstance('Calculator');

$input=JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
$controller->redirect();
