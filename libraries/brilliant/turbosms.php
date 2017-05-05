<?php
//------------------------------------------------------------
// mail api
//
// Author: Andrii Biriev
//------------------------------------------------------------

class TSMSRouter{
	private $client;
	private $log='';
	private $connected;
	public $username;
	public $password;
	public $router;
	private static $instance;
	//================================================================================
	//
	//================================================================================
	public function __construct(){
		$this->connected=false;
		//TODO: fill these values!
		$this->username='';//
		$this->password='';
		$this->router='';
		}
	//================================================================================
	// Returns the global Session object, only creating it
	// if it doesn't already exist.
	//================================================================================
	public static function getInstance(){
		if (!is_object(self::$instance))
			self::$instance=new TSMSRouter();
		return self::$instance;
		}
	//================================================================================
	//
	//================================================================================
	public function TryConnect(){
		if($this->connected)return TRUE;
		$this->log.='Connecting...'.PHP_EOL;
		$this->client=new SoapClient('http://turbosms.in.ua/api/wsdl.html');
		$auth = Array ( 
			'login' => $this->username, 
			'password' => $this->password
			);
		$this->log.='Logging in...'.PHP_EOL;
		$result=$this->client->Auth($auth); 
		$this->log.='Logon result: '.$result->AuthResult.PHP_EOL;
//echo($this->log);
		return TRUE;
		}
	public function CreditBalance(){
		$result=$this->client->GetCreditBalance(); 
		return (float)$result->GetCreditBalanceResult;
		}
	/**
	 *
	 */
	public function SendSimple(){
		if(empty($this->destination)){
			$this->log.='Destination number is empty!'.PHP_EOL;
			return FALSE;
			}
		if(empty($this->text)){
			$this->log.='SMS text is empty!'.PHP_EOL;
			return FALSE;
			}

		if(substr($this->destination,0,4)=='+380'){
			//All ok
			}
		elseif(substr($this->destination,0,2)=='80'){
			//All ok
			$this->destination='+3'.$this->destination;
			}
		elseif(substr($this->destination,0,1)=='0'){
			//All ok
			$this->destination='+38'.$this->destination;
			}else{
			$this->destination='+380'.$this->destination;
			}

		//$db=BMySQL::getInstanceAndConnect();
		//if(empty($db))
		//	return FALSE;
		if(empty($this->client)){
			$this->log.='Client is not connected!'.PHP_EOL;
			return FALSE;
			}
		$sms=Array(
			'sender'=>$this->router,
			'destination'=>$this->destination,
			'text'=>$this->text);
		$result=$this->client->SendSMS($sms);
		$this->log.='Send SMS Result:'.$result->SendSMSResult->ResultArray[0].PHP_EOL;
		$sms_id[0]=$result->SendSMSResult->ResultArray[1];
		$this->log.='Send SMS ID[0]:'.$sms_id[0].PHP_EOL;
		$status=new stdClass;
		//$status->str=','.$db->escape_string($this->log).','.$db->escape_string($sms_id[0]);
		$status->statid=($sms_id[0]!='')?1:2;
		$status->st=$result->SendSMSResult->ResultArray[0];
		return $status;

		}
	public function GetStatus($sms_id){
		$sms = Array ('MessageId' => $sms_id); 
		$status = $this->client->GetMessageStatus ($sms); 
		return $status;
		}
	public function Send(){
		if((empty($this->destination))||(empty($this->text))) return FALSE;
		$db=BMySQL::getInstanceAndConnect();
		if(empty($db))
			return FALSE;
		$db->start_transaction();
		$qr='INSERT INTO `mail_general` (`ma_type`,`ma_sender`,`ma_dt`,`ma_text`) VALUES ('.
			'2,'.my_UID().',NOW(),'.$db->escape_string($this->text).')';
		$res=$db->Query($qr);
		if(!$res){
			if(DEBUG_MODE){
				echo('query:'.$qr.'<br>');
				echo('err:'.$db->lasterror().'<br>');
				die();
				}
			return false;
			}
		$ma_id=$db->insert_id();

		$sms=Array(
			'sender'=>$this->router,
			'destination'=>$this->destination,
			'text'=>$this->text);
		$result=$this->client->SendSMS($sms);
		$this->log.='Send SMS Result:'.$result->SendSMSResult->ResultArray[0].PHP_EOL;

		$sms_id[0]=$result->SendSMSResult->ResultArray[1];
		$this->log.='Send SMS ID[0]:'.$sms_id[0].PHP_EOL;
		//
		$qr='INSERT INTO `mail_turbosms` (`ma_id`,`ma_phone`,`ma_result`,`ma_turbo_id`) VALUES ('.
			$ma_id.','.
			$db->escape_string($this->destination).','.
			$db->escape_string($this->log).','.
			$db->escape_string($sms_id[0]).')';
		$res=$db->Query($qr);
		if(!$res){
			if(DEBUG_MODE){
				echo('query:'.$qr.'<br>');
				echo('err:'.$db->lasterror().'<br>');
				die();
				}
			return false;
			}
		$db->commit();
		return TRUE;
		}
	/**
	 *
	 */
	public function getLog(){
		return $this->log;
		}
	}
