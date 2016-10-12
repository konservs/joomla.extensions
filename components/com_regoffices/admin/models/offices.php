<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_regoffices.offices');
 
/**
 * 
 */
class RegofficesModelOffices extends JModelList{
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
		$roc=BRegofficesOffices::getInstance();
		$list=$roc->items_get_all();
		return $list;
		}
	}