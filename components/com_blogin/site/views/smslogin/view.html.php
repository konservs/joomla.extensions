<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}

class BloginViewSmslogin extends JViewLegacy{
	protected $msg;

	public function display($tpl = null){
		$model = $this->getModel();
		$this->phonenum=$model->getPhone();
		//Fetch exceptions
		if(count($errors = $this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_brilllogin');
				}
			} else {
			if($this->phonenum){
				$model->loginRegister($this->phonenum);
				}
			}
		$this->codeSent = $model->codeSent;
		$doc=JFactory::getDocument();
		//
		$descr=JText::_('COM_BLOGIN_SMSLOGIN_METADESC');
		if((!empty($descr))&&($descr!='COM_BLOGIN_SMSLOGIN_METADESC')){
			$doc->setMetaData('description',$descr);
			}
		$keyw=JText::_('COM_BLOGIN_SMSLOGIN_METAKEYS');
		if((!empty($keyw))&&($keyw!='COM_BLOGIN_SMSLOGIN_METAKEYS')){
			$doc->setMetaData('keywords',$keyw);
			}
		parent::display($tpl);
		}
	}
