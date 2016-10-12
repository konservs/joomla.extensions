<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class ExhibitionsModelArchive extends JModelItem{
	protected $exhibitions;
	public function getArchive(){
		jimport('brilliant_exhibitions.exhibitions');
		$exhibitions=BExhibitions::getInstance();
		return $exhibitions->exhibitions_get_all();
		}
	}

