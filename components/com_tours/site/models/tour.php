<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class ExhibitionsModelExhibition extends JModelItem{
	protected function populateState(){
		$app = JFactory::getApplication('site');
		$pk = JRequest::getInt('id');
		$this->setState('exhibition.id', $pk);
		$offset = JRequest::getUInt('limitstart');
		$this->setState('list.offset', $offset);
		}
	public function &getItem($pk = null){
		$pk=(!empty($pk))?$pk:(int)$this->getState('exhibition.id');
		jimport('brilliant_exhibitions.exhibitions');
		$exhibitions=BExhibitions::getInstance();
		$exhb=$exhibitions->exhibition_get($pk);
		if(empty($exhb)) {
			return JError::raiseError(404, 'Exhibition not found!'); //JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND')
			}
                return $exhb;

		}
	/*public function getExhibition(){
		jimport('brilliant_exhibitions.exhibitions');
		$exhibitions=BExhibitions::getInstance();
		return $exhibitions->exhibition_get(54);
		}*/
	}
