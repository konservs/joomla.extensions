<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modeladmin');

/**
 * Модель Country.
 */
class RegofficesModelCountry extends JModelAdmin{
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
	public function getTable($type = 'countries', $prefix = 'RegofficesTable', $config = array()){        
		return JTable::getInstance($type, $prefix, $config);
		}
	/**
	 * Метод для получения данных из POST запроса
	 */ 
	public function getPostData(){
		//Get form data...
		$jinput=JFactory::getApplication()->input;
		$data=$jinput->get('country', array(),'array');
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
		jimport('brilliant_regoffices.countries');
		$bcountry=BRegofficesCountries::getInstance();
		// Если id не установлено, то получаем его из состояния.
		$id = (!empty($id)) ? $id : (int) $this->getState('country.id');
		return $bcountry->item_get($id);
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
		jimport('brilliant_regoffices.countries');
		jimport('brilliant_regoffices.country');
		$brcountries=BRegofficesCountries::getInstance();
		if($this->record_id>0){
			$country=$brcountries->item_get($this->record_id);
			}else{
			$country=NULL;
			}
		if(empty($country)){
			$country=new BRegofficesCountry();
			}
		//set exhibition values;
		$country->status=$data['status'];
		$country->iso=$data['iso'];

		foreach($lang_list as $language) {
			$lang_code= $language->lang_code;
			$sef=$language->sef;
			$country->setlangvar('name',$data['name_'.$lang_code],$lang_code);
			$country->setlangvar('alias',$data['alias_'.$lang_code],$lang_code);
			$country->setlangvar('description',$data['description_'.$lang_code],$lang_code);
			$country->setlangvar('h1',$data['h1_'.$lang_code],$lang_code);
			$country->setlangvar('title',$data['title_'.$lang_code],$lang_code);
			$country->setlangvar('metadesc',$data['metadesc_'.$lang_code],$lang_code);
			$country->setlangvar('metakeyw',$data['metakeyw_'.$lang_code],$lang_code);
			$country->setlangvar('metarobots',$data['metarobots_'.$lang_code],$lang_code);
			}
		//var_dump($country);die();
		//Save
		if(!$country->savetodb()){                     
			return false;
			}
		$this->record_id=$country->id;
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
			$qr1='DELETE FROM #__regoffices_countries_lang '.' WHERE country IN ('.$cids.')';
			$qr2='DELETE FROM #__regoffices_countries '.' WHERE id IN ('.$cids.')';

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
		}
	}
