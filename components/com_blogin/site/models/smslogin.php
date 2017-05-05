<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class BloginModelSmslogin extends JModelItem{
	public $codeSent = false;
	/**
	 *
	 */
	public function sendSMS(){
		return true;
		}

	public function RandomString($count){
		$firstCharacters = '123456789';
		$characters = '0123456789';
		//
		$randstring = $firstCharacters[rand(0, strlen($firstCharacters))];
		for ($i = 1; $i < $count; $i++) {
			$randstring .= $characters[rand(0, strlen($characters))];
			}
		return $randstring;
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
	public function sendCode($phonenum2, $smscode){
		$sms = TSMSRouter::getInstance();
		$sms->destination = '+'.$phonenum2;
		$sms->text = 'Ваш код: '.$smscode;

		$r = $sms->tryConnect();
		if(!$r){
			JError::raiseWarning('TURBOSMS_CONNECT', 'Could not connect! TurboSMS error: '.$sms->getLog());
			return false;
			}
		$r = $sms->SendSimple();
		if(!$r){
			JError::raiseWarning('PHONE_DIGITS', 'Could not send! TurboSMS error 1: '.$sms->getLog());
			return false;
			}
		if($r->st!='Сообщения успешно отправлены'){
			JError::raiseWarning('PHONE_DIGITS', 'Could not send! TurboSMS error 2: '.$r->st);
			return false;
			}
		return true;
		}
	/**
	 *
	 */
	public function loginRegister($phonenum){
		$phonenum2 = $this->getPhoneCanonical($phonenum);
		if(strlen($phonenum2)!=12){
			JError::raiseWarning('PHONE_DIGITS', 'Wrong digits count!');
			return false;
			}
		jimport('brilliant.turbosms');
		//Maybe, we already know this phone?
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id', 'userid', 'phone', 'codesent', 'codesms', 'created', 'modified')));
		$query->from($db->quoteName('#__blogin_phones'));
		$query->where($db->quoteName('phone') . ' = '. $db->quote($phonenum2));
		$db->setQuery($query);
		$results = $db->loadObjectList();

		if(!empty($results)){
			$result = reset($results);
			$code = $result->codesms;
			$codesent = empty($result->codesent)?NULL:new DateTime($result->codesent);
			$min5before = new DateTime();
			$min5before->sub(new DateInterval('P1M'));
			if((empty($codesent))||($codesent < $min5before)){
				$now = new DateTime();
				$code = $this->RandomString(4);
				if(!$this->sendCode($phonenum2,$code)){
					return false;
					}
				$result->codesms = $code;
				$result->codesent = $now->format('Y-m-d h:i:s');
				// Fields to update.
				$fields = array(
				    $db->quoteName('codesent') . ' = ' . $db->quote($result->codesent),
				    $db->quoteName('codesms') . ' = ' . $db->quote($result->codesms)
				); 
				// Conditions for which records should be updated.
				$conditions = array($db->quoteName('phone') . ' = '.$db->quote($phonenum2));
				$query->update($db->quoteName('#__blogin_phones'))
					->set($fields)
					->where($conditions);
				$db->setQuery($query); 
				$result = $db->execute();
				if(!$result){
					JError::raiseWarning('PHONE_DIGITS', 'Database error');
					return false;
					}
				$this->codeSent = true;
				} else {
				//We already sent the code, just need to confirm!
				$this->codeSent = true;
				}
			} else {
			//We don't know this phone!
			$code = $this->RandomString(4);
			if(!$this->sendCode($phonenum2,$code)){
				return false;
				}
			$now = new DateTime();
			$result = new stdClass();
			$result->phone = $phonenum2;
			$result->codesms = $code;
			$result->codesent = $now->format('Y-m-d h:i:s');
			$result = JFactory::getDbo()->insertObject('#__blogin_phones', $result);
			if(!$result){
				JError::raiseWarning('PHONE_DIGITS', 'Database Insert error');
				return false;
				}
			$this->codeSent = true;
			}
		return true;
		}

	}
