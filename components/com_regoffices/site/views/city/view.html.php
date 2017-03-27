<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class RegofficesViewCity extends JViewLegacy{
	protected $msg;
	/**
	 *
	 */
	public function display($tpl = null){
		$this->city=$this->get('Item');
		if(empty($this->city)){
			return null;
			}
		//
		$this->country=$this->city->getcountry();
		if(empty($this->country)){
			return null;
			}
		//Get countries...
		$this->countries=$this->get('Countries');
		//Get cities...
		$this->cities=$this->get('Cities');
		//Get offices...
		$this->offices=$this->get('Offices');
		//Fetch exceptions
		if(count($errors=$this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_exhibitions');
				}
			}
		//
		$doc = JFactory::getDocument();
		if(version_compare(JVERSION, '3.0.0', 'ge')){
			JHtml::_('jquery.framework', false);
			} else {
			if(!JFactory::getApplication()->get('jquery')){
				JFactory::getApplication()->set('jquery',true);
				$doc = JFactory::getDocument();
				$doc->addScript(JUri::root().'media/com_regoffices/js/jquery.js');
				}
			}
		$doc->addScript('/media/com_regoffices/js/regoffices.js');
		//
		jimport('brilliant.general');
		$url_current = bfull_url($_SERVER);
		$url_need = rtrim(JUri::base(), '/').JRoute::_('index.php?option=com_regoffices&view=city&id='.$this->city->id);
		if($url_current!=$url_need){
		        Header("HTTP/1.1 301 Moved Permanently");
		        Header("Location: ".$url_need);
			die();
		        }
		//
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
		$this->cntr_name=$this->country->getlangvar('name');
		$this->cntr_url=rtrim(JUri::base(), '/').JRoute::_('index.php?option=com_regoffices&view=country&id='.$this->country->id);
		$this->city_name=$this->city->getlangvar('name');
		$this->description=$this->city->getlangvar('description');
		//Pathway - for breadcrumbs.
		$pathway->addItem($this->cntr_name, $this->cntr_url);
		$pathway->addItem($this->city_name, '#');
		//
		$this->heading=$this->city->getlangvar('h1');
		if(empty($this->heading)){
			$this->heading=$this->cntr_name;
			}
		//Title
		$title=$this->city->getlangvar('title');
		if(empty($title)){
			$title=$this->cntr_name;
			}
		$doc->settitle($title);
		//Meta description
		$descr=$this->city->getlangvar('metadesc');
		if(!empty($descr)){
			$doc->setMetaData('description',$descr);
			}
		//Meta robots
		$metarobots=$this->city->getlangvar('metarobots');
		if(!empty($metarobots)){
			switch($metarobots){
				case 1:
					$doc->setMetaData('robots','noindex, follow');
					break;
				case 2:
					$doc->setMetaData('robots','index, nofollow');
					break;
				case 3:
					$doc->setMetaData('robots','noindex, nofollow');
					break;
				}
			}
		//Meta keywords
		$keyw=$this->city->getlangvar('metakeyw');
		if(!empty($keyw)){
			$doc->setMetaData('keywords',$keyw);
			}
		return true;
		}
	}
