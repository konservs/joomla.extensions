<?php
/**
 * Simple label
 * ------------------------------------------------------------------------
 * author    Andrii Biriev
 * copyright Copyright (C) 2014 konservs.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl.html GNU/GPL
 * Websites: http://konservs.com/
 * Technical Support: http://konservs.com/
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.form.formfield');

/**
 *
 */
class JFormFieldPluginDescription extends JFormField{
	protected $type = 'PluginDescription';
	/**
	 *
	 */
	function getLabel(){
		return '';
		}

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	function getInput() {
		$html='';
		$html.='<h2 style="">'.JText::_('VMSHIPMENT_CATEGORIES').'</h2>';
		$html.='<p>'.JText::_('VMSHIPMENT_CATEGORIES_HELP').'</p>';
		return $html;
		}
	}
