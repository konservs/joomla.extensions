<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modellist Joomla.
jimport('joomla.application.component.modellist');
jimport('brilliant_exhibitions.exhibitions');
 
/**
 * Модель списка сообщений компонента Exhibitions.
 */
class ExhibitionsModelExhibitions extends JModelList{
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
		$exhibitions=BExhibitions::getInstance();
		$list=$exhibitions->exhibitions_get_all();
		return $list;
		}
	}