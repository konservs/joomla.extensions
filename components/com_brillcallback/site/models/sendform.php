<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class BrillcallbackModelSendform extends JModelItem{
	//protected $exhibitions;
	public function SendMail(){
		$name =JRequest::getVar('name');
		$phone=JRequest::getVar('phone');
		$email=JRequest::getVar('email');
		$comment=JRequest::getVar('comment');
		//$text=JText::sprintf('COM_BRILLCALLBACK_EMAIL',$name,$phone,#);
		$text='<p>'.JText::_('COM_BRILLCALLBACK_INTRO').'</p>'.
			'<p><b>'.JText::_('COM_BRILLCALLBACK_NAME').'</b>: '.$name.'</p>'.
			'<p><b>'.JText::_('COM_BRILLCALLBACK_PHONE').'</b>:'.$phone.'</p>'.
			'<p><b>'.JText::_('COM_BRILLCALLBACK_EMAIL').'</b>: <a href="mailto:'.$email.'">'.$email.'</a></p>'.
			'<p><b>'.JText::_('COM_BRILLCALLBACK_COMMENT').'</b>:'.$comment.'</p>';
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
		$mailer->setSubject(JText::_('COM_BRILLCALLBACK_SUBJECT'));
		$mailer->setBody($text);

		if(!$mailer->send()){
			return false;
			}
		return true;
		}
	}
