<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
jimport('brilliant_tours.tours');
 
/**
 * Модель списка сообщений компонента Tours.
 */
class ToursModelTours extends JModelList{
    /**
     * 
     *
     * @return  string  SQL запрос.
     */
	public function getPagination(){
		//????
		return NULL;
		}

	public function getItems(){
		$tours=BTours::getInstance();
		$list=$tours->tours_get_all();
		return $list;
		}
	}