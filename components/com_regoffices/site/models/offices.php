<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modellist');

class RegofficesModelOffices extends JModelList{
	/**
	 *
	 */
	public function filterItems($options=array()){
		jimport('brilliant_regoffices.offices');
		$bro=BRegofficesOffices::getInstance();
		$list=$bro->items_filter($options);
		return $list;
		}
	}
