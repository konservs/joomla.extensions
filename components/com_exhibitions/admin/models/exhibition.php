<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку modeladmin Joomla.
jimport('joomla.application.component.modeladmin');

/**
 * Модель Exhibition.
 */
class ExhibitionsModelExhibition extends JModelAdmin{
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
    public function getTable($type = 'Exhibitions', $prefix = 'ExhibitionsTable', $config = array())
    {        
        return JTable::getInstance($type, $prefix, $config);
    }


    /**
     * Метод для получения данных из POST запроса
     */ 
    public function getPostData(){
        //Get form data...
        $jinput=JFactory::getApplication()->input;
        $data=$jinput->get('exhibition', array(),'array');
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
        jimport('brilliant_exhibitions.exhibitions');
        $exhibition = BExhibitions::getInstance();
        // Если id не установлено, то получаем его из состояния.
        $id = (!empty($id)) ? $id : (int) $this->getState('exhibition.id');
        return $exhibition->exhibition_get($id);
        }

    /**
    * Get cities list
    *
    *
    * @return  array  Cities list
    */
    public function getCities(){   
        jimport('brilliant_exhibitions.cities');
        $bcities = BCities::getInstance();
        return $bcities->cities_get_all();
        }

    /**
    * Get data from array
    *
    *
    * @return  array form
    */
/*    protected function getFormData(){
die('getFormData...');
        $jinput = JFactory::getApplication()->input;
        $form_data = $jinput->getArray($_POST);
        return $form_data;
        }*/

    /**
     * Метод для получения данных, которые должны быть загружены в форму.
     *
     * @return boolean  Данные для формы.
     */
    public function save($data){
        jimport('brilliant_exhibitions.language');
        $languages = BLanguages::getInstance();
        $lang_list = $languages->languages_get_all();
        
        $this->record_id=JRequest::getVar('exb_id');//Kostyl
        //var_dump($data); die();
        jimport('brilliant_exhibitions.exhibitions');
        $bexhibitions = BExhibitions::getInstance();
        if($this->record_id>0){
            $e=$bexhibitions->exhibition_get($this->record_id);
            }else{
            $e=NULL;
            }
        if(empty($e)){
            $e=new BExhibitionsExhibition();
            }
        //set exhibition values;
        //var_dump($data);die();
        foreach ($lang_list as $language) {
            $lang_code= $language->lang_code;
            $sef=$language->sef;
            $exh_name=$data['name_'.$sef];
            $e->setName($exh_name,$lang_code);           
            $text=explode('<hr id="system-readmore" />',$data['fulltext_'.$sef]);
            $e->setIntrotext($text[0],$lang_code);
            //$fulltext=$text[0].$text[1];                        
            $e->setFulltext($data['fulltext_'.$sef],$lang_code);
            $e->setMetaDesc($data['metadesc_'.$sef],$lang_code);
            $e->setMetaKeyw($data['metakeyw_'.$sef],$lang_code);
            $alias=$data['alias_'.$sef];
            if(empty($alias)){
                $lang =JFactory::getLanguage();    
                $alias=$lang->transliterate($exh_name);  
                $alias=str_replace(' ', '-', $alias); 
                $e->setAlias($alias,$lang_code);
                }else{
                $e->setAlias($alias,$lang_code);
                }
            }
        //$e->($data['industries']);
        $e->setStartdate($data['date_start']);
        $e->setEnddate($data['date_end']);
        $e->setStartdate($data['date_start']);
        $e->setEnddate($data['date_end']);
        $e->setIntroimg($data['introimg']);
        $e->setFullimg($data['fullimg']);
        $e->setCity($data['city']);  
        //var_dump($data);die();      
        //Save
        if(!$e->savetodb()){
            return false;
            }
        $this->record_id=$e->id;
        //echo('id = '.$this->record_id);
        //var_dump($data['industries']);die();
        //die('e->id = '.$e->id);
        $e->updateIndustries($data['industries']);
        return true;
        }

    /**
    * Deletes 
    *
    * @return boolean.
    */       
    public function delete($cid){
        if (count($cid)) {
            $db=JFactory::getDBO();
            $cids=implode( ',', $cid );
            $qr1='DELETE FROM #__exhibitions '.' WHERE exb_id IN ('.$cids.')';
            $qr2='DELETE FROM #__exhibitions_lang '.' WHERE exbl_exhibition IN ('.$cids.')';
            $qr3='DELETE FROM #__exhibitions_industries '.' WHERE exbi_exhibition IN ('.$cids.')';
            
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
            $db->setQuery($qr3);
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