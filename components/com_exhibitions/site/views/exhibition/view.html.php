<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists(JViewLegacy))
	class_alias('JView', 'JViewLegacy');

class ExhibitionsViewExhibition extends JViewLegacy{
	protected $msg;

	public function display($tpl = null){
		$this->exhibition = $this->get('Item');
		if(count($errors=$this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_exhibitions');
				}
			return false;
			}
		if(empty($this->exhibition)){
			return false;
			}
		//Set last-modified & 
		JResponse::setHeader('Last-Modified',$this->exhibition->getModified()->format('D, d M Y H:i:s').' GMT');
		//if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])>=$last_modified){
		//	header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
		//	die;
		//	}

		//Set title, description & keywords
		$doc=&JFactory::getDocument();
		$doc->settitle($this->exhibition->getTitle());
		$descr=$this->exhibition->getMetaDesc();
		if(!empty($descr))
			$doc->setMetaData('description',$descr);
		$keyw=$this->exhibition->getMetaKeyw();
		if(!empty($keyw))
			$doc->setMetaData('keywords',$keyw);
		parent::display($tpl);
		}
	}
