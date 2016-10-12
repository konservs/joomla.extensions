<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_regoffices.regions');
 
/**
 * 
 */
class RegofficesModelRegions extends JModelList{
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
		$regions=BRegofficesRegions::getInstance();

		$params=array();
		$this->country=JRequest::getVar('country');
		if(!empty($this->country)){
			$params['country']=$this->country;
			}
		$list=$regions->items_filter($params);
		return $list;
		}
	}