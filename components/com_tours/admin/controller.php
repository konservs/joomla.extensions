<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');
 
/**
 * Общий контроллер компонента Tours.
 */
class ToursController extends JControllerLegacy
{
    /**
     * Задача по отображению.
     *
     * @param   boolean  $cachable   Если true, то представление будет закешировано.
     * @param   array    $urlparams  Массив безопасных url-параметров и их валидных типов переменных.
     *
     * @return  void
     */
    public function display($cachable = false, $urlparams = array()) 
    {
        // Устанавливаем представление по умолчанию, если оно не было установлено.
        $input = JFactory::getApplication()->input;
	$view=$input->getCmd('view', 'tours');
        $input->set('view', $view);
	ToursHelper::addSubmenu($view);
        parent::display($cachable);
    }
}