<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}
 
/**
 * HTML представление списка сообщений компонента Tours.
 */
class ToursViewOrders extends JViewLegacy{
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
		JToolBarHelper::title(JText::_('COM_TOURS_ORDERS'), 'tours-cities');
		//JToolBarHelper::addNew('order.add');
		//JToolBarHelper::editList('order.edit');
		//JToolBarHelper::divider();
		//JToolBarHelper::deleteList('', 'order.delete');         
		}        
	}
