<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modeladmin Joomla.
jimport('joomla.application.component.modeladmin');

/**
 * Модель City.
 */
class ToursModelCity extends JModelAdmin{
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
    public function getTable($type = 'Cities', $prefix = 'ToursTable', $config = array())
    {        
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
    public function getForm($data = array(), $loadData = true)
    {
        // Получаем форму.
        //$form = $this->loadForm($this->option . '.exhibitions', 'exhibitions', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
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
        jimport('brilliant_tours.cities');
        $city=BCities::getInstance();
        // Если id не установлено, то получаем его из состояния.
        $id = (!empty($id)) ? $id : (int) $this->getState('city.id');
        return $city->city_get($id);
        }

    /**
     * Метод для получения данных, которые должны быть загружены в форму.
     *
     * @return boolean  Данные для формы.
     */
    public function save($data){
        die('Models -> city: Saving...');

        $this->record_id=JRequest::getVar('ct_id');//Kostyl
        jimport('brilliant_exhibitions.language');
        $languages = BLanguages::getInstance();
        $lang_list = $languages->languages_get_all();       
     
        jimport('brilliant_exhibitions.cities');
        $cities=BCities::getInstance();
        if($this->record_id>0){
            $city=$cities->city_get($this->record_id);
            }else{
            $city=NULL;
            }
        if(empty($city)){
            $city=new BCity();
            }
        //set exhibition values;
        //var_dump($data);die();
        foreach ($lang_list as $language) {
            $lang_code= $language->lang_code;
            $sef=$language->sef;
            $city_name=$data['name_'.$sef];
            $city->setName($city_name,$lang_code);  
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
        die('Models -> city: deleting...');
        if (count($cid)) {
            $db=JFactory::getDBO();
            $cids=implode( ',', $cid);
            $qr1='DELETE FROM #__cities '.' WHERE ct_id IN ('.$cids.')';
            $qr2='DELETE FROM #__cities_lang '.' WHERE ctl_city IN ('.$cids.')';
            
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
    protected function loadFormData()
    {       
die('loadFormData...');
        // Проверка сессии на наличие ранее введеных в форму данных.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.exhibition.data', array());
 
        if (empty($data))
        {            
            $data = $this->getItem();
        }


        return $data;
    }
}