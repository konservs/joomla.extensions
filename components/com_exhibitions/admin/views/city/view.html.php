<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy'))
	class_alias('JView', 'JViewLegacy');
 
/**
 * HTML представление редактирования сообщения.
 */
class ExhibitionsViewCity extends JViewLegacy{
	/**
	* Сообщение.
	*
	* @var  object
	*/
	protected $item;
	/**
	* Объект формы.
	*
	* @var  array
	*/
	protected $form;
	/**
	* Отображает представление.
	*
	* @param   string  $tpl  Имя файла шаблона.
	*
	* @return  void
	*
	* @throws  Exception
	*/
	public function display($tpl = null){   
        	try{  
			//$this->form=$this->get('Form');
			$this->item=$this->get('Item');
			//var_export($this->item);die();
			// Устанавливаем панель инструментов.
			$this->addToolBar();
			//
			
			$doc = JFactory::getDocument();
			$doc->addStylesheet('/media/com_exhibitions/css/admin.css');
			$doc->addScript('/media/com_exhibitions/js/admin.js');
			/*
			//TODO disable jQuery in admin pannel;
			$doc->addScript('/media/com_exhibitions/js/jquery-1.11.1.min.js');
			$doc->addScript('/media/com_exhibitions/js/jquery-ui.min.js');
			$doc->addScript('/media/com_exhibitions/js/select2.min.js');
			$doc->addStylesheet('/media/com_exhibitions/css/select2.css');
			$doc->addStylesheet('/media/com_exhibitions/css/jquery-ui.min.css');
			*/
			//Отображаем представление.
			parent::display($tpl);
			}
        	catch (Exception $e){
			throw new Exception($e->getMessage());
			}
		}   
	/**
	* Устанавливает панель инструментов.
	*
	* @return  void
	*/
	protected function addToolBar(){
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$isNew = (empty($this->item)||($this->item->id == 0));
		JToolBarHelper::title($isNew ? JText::_('COM_EXHIBITIONS_CITY_NEW') : JText::_('COM_EXHIBITIONS_CITY_EDIT'), 'city');
		JToolBarHelper::apply('city.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('city.save');
		JToolBarHelper::cancel('city.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
		}
	}
