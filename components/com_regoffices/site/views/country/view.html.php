<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class RegofficesViewCountry extends JViewLegacy{
	protected $msg;
	/**
	 *
	 */
	public function display($tpl = null){
		$this->country=$this->get('Item');
		if(empty($this->country)){
			return null;
			}
		//Get countries...
		$this->countries=$this->get('Countries');
		//Get cities...
		$this->cities=$this->get('Cities');
		//Fetch exceptions
		if(count($errors=$this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_exhibitions');
				}
			}
		//
		jimport('brilliant.general');
		$url_current = bfull_url($_SERVER);
		$url_need = rtrim(JUri::base(), '/').JRoute::_('index.php?option=com_regoffices&view=country&id='.$this->country->id);
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
		$this->description=$this->country->getlangvar('description');
		//Pathway - for breadcrumbs.
		$pathway->addItem($this->cntr_name, '#');
		//
		$this->heading=$this->country->getlangvar('h1');
		if(empty($this->heading)){
			$this->heading=$this->cntr_name;
			}
		//Title
		$title=$this->country->getlangvar('title');
		if(empty($title)){
			$title=$this->cntr_name;
			}
		$doc->settitle($title);
		//Meta description
		$descr=$this->country->getlangvar('metadesc');
		if(!empty($descr)){
			$doc->setMetaData('description',$descr);
			}
		//Meta robots
		$metarobots=$this->country->getlangvar('metarobots');
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
		$keyw=$this->country->getlangvar('metakeyw');
		if(!empty($keyw)){
			$doc->setMetaData('keywords',$keyw);
			}
		return true;
		}
	}
