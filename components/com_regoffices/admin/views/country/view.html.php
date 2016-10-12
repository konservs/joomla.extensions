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
class RegofficesViewCountry extends JViewLegacy{
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
		JToolBarHelper::title($isNew ? JText::_('COM_REGOFFICES_COUNTRY_NEW') : JText::_('COM_REGOFFICES_COUNTRY_EDIT'), 'COUNTRY');
		JToolBarHelper::apply('country.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('country.save');
		JToolBarHelper::cancel('country.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
		}
	}
