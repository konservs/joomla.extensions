<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');
 
/**
 * Общий контроллер компонента siegebench.
 */
class SiegebenchController extends JControllerLegacy
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
        $input = JFactory::getApplication()->input;
        $input->set('view', $input->getCmd('view', 'Siegebench'));
 
        parent::display($cachable);
    }
}