<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy'))
	class_alias('JView', 'JViewLegacy');
 
/**
 * HTML представление списка сообщений компонента Regoffices.
 */
class RegofficesViewCities extends JViewLegacy
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
     * Show cities list
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
				RegofficesHelper::addSubmenu('cities');
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
		JToolBarHelper::title(JText::_('COM_REGOFFICES_CITIES'), 'regoffices-cities');
		JToolBarHelper::addNew('city.add');
		JToolBarHelper::editList('city.edit');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'city.delete');         
		}        
	}
