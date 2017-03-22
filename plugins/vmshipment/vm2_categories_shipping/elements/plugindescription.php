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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

 // Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * A label/header element, displayed left-aligned, spanning the whole width. This can be used for section headers as well as for explanatory text
 */

class JElementPluginDescription extends JElement{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	var	$_name = 'plugindescription';

	function fetchElement($name, $value, &$node, $control_name){
		$class = ( $node->attributes('class') ? 'class="'.$node->attributes('class').'"' : 'class="text_area"' );
		return '<label for="'.$name.'"'.$class.'>'.JText::_($value).'</label>';
		}

	/**
	 * Method to render an xml element
	 *
	 * @param   string  &$xmlElement   Name of the element
	 * @param   string  $value         Value of the element
	 * @param   string  $control_name  Name of the control
	 *
	 * @return  array  Attributes of an element
	 *
	 * @deprecated    12.1
	 * @since   11.1
	 */
	public function render(&$xmlElement, $value, $control_name = 'params'){
		$name = $xmlElement->attributes('name');
		$label = $xmlElement->attributes('label');
		$descr = $xmlElement->attributes('description');
		//make sure we have a valid label
		$label = $label ? $label : $name;
		// Set to NULL so that the virtuemart table layout code will make the element span two columns:
		$result[0] = NULL;
		// $result[0] = $this->fetchTooltip($label, $descr, $xmlElement, $control_name, $name);
		$result[1] = $this->fetchElement($name, $value, $xmlElement, $control_name);
		$result[2] = $descr;
		$result[3] = $label;
		$result[4] = $value;
		$result[5] = $name;
		return $result;
		}
	}
