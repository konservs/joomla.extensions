<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}
 
/**
 * HTML представление списка сообщений компонента Tours.
 */
class ToursViewCategories extends JViewLegacy
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
			$this->items = $this->get('Items');
			$this->pagination = $this->get('Pagination');
			$doc = JFactory::getDocument();
			$doc->addStylesheet('/media/com_exhibitions/css/admin.css');
			$doc->addScript('/media/com_exhibitions/js/admin.js');
			$this->addToolBar();
			parent::display($tpl);
			}
		catch (Exception $e){
			throw new Exception($e->getMessage());
			}
		}
	/**
	 * Устанавливает панель инструментов.
	 *
	 * @return void
	*/
	protected function addToolBar(){
		JToolBarHelper::title(JText::_('COM_TOURS_CATEGORIES'), 'tours-categories');
		JToolBarHelper::addNew('category.add');
		JToolBarHelper::editList('category.edit');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'category.delete');         
		}        
	}
