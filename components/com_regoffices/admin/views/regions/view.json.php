<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
if(!class_exists('JViewLegacy')){
	class_alias('JView', 'JViewLegacy');
	}
 
/**
 * HTML представление списка сообщений компонента RegOffices.
 */
class RegofficesViewRegions extends JViewLegacy{
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
			$this->jsonresult=new stdClass();
			$this->jsonresult->regions=array();

			$this->items = $this->get('Items');
			foreach($this->items as $itm){
				$xreg=new stdClass();
				$xreg->id=$itm->id;
				$xreg->name=$itm->getname();

				$this->jsonresult->regions[]=$xreg;
				}


			//parent::display($tpl);
			echo json_encode($this->jsonresult);
			}
		catch (Exception $e){
			throw new Exception($e->getMessage());
			}
		}
	}
