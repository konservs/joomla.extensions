<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_exhibitions.cities');
 
/**
 * 
 */
class ExhibitionsModelCities extends JModelList{
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
		$cities=BCities::getInstance();
		$list=$cities->cities_get_all();
		return $list;
		}
	}