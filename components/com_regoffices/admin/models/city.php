<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modeladmin Joomla.
jimport('joomla.application.component.modeladmin');

/**
 * Модель City.
 */
class RegofficesModelCity extends JModelAdmin{
	public $record_id;
	/**
	 * Возвращает ссылку на объект таблицы, всегда его создавая.
	 *
	 * @param   string  $type    Тип таблицы для подключения.
	 * @param   string  $prefix  Префикс класса таблицы. Необязателен.
	 * @param   array   $config  Конфигурационный массив. Необязателен.
	 *
	 * @return  JTable  Объект JTable.
	 */
	public function getTable($type = 'Cities', $prefix = 'RegofficesTable', $config = array()){        
		return JTable::getInstance($type, $prefix, $config);
		}
	/**
	 * Метод для получения данных из POST запроса
	 */ 
	public function getPostData(){
		//Get form data...
		$jinput=JFactory::getApplication()->input;
		$data=$jinput->get('city', array(),'array');
		return $data;
		}
	/**
	 * Метод для получения формы.
	 *
	 * @param   array    $data      Данные для формы.
	 * @param   boolean  $loadData  True, если форма загружает свои данные (по умолчанию), false если нет.
	 *
	 * @return  mixed  Объект JForm в случае успеха, в противном случае false.
	 */
	public function getForm($data = array(), $loadData = true){
		// Получаем форму.
		//$form = $this->loadForm($this->option . '.exhibitions', 'exhibitions', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)){
			return false;
			}
		return $form;
		}
	/**
	 * Получаем выставку.
	 *
	 * @param   int  $id  Id сообщения.
	 *
	 * @return  string  Сообщение, которое отображается пользователю.
	 */
	public function getItem($id = null){   
		jimport('brilliant_regoffices.cities');
		$bcity=BRegofficesCities::getInstance();
		// Если id не установлено, то получаем его из состояния.
		$id = (!empty($id)) ? $id : (int) $this->getState('city.id');
		return $bcity->item_get($id);
		}
	/**
	 *
	 */
	public function getFcountries(){
		$res=array();
		jimport('brilliant_regoffices.countries');
		$brcntr=BRegofficesCountries::getInstance();

		$countries=$brcntr->items_get_all();
		foreach($countries as $cnt){
			$fcountry=new stdClass();
			$fcountry->id=$cnt->id;
			$fcountry->name=$cnt->getname();
			$res[]=$fcountry;
			}
		return $res;
		}
	/**
	 * Метод для получения данных, которые должны быть загружены в форму.
	 *
	 * @return boolean  Данные для формы.
	 */
	public function save($data){
		$this->record_id=JRequest::getVar('id');

		$lang_list=JLanguageHelper::getLanguages();
		//
		jimport('brilliant_regoffices.cities');
		jimport('brilliant_regoffices.city');
		$brcities=BRegofficesCities::getInstance();
		if($this->record_id>0){
			$city=$brcities->item_get($this->record_id);
			}else{
			$city=NULL;
			}
		if(empty($city)){
			$city=new BRegofficesCity();
			}
		//set exhibition values;
		//var_dump($data);die();
		$city->status=$data['status'];
		$city->country=$data['country'];
		$city->region=$data['region'];

		foreach($lang_list as $language) {
			$lang_code= $language->lang_code;
			$sef=$language->sef;
			$city->setlangvar('name',$data['name_'.$lang_code],$lang_code);
			$city->setlangvar('alias',$data['alias_'.$lang_code],$lang_code);
			$city->setlangvar('description',$data['description_'.$lang_code],$lang_code);
			$city->setlangvar('h1',$data['h1_'.$lang_code],$lang_code);
			$city->setlangvar('title',$data['title_'.$lang_code],$lang_code);
			$city->setlangvar('metadesc',$data['metadesc_'.$lang_code],$lang_code);
			$city->setlangvar('metakeyw',$data['metakeyw_'.$lang_code],$lang_code);
			$city->setlangvar('metarobots',(int)$data['metarobots_'.$lang_code],$lang_code);
			}
		//var_dump($city);die();
		//Save
		if(!$city->savetodb()){                     
			return false;
			}
		$this->record_id=$city->id;
		return true;
		}
	/**
	 * Deletes 
	 *
	 * @return boolean.
	 */       
	public function delete($cid){
		if(count($cid)){
			$db=JFactory::getDBO();
			$cids=implode( ',', $cid);
			$qr1='DELETE FROM #__regoffices_cities_lang '.' WHERE city IN ('.$cids.')';
			$qr2='DELETE FROM #__regoffices_cities '.' WHERE id IN ('.$cids.')';

			$db->setQuery('START TRANSACTION');
			if(!$db->query()){
				$db->setQuery('ROLLBACK');
				return false;
				}
			$db->setQuery($qr1);
			if (!$db->query()){
				$db->setQuery('ROLLBACK');               
				return false;
				}
			$db->setQuery($qr2);
			if (!$db->query()){
				$db->setQuery('ROLLBACK');               
				return false;
				}
			$db->setQuery('COMMIT');
			if (!$db->query()){
				$db->setQuery('ROLLBACK');               
				return false;
				}
			return true;
			}
		}
	/**
	 * Метод для получения данных, которые должны быть загружены в форму.
	 *
	 * @return  mixed  Данные для формы.
	 */
	protected function loadFormData(){       
		die('loadFormData...');
		//Проверка сессии на наличие ранее введеных в форму данных.
		$data = JFactory::getApplication()->getUserState($this->option . '.edit.exhibition.data', array());
		if (empty($data)){            
			$data = $this->getItem();
			}
		return $data;
		}
	}
