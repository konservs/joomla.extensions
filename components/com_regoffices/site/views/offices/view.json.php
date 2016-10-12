<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class RegofficesViewOffices extends JViewLegacy{
	/**
	 *
	 */
	public function display($tpl = null){
		$json=new stdClass();
		$json->offices=array();

		$model = $this->getModel();
		//Get filters...
		$this->city_id=JRequest::getVar('city',0,'get','int');
		$this->country_id=JRequest::getVar('country',0,'get','int');
		//Form
		$options=array();
		if(!empty($this->city_id)){
			$options['city']=$this->city_id;
			}
		if(!empty($this->country_id)){
			$options['country']=$this->country_id;
			}
		//
		$offices=$model->filterItems($options);//$this->get('Exhibitions');
		foreach($offices as $office){
			$xoffice=new stdClass();
			$xoffice->name=$office->getlangvar('name');
			$xoffice->lat=$office->lat;
			$xoffice->lng=$office->lng;
			$json->offices[]=$xoffice;
			}
		//
		$document =JFactory::getDocument();
		// Set the MIME type for JSON output.
		$document->setMimeEncoding('application/json');
		// Change the suggested filename.
		$document->setName('offices');
		//JResponse::setHeader('Content-Disposition','attachment;filename="offices.json"');
		echo json_encode($json);
		}
	}
