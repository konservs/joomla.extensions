<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class BloginModelSmslogin extends JModelItem{
	/**
	 *
	 */
	public function sendSMS(){
		return true;
		}
	/**
	 *
	 */
	public function getPhoneCanonical($phone){
		$phonename = $phone;
		if(strlen($phonename)<12){
			return $phonename;
			}
		if($phonename[0]=='+'){
			$phonename = substr($phonename,1);
			}
		return $phonename;
		}
	/**
	 *
	 */
	public function getPhone(){
		$jinput = JFactory::getApplication()->input;
		$do = $jinput->get('do', '', 'string');
		$phonename = $jinput->get('phone', '', 'string');
		if($do=='loginregister'){
			$phonename2 = $this->getPhoneCanonical($phonename);
			if(strlen($phonename2)<12){
				JError::raiseWarning('PHONE_LOW_DIGITS', 'Too less digits!');
				return $phonename;
				}
			if(strlen($phonename2)>12){
				JError::raiseWarning('PHONE_MUCH_DIGITS', 'Too much digits!');
				return $phonename;
				}
			}
		return $phonename;
		}
	/**
	 *
	 */
	public function loginRegister($phonenum){
		return true;
		}

	}
