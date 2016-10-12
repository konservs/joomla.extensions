<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');
jimport('brilliant_tours.orders');
 
/**
 * 
 */
class ToursModelOrders extends JModelList{
	public function getPagination(){
		return NULL;
		}
	public function getItems(){
		$orders=BToursOrders::getInstance();
		$list=$orders->orders_get_all();
		return $list;
		}
	}
