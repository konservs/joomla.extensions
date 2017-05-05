<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

if(!class_exists('JHelperContent')){
	class JHelperContent{};
	}
/**
 * @since  1.6
 */
class BloginHelper extends JHelperContent{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 * @return  void
	 * @since   1.6
	 */
	public static function addSubmenu($vName){
		if(!class_exists('JHtmlSidebar')){
			return true;
			}
		JHtmlSidebar::addEntry(
			JText::_('COM_REGOFFICES_OFFICES'),
			'index.php?option=com_regoffices&view=home',
			$vName=='home'
			);
		}
	}
