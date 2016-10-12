<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку контроллера Joomla.
jimport('joomla.application.component.controller');
 
/**
 * Общий контроллер компонента Regoffices.
 */
class RegofficesController extends JControllerLegacy
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
	require_once JPATH_COMPONENT . '/helpers/regoffices.php';

        // Устанавливаем представление по умолчанию, если оно не было установлено.
        $input = JFactory::getApplication()->input;
        $input->set('view', $input->getCmd('view', 'offices'));
 
        parent::display($cachable);
    }
}