<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_tours.countries');
 
/**
 * 
 */
class ToursModelCountries extends JModelList{
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
		$countries=BCountries::getInstance();
		$list=$countries->countries_get_all();
		return $list;
		}
	}