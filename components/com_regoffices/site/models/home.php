<?php
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class RegofficesModelHome extends JModelItem{
	/**
	 *
	 */
	public function getCountries(){
		jimport('brilliant_regoffices.countries');
		$brc=BRegofficesCountries::getInstance();
		return $brc->items_get_all();
		}
	/**
	 * Get the root regions (in case )
	 */
	public function getRegions(){
		jimport('brilliant_regoffices.regions');
		$brr=BRegofficesRegions::getInstance();
		return $brr->items_get_all();
		}

	}
