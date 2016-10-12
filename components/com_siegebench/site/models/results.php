<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class ExhibitionsModelExhibitions extends JModelItem{
	protected $exhibitions;
	public function getIndustries(){
		jimport('brilliant_exhibitions.industries');
		$industries=BExhibitionsIndustries::getInstance();
		return $industries->industries_get_all();
		}
	public function getCities(){
		jimport('brilliant_exhibitions.cities');
		$cities=BCities::getInstance();
		return $cities->cities_get_all();
		}
	public function filterExhibitions($filter){
		jimport('brilliant_exhibitions.exhibitions');
		jimport('brilliant_exhibitions.industries');
		$exhibitions=BExhibitions::getInstance();
		$list=$exhibitions->exhibitions_get_filter($filter);
		//Return exhibitions...
		return $list;		
		}
	public function getExhibitions(){
		jimport('brilliant_exhibitions.exhibitions');
		jimport('brilliant_exhibitions.industries');
		$exhibitions=BExhibitions::getInstance();
		$list=$exhibitions->exhibitions_get_actual();
		//Return exhibitions...
		return $list;		
		}
	}

