<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.controller');
 
/**
 * 
 */
class BloginController extends JControllerLegacy{
	/**
	 * @param   boolean  $cachable
	 * @param   array    $urlparams
	 * @return  void
	 */
	public function display($cachable = false, $urlparams = array()) {
		require_once JPATH_COMPONENT . '/helpers/blogin.php';
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->getCmd('view', 'home'));
		parent::display($cachable);
		}
	}
