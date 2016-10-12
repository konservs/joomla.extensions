<?php
/**
 * Controller of single exhibition.
 *
 * @author Andrii Biriev, a@konservs.com
 * @author Maksym Kutyshenko, mkutyshenko@gmail.com
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');
/**
 * Single City controller
 *
 * @author Andrii Biriev, a@konservs.com
 * @author Maksym Kutyshenko, mkutyshenko@gmail.com
 */
class ExhibitionsControllerCity extends JControllerForm{
	/**
	 * Handle the save task
	 *
	 * @return boolean true if ok, false in other cases. 
	 *
	 * @author Andrii Biriev, a@konservs.com
	 * @author Maksym Kutyshenko, mkutyshenko@gmail.com
	 */
	public function save($key = null, $urlVar = null){
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$model = $this->getModel();
		$task = $this->getTask();
		//Get form data...
		$jinput=JFactory::getApplication()->input;
		$data=$jinput->get('city', array(),'array');
		//var_dump($data);die();
		//TODO get record ID;
		$urlVar='id';
		if(!$model->save($data)){
			$app->enqueueMessage('Could not save data form! form data='.var_export($data,true), 'error');
			//Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item.$this->getRedirectToItemAppend($model->record_id, $urlVar), false));
			return false;
			}
		//Redirect the user and adjust session state based on the chosen task.
		switch ($task){
			case 'apply':
				// Redirect back to the edit screen.
				$redirect_url=JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item.$this->getRedirectToItemAppend($model->record_id, $urlVar), false);
				$this->setRedirect($redirect_url);
				break;
			case 'save2new':
				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_item.$this->getRedirectToItemAppend(null, $urlVar), false));
				break;
			default:
				// Redirect to the list screen.
				$redirect_url=JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list.$this->getRedirectToListAppend(), false);
				//var_dump($redirect_url);die();
				$this->setRedirect($redirect_url);
				break;
			}
		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $data);
		//All done!
		return true;
		}

	public function delete(){			
		$app   = JFactory::getApplication();
		$cid = JRequest::getVar('cid', array(), '', 'array');
		JArrayHelper::toInteger($cid);
		$model=$this->getModel();		
		if(!$model->delete($cid)){
			$app->enqueueMessage('Could not delete data data='.var_export($cid,true), 'error');
			$this->setRedirect('index.php?option='.$this->option.'&view='.$this->view_list);
			echo "<script> alert('".$db->getErrorMsg(true)."'); window.history.go(-1); </script>\n";
			return false;
			}
		$this->setRedirect('index.php?option='.$this->option.'&view='.$this->view_list);
		$app->enqueueMessage(JText::_('COM_EXHIBITIONS_CITY_DELETED').' id: '.var_export(implode(', ', $cid),true), 'message');	
		return true;	
		}		
	}
