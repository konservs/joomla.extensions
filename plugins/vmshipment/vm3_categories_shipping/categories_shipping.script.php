<?php
defined('_JEXEC') or die('Restricted access');
/**
 * Installation script for the plugin
 *
 * @copyright Copyright (C) 2013 Andrii Biriev, a@konservs.com
 * @license GPL v3+,  http://www.gnu.org/copyleft/gpl.html 
 */

class plgVmShipmentCategories_ShippingInstallerScript{
	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function install(JAdapterInstance $adapter){
		// enabling plugin
		$db =& JFactory::getDBO();
		$db->setQuery('update #__extensions set enabled = 1 where type = "plugin" and element = "categories_shipping" and folder = "vmshipment"');
		$db->query();
		return True;
		}
	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	public function uninstall(JAdapterInstance $adapter){
		//Remove plugin table
		//$db =& JFactory::getDBO();
		//$db->setQuery('DROP TABLE `#__virtuemart_shipment_plg_categories_shipping`;');
		//$db->query();
		}
	}
