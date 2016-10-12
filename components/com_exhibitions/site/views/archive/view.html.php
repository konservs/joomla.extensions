<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

if(!class_exists(JViewLegacy))
	class_alias('JView', 'JViewLegacy');

class ExhibitionsViewArchive extends JViewLegacy{
	protected $msg;

	public function display($tpl = null){
		$this->exhibitions=$this->get('Archive');
		if (count($errors=$this->get('Errors'))){
			foreach ($errors as $error){
				JLog::add($error, JLog::ERROR, 'com_exhibitions');
				}
			}
		$doc=&JFactory::getDocument();
		$descr=JText::_('COM_EXHIBITIONS_ARCHIVE_METADESC');
		if((!empty($descr))&&($descr!='COM_EXHIBITIONS_ARCHIVE_METADESC'))
			$doc->setMetaData('description',$descr);
		$keyw=JText::_('COM_EXHIBITIONS_ARCHIVE_METAKEYW');
		if((!empty($keyw))&&($keyw!='COM_EXHIBITIONS_ARCHIVE_METAKEYW'))
			$doc->setMetaData('keywords',$keyw);
		parent::display($tpl);
		}
	}
?>