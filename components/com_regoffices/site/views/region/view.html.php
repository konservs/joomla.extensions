<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class RegofficesViewRegion extends JViewLegacy{
	protected $msg;
	/**
	 *
	 */
	public function display($tpl = null){
		$this->region=$this->get('Item');
		if(empty($this->region)){
			return null;
			}
		//
		$this->getoptions();
		//Get countries...
		if($this->show_page_country){
			$this->countries=$this->get('Countries');
			$this->country=$this->region->getcountry();
			if(empty($this->country)){
				return null;
				}
			}
		//Get cities...
		$this->cities=$this->get('Cities');
		//Get cities...
		$this->regions=$this->get('Regions');
		//Get offices...
		$this->offices=$this->get('Offices');
		//Fetch exceptions
		if(count($errors=$this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_exhibitions');
				}
			}
		$this->_prepareDocument();
		parent::display($tpl);
		}
	/**
	 * Prepares the document.
	 *
	 * @return  void.
	 */
	protected function _prepareDocument(){
		$app=JFactory::getApplication();
		$doc=JFactory::getDocument();
		//$menus=$app->getMenu();
		$pathway=$app->getPathway();
		//
		$this->cntr_name=$this->region->getlangvar('name');
		$this->description=$this->region->getlangvar('description');
		//Pathway - for breadcrumbs.
		$pathway->addItem($this->cntr_name, '#');
		//
		$this->heading=$this->region->getlangvar('h1');
		if(empty($this->heading)){
			$this->heading=$this->cntr_name;
			}
		//Title
		$title=$this->region->getlangvar('title');
		if(empty($title)){
			$title=$this->cntr_name;
			}
		$doc->settitle($title);
		//Meta description
		$descr=$this->region->getlangvar('metadesc');
		if(!empty($descr)){
			$doc->setMetaData('description',$descr);
			}
		//Meta keywords
		$keyw=$this->region->getlangvar('metakeyw');
		if(!empty($keyw)){
			$doc->setMetaData('keywords',$keyw);
			}
		return true;
		}
	/**
	 *
	 */
	public function getoptions(){
		jimport('joomla.application.component.helper');
		$params=JComponentHelper::getParams('com_regoffices');
		$this->mapskey=$params->get('googlemaps_key');
		$this->show_page_country=$params->get('show_page_country');
		$this->show_page_region=$params->get('show_page_region');
		$this->show_page_city=$params->get('show_page_city');
		//
		$this->url_offices_json=JRoute::_('index.php?option=com_regoffices&view=offices&format=json');
		return true;
		}
	}
