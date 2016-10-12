<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class ToursModelTours extends JModelItem{
	protected $tours;
	/**
	 *
	 */
	public function getCategories(){
		jimport('brilliant_tours.categories');
		$industries=BToursIndustries::getInstance();
		return $categories->categories_get_all();
		}
	/**
	 *
	 */
	public function getCities(){
		jimport('brilliant_tours.cities');
		$cities=BCities::getInstance();
		return $cities->cities_get_all();
		}
	/**
	 *
	 */
	public function filterTours($filter){
		jimport('brilliant_tours.tours');
		$tours=BTours::getInstance();
		$list=$tours->tours_get_filter($filter);
		return $list;		
		}
	/**
	 * Get actual tours
	 */
	public function getTours(){
		jimport('brilliant_tours.tours');
		$tours=BTours::getInstance();
		$list=$tours->tours_get_actual();
		return $list;		
		}
	}
