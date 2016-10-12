<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class RegofficesModelRegion extends JModelItem{
	/**
	 *
	 */
	protected function populateState(){
		$app = JFactory::getApplication('site');
		$pk = JRequest::getInt('id');
		$this->setState('region.id', $pk);
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
		$pk=(!empty($pk)) ? $pk : (int) $this->getState('region.id');
		jimport('brilliant_regoffices.cities');
		$brc=BRegofficesCities::getInstance();
		return $brc->items_filter(array('region'=>$pk));
		}
	/**
	 *
	 */
	public function getRegions(){
		$pk=(!empty($pk)) ? $pk : (int) $this->getState('region.id');
		jimport('brilliant_regoffices.regions');
		$brr=BRegofficesRegions::getInstance();
		$region=$brr->item_get($pk);
		if(empty($region)){
			return array();
			}
		return $brr->items_filter(array('country'=>$region->country));
		}
	/**
	 *
	 */
	public function getOffices(){
		$pk=(!empty($pk)) ? $pk : (int) $this->getState('region.id');
		jimport('brilliant_regoffices.offices');
		$bro=BRegofficesOffices::getInstance();
		$offices=$bro->items_filter(array('region'=>$pk));
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
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('region.id');

		try{
			jimport('brilliant_regoffices.regions');
			$brrr=BRegofficesRegions::getInstance();
			$region=$brrr->item_get($pk);

			if(empty($region)) {
				return JError::raiseError(404, 'Region not found (id='.$pk.')!'); //JText::_('COM_CONTENT_ERROR_ARTICLE_NOT_FOUND')
				}
			return $region;
			}
		catch (RuntimeException $e){
			$this->setError($e->getMessage());
			return false;
			}/**/

		}
	}
