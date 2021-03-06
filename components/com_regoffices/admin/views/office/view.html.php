<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}
 
/**
 * HTML представление редактирования сообщения.
 */
class RegofficesViewOffice extends JViewLegacy{
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
			$this->fcountries=$this->get('Fcountries');
			//var_export($this->item);die();
			// Устанавливаем панель инструментов.
			$this->addToolBar();
			//
			$doc = JFactory::getDocument();

			if(version_compare(JVERSION, '3.0.0', 'ge')){
				JHtml::_('jquery.framework', false);
				}else{
				if(!JFactory::getApplication()->get('jquery')){
					JFactory::getApplication()->set('jquery',true);
					$doc = JFactory::getDocument();
					$doc->addScript(JUri::root().'media/com_regoffices/js/jquery.js');
					}
				}
			$doc->addStylesheet(JUri::root().'media/com_regoffices/css/admin.css');
			$doc->addScript(JUri::root().'media/com_regoffices/js/admin.js');
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
		JToolBarHelper::title($isNew ? JText::_('COM_REGOFFICES_OFFICE_NEW') : JText::_('COM_REGOFFICES_OFFICE_EDIT'), 'office');
		JToolBarHelper::apply('office.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('office.save');
		JToolBarHelper::cancel('office.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
		}
	}
