<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_tours.categories');
 
/**
 * 
 */
class ToursModelCategories extends JModelList{
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
		$categories=BToursCategories::getInstance();
		$list=$categories->categories_get_all();
		return $list;
		}
	}
