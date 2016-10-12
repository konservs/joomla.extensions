<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class RegofficesViewHome extends JViewLegacy{
	protected $msg;

	public function display($tpl = null){
		$model = $this->getModel();
		//Get countries...
		$this->countries=$this->get('Countries');
		//Fetch exceptions
		if(count($errors = $this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_exhibitions');
				}
			}
		//
		jimport('brilliant.general');
		$url_current = bfull_url($_SERVER);
		$url_need = rtrim(JUri::base(), '/').JRoute::_('index.php?option=com_regoffices&view=home');
		if($url_current!=$url_need){
		        Header("HTTP/1.1 301 Moved Permanently");
		        Header("Location: ".$url_need);
			die();
		        }
		//
		$doc=JFactory::getDocument();
		$descr=JText::_('COM_REGOFFICES_METADESC');
		if((!empty($descr))&&($descr!='COM_REGOFFICES_METADESC')){
			$doc->setMetaData('description',$descr);
			}
		$keyw=JText::_('COM_REGOFFICES_METAKEYW');
		if((!empty($keyw))&&($keyw!='COM_REGOFFICES_METAKEYW')){
			$doc->setMetaData('keywords',$keyw);
			}
		parent::display();
		}
	}
