<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку controlleradmin Joomla.
jimport('joomla.application.component.controlleradmin');
 
/**
 * Countries контроллер.
 */
class ToursControllerCountries extends JControllerAdmin
{   
    /**
     * Прокси метод для getModel.
     *
     * @param   string  $name    Имя класса модели.
     * @param   string  $prefix  Префикс класса модели.
     *
     * @return  object  Объект модели.
     */
    public function getModel($name = 'Countries', $prefix = 'ToursModel')
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }   


}