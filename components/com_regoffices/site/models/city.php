<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class RegofficesModelCity extends JModelItem{
	/**
	 *
	 */
	protected function populateState(){
		$app = JFactory::getApplication('site');
		$pk = JRequest::getInt('id');
		$this->setState('city.id', $pk);
		return true;
		}
	/**
	 *
	 */
	public function getCountries(){
		jimport('brilliant_regoffices.countries');
		$brc=BRegofficesCountries::getInstance();
		return $brc->items_get_all();
		}
	/**
	 *
	 */
	public function getCities(){
		$pk=(!empty($pk)) ? $pk : (int) $this->getState('city.id');
		jimport('brilliant_regoffices.cities');
		$brc=BRegofficesCities::getInstance();
		$city=$brc->item_get($pk);
		if(empty($city)){
			return array();
			}
		return $brc->items_filter(array('country'=>$city->country));
		}
	/**
	 *
	 */
	public function getOffices(){
		$pk=(!empty($pk)) ? $pk : (int) $this->getState('city.id');
		jimport('brilliant_regoffices.offices');
		$bro=BRegofficesOffices::getInstance();
		$offices=$bro->items_filter(array('city'=>$pk));
		return $offices;
		}

	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function &getItem($pk = null){
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('city.id');

		try{
			jimport('brilliant_regoffices.cities');
			$brcc=BRegofficesCities::getInstance();
			$city=$brcc->item_get($pk);

			if(empty($city)) {
				return JError::raiseError(404, 'City not found (id='.$pk.')!'); //JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND')
				}
			return $city;
			}
		catch (RuntimeException $e){
			$this->setError($e->getMessage());
			return false;
			}/**/

		}
	}
