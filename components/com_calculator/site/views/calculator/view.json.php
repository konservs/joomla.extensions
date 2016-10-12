<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class CalculatorViewCalculator extends JViewLegacy{
	protected $msg;
	/**
	 * Display JSON result
	 */
	public function display($tpl = null){
		$model = $this->getModel();
		$this->errorcode=$model->Calculate();
		$this->errormessage=$model->errormessage;
		$res=(object)array(
			'errorcode'=>$this->errorcode,
			'errormessage'=>$this->errormessage
			);
		echo json_encode($res);
		return true;
		}
	}
