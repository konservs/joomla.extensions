<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку controlleradmin Joomla.
jimport('joomla.application.component.controlleradmin');
 
/**
 * Tours контроллер.
 */
class ToursControllerTours extends JControllerAdmin
{   
    /**
     * Прокси метод для getModel.
     *
     * @param   string  $name    Имя класса модели.
     * @param   string  $prefix  Префикс класса модели.
     *
     * @return  object  Объект модели.
     */
    public function getModel($name = 'Tour', $prefix = 'ToursModel')
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }   

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
        $input->set('view', $input->getCmd('view', 'tours'));
        // Устанавливаем подменю.
        ToursHelper::addSubmenu('tours');
        parent::display($cachable);
    }

}