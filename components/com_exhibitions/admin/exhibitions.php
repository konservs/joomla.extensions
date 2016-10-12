<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

// Устанавливаем обработку ошибок в режим использования Exception.

JError::$legacy = false; 

// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');

// Получаем экземпляр контроллера с префиксом Exhibitions.
if(!class_exists('JControllerLegacy'))
	class_alias('JController', 'JControllerLegacy');
$controller = JControllerLegacy::getInstance('Exhibitions');

// Исполняем задачу task из Запроса.
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task', 'display'));

// Перенаправляем, если перенаправление установлено в контроллере.
$controller->redirect();
