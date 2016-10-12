<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class RegofficesModelCountry extends JModelItem{
	/**
	 *
	 */
	protected function populateState(){
		$app = JFactory::getApplication('site');
		$pk = JRequest::getInt('id');
		$this->setState('country.id', $pk);
		//$offset = JRequest::getUInt('limitstart');
		//$this->setState('list.offset', $offset);
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
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('country.id');
		jimport('brilliant_regoffices.cities');
		$brc=BRegofficesCities::getInstance();
		return $brc->items_filter(array('country'=>$pk));
		}
	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function &getItem($pk = null){
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('country.id');

		try{
			jimport('brilliant_regoffices.countries');
			$brc=BRegofficesCountries::getInstance();
			$country=$brc->item_get($pk);

			if(empty($country)) {
				return JError::raiseError(404, 'Country not found!'); //JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND')
				}
			return $country;
			}
		catch (RuntimeException $e){
			$this->setError($e->getMessage());
			return false;
			}/**/

		}
	}
