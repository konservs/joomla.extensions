<?php
defined('_JEXEC') or die;

JLog::addLogger(array('text_file' => 'com_exhibitions.php'), JLog::ALL, array('com_exhibitions'));
jimport('joomla.application.component.controller');
if(!class_exists(JControllerLegacy))
	class_alias('JController', 'JControllerLegacy');


$controller = JControllerLegacy::getInstance('Exhibitions');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
$controller->redirect();
?>