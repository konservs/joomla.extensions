<?php
/**
 * Model to send emails to customer & administrator.
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copytight (c) Brilliant IT, http://it.brilliant.ua
 */

defined('_JEXEC') or die('No direct access!');

jimport('joomla.application.component.modelitem');

class CalculatorModelCalculator extends JModelItem{
	public $errormessage='';
	/**
	 * Get calculator type and call necessary function.
	 */
	public function Calculate(){
		$calctype=JRequest::getInt('calctype');
		switch($calctype){
			case 1:
				return $this->AviaCalculate();
			case 2:
				return $this->ExpressCalculate();
			case 3:
				return $this->SeaCalculate();
			}
		return $this->AviaCalculate();
		}
	/**
	 * Авиадоставка (10-14 дней).
	 *
	 * Send email to administrator
	 */
	public function AviaSendMailAdmin($formdata){
		$text='';
		//Get Joomla mailer & config
		$mailer=JFactory::getMailer();
		$config=JFactory::getConfig();
		//Set sender
		if(version_compare(JVERSION, '3.0.0', 'ge')){
			$sender=array(
				$config->get('config.mailfrom'),
				$config->get('config.fromname'));
			}
		else{
			$sender=array(
				$config->getValue('config.mailfrom'),
				$config->getValue('config.fromname'));
			}
		if(!empty($sender)){
			$mailer->setSender($sender);
			}
		$mailer->addRecipient('vpupkin97@gmail.com');
		//Add text
		$mailer->isHTML(true);
		$mailer->setSubject(JText::_('COM_CALCULATOR_ADMIN_SUBJECT'));
		$mailer->setBody($text);

		if(!$mailer->send()){
			$this->errormessage='Admin email sending failed!';
			return 101;
			}
		return 0;
		}
	/**
	 * Авиадоставка (10-14 дней).
	 *
	 * Send email to customer. Append some files.
	 */
	public function AviaSendMailCustomer($formdata){
		$text='';
		//Get Joomla mailer & config
		$mailer=JFactory::getMailer();
		$config=JFactory::getConfig();
		//Set sender
		if(version_compare(JVERSION, '3.0.0', 'ge')){
			$sender=array(
				$config->get('config.mailfrom'),
				$config->get('config.fromname'));
			}
		else{
			$sender=array(
				$config->getValue('config.mailfrom'),
				$config->getValue('config.fromname'));
			}
		if(!empty($sender)){
			$mailer->setSender($sender);
			}
		$mailer->addRecipient('vpupkin97@gmail.com');
		//Add text
		$mailer->isHTML(true);
		$mailer->setSubject(JText::_('COM_CALCULATOR_ADMIN_SUBJECT'));
		$mailer->setBody($text);

		if(!$mailer->send()){
			$this->errormessage='Customer email sending failed!';
			return 102;
			}
		return 0;
		}
	/**
	 * Авиадоставка (10-14 дней).
	 */
	public function AviaCalculate(){
		$name=JRequest::getVar('name');
		if(empty($name)){
			$this->errormessage=JText::_('COM_CALCULATOR_ERROR_NAME_EMPTY');//'Name field is empty!';
			return 1;
			}
		$phone=JRequest::getVar('phone');
		if(empty($phone)){
			$this->errormessage=JText::_('COM_CALCULATOR_ERROR_PHONE_EMPTY');//'Phone field is empty!';
			return 2;
			}
		$email=JRequest::getVar('email');
		if(empty($email)){
			$this->errormessage=JText::_('COM_CALCULATOR_ERROR_EMAIL_EMPTY');//'Email field is empty!';
			return 3;
			}
		$mass=JRequest::getVar('mass');
		if(empty($mass)){
			$this->errormessage=JText::_('COM_CALCULATOR_ERROR_MASS_EMPTY');//'Mass field is empty!';
			return 4;
			}
		$volume=JRequest::getVar('volume');
		if(empty($volume)){
			$this->errormessage=JText::_('COM_CALCULATOR_ERROR_VOLUME_EMPTY');//'Volume field is empty!';
			return 5;
			}
		$count=JRequest::getVar('count');
		/*if(empty($count)){
			$this->errormessage=JText::_('COM_CALCULATOR_ERROR_COUNT_EMPTY');//'Count field is empty!';
			return 6;
			}*/
		$formdata=array('name'=>$name,'email'=>$email,'phone'=>$phone);
		$r=$this->AviaSendMailCustomer($formdata);
		if($r!=0){
			return $r;
			}
		$r=$this->AviaSendMailAdmin($formdata);
		if($r!=0){
			return $r;
			}
		$this->errormessage=JText::_('COM_CALCULATOR_AVIA_ALLDONE');//'All done!';
		return 0;
		}
	/**
	 * Экспресс доставка (3-4 дня).
	 */
	public function ExpressCalculate(){
		return 0;
		}
	/**
	 * Доставка морем.
	 */
	public function SeaCalculate(){
		return 0;
		}
	}

