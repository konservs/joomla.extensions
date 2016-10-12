<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists(JViewLegacy)){
	class_alias('JView', 'JViewLegacy');
	}

class SiegebenchViewResults extends JViewLegacy{
	protected $msg;

	public function display($tpl = null){
		$model = $this->getModel();
		//Get filters...
		$this->city_id=JRequest::getVar('city',0,'post','int');
		$this->industry_id=JRequest::getVar('industry',0,'post','int');
		$post_date_from=JRequest::getVar('date-from','','post','string');
		$this->date_from=new DateTime($post_date_from);
		$post_date_to=JRequest::getVar('date-to','','post','string');
		if(empty($post_date_to))
			$this->date_to=NULL; else
			$this->date_to=new DateTime($post_date_to);
		//Form
		$options['city']=$this->city_id;
		$options['industry']=$this->industry_id;
		$options['date_from']=$this->date_from;
		$options['date_to']=$this->date_to;
		//Get exhibitions, industries, cities
		$this->exhibitions=$model->filterExhibitions($options);//$this->get('Exhibitions');
		$this->industries=$this->get('Industries');
		$this->cities=$this->get('Cities');
		//Fetch exceptions
		if(count($errors = $this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_exhibitions');
				}
			}
		$doc=&JFactory::getDocument();
		$descr=JText::_('COM_EXHIBITIONS_METADESC');
		if((!empty($descr))&&($descr!='COM_EXHIBITIONS_METADESC'))
			$doc->setMetaData('description',$descr);
		$keyw=JText::_('COM_EXHIBITIONS_METAKEYW');
		if((!empty($keyw))&&($keyw!='COM_EXHIBITIONS_METAKEYW'))
			$doc->setMetaData('keywords',$keyw);
		parent::display($tpl);
		}
	}
?>