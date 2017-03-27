<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}
 
/**
 * HTML представление списка сообщений компонента Exhibitions.
 */
class RegofficesViewCountries extends JViewLegacy{
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
			//
			$doc->addStylesheet('/media/com_regoffices/css/admin.css');
			$doc->addScript('/media/com_regoffices/js/admin.js');

			if($this->getLayout() !== 'modal'){
				$this->addToolbar();
				RegofficesHelper::addSubmenu('countries');
				$this->sidebar='';
				if(class_exists('JHtmlSidebar')){
					$this->sidebar = JHtmlSidebar::render();
					}
				}
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
		JToolBarHelper::title(JText::_('COM_REGOFFICES_COUNTRIES_HEADING'), 'regoffices-countries');
		JToolBarHelper::addNew('country.add');
		JToolBarHelper::editList('country.edit');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'country.delete');         
		}        
	}
