<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_regoffices.countries');
 
/**
 * 
 */
class RegofficesModelCountries extends JModelList{
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
		$roc=BRegofficesCountries::getInstance();
		$list=$roc->items_get_all();
		return $list;
		}
	}