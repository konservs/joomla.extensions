<?php
defined('_JEXEC') or die('No direct access!');

JLog::addLogger(array('text_file' => 'com_addarticle.php'), JLog::ALL, array('com_addarticle'));

jimport('joomla.application.component.controller');
if(!class_exists('JControllerLegacy')){
	class_alias('JController', 'JControllerLegacy');
	}

$controller = JControllerLegacy::getInstance('Addarticle');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
$controller->redirect();
