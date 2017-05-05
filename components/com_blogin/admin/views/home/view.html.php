<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}
 
/**
 *
 */
class BloginViewHome extends JViewLegacy{
	/**
	 *
	 */
	public function display($tpl = null){
		if($this->getLayout() !== 'modal'){
			$this->addToolbar();
			BloginHelper::addSubmenu('home');
			$this->sidebar='';
			if(class_exists('JHtmlSidebar')){
				$this->sidebar = JHtmlSidebar::render();
				}
			}
		parent::display($tpl);
		}
	/**
	 * Add toolbar
	 *
	 * @return void
	*/
	protected function addToolBar(){
		JToolBarHelper::title('Blogin', 'regoffices-countries');
		//JToolBarHelper::divider();
		JToolBarHelper::preferences('com_blogin');
		}        
	}
