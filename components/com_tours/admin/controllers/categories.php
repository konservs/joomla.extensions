<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку controlleradmin Joomla.
jimport('joomla.application.component.controlleradmin');
 
/**
 * Industries контроллер.
 */
class ToursControllerIndustries extends JControllerAdmin
{   
    /**
     * Прокси метод для getModel.
     *
     * @param   string  $name    Имя класса модели.
     * @param   string  $prefix  Префикс класса модели.
     *
     * @return  object  Объект модели.
     */
    public function getModel($name = 'Insdustries', $prefix = 'ExhibitionsModel')
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }   


}