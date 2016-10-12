<?php
defined('_JEXEC') or die;
echo('<tr>');
echo('<th width="1%">'.
	'<input'.
	' type="checkbox"'.
	' name="checkall-toggle"'.
	' value=""'.
	' title="'.JText::_('JGLOBAL_CHECK_ALL').'"'.
	' onclick="Joomla.checkAll(this)"></th>');

echo('<th width="*">'.JText::_('COM_TOURS_CITY_HEADING_NAME').'</th>');
echo('<th width="*">'.JText::_('COM_TOURS_CITY_HEADING_COUNTRY').'</th>');
echo('<th width="1%">'.JText::_('COM_TOURS_TOURS_HEADING_ID').'</th>');
echo('</tr>');

