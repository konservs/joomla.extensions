<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class CalculatorViewCalculator extends JViewLegacy{
	protected $msg;
	/**
	 * Display HTML result.
	 */
	public function display($tpl = null){
		$model = $this->getModel();
		$this->errorcode=$model->Calculate();
		$this->errormessage=$model->errormessage;

		//$doc=JFactory::getDocument();
		//Fetch exceptions
		/*if(count($errors = $this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_brillcallback');
				}
			}*/
		parent::display($tpl);
		}
	}
