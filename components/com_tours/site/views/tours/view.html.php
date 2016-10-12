<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class ToursViewTours extends JViewLegacy{
	protected $msg;

	public function display($tpl = null){
		$model = $this->getModel();
		$options=array();
		$this->tours=$model->filterTours($options);//$this->get('Tours');
		/*$this->industries=$this->get('Industries');
		$this->cities=$this->get('Cities');*/
		//Fetch exceptions
		if(count($errors = $this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_tours');
				}
			}
		/*$doc=&JFactory::getDocument();
		$descr=JText::_('COM_TourS_METADESC');
		if((!empty($descr))&&($descr!='COM_TourS_METADESC'))
			$doc->setMetaData('description',$descr);
		$keyw=JText::_('COM_TourS_METAKEYW');
		if((!empty($keyw))&&($keyw!='COM_TourS_METAKEYW'))
			$doc->setMetaData('keywords',$keyw);*/
		parent::display($tpl);
		}
	}
