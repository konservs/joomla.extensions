<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;

 
// Подключаем библиотеку представления Joomla.
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy'))
	class_alias('JView', 'JViewLegacy');
 
/**
 * HTML представление списка сообщений компонента Exhibitions.
 */
class ExhibitionsViewExhibitions extends JViewLegacy
{
    /**
     * Сообщения.
     *
     * @var  array 
     */
    protected $items;
 
    /**
     * Постраничная навигация.
     *
     * @var  object
     */
    protected $pagination;
 
    /**
     * Show exhibitions list
     *
     * @param   string  $tpl 
     *
     * @return  void
     *
     * @throws  Exception
     */
	public function display($tpl = null){
		try{
			//Получаем данные из модели.
			$this->items = $this->get('Items');
			//Получаем объект постраничной навигации.
			$this->pagination = $this->get('Pagination');
			//
			$doc = JFactory::getDocument();
			$doc->addStylesheet('/media/com_exhibitions/css/admin.css');
			$doc->addScript('/media/com_exhibitions/js/admin.js');
			// Устанавливаем панель инструментов.
			$this->addToolBar();
			// Отображаем представление.
			parent::display($tpl);
			}
		catch (Exception $e){
			throw new Exception($e->getMessage());
			}
		}
	/**
	 * Add toolbar
	 *
	 * @return void
	 */
	protected function addToolBar(){
		JToolBarHelper::title(JText::_('COM_EXHIBITIONS_MANAGER_EXHIBITIONS'), 'exhibitions');
		JToolBarHelper::addNew('exhibition.add');
		JToolBarHelper::editList('exhibition.edit');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'exhibitions.delete');         
		}        
	}
