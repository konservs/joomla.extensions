<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_exhibitions.industries');
 
/**
 * 
 */
class ExhibitionsModelIndustries extends JModelList{
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
		$industries=BExhibitionsIndustries::getInstance();
		$list=$industries->industries_get_all();
		return $list;
		}
	}