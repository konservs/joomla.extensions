<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_regoffices.cities');
 
/**
 * 
 */
class RegofficesModelCities extends JModelList{
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
		$rocc=BRegofficesCities::getInstance();
		$list=$rocc->items_get_all();
		return $list;
		}
	}