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

echo('<th width="*">'.JText::_('COM_TOURS_ORDER_HEADING_TOUR').'</th>');
echo('<th width="*">'.JText::_('COM_TOURS_ORDER_HEADING_FIO').'</th>');
echo('<th width="*">'.JText::_('COM_TOURS_ORDER_HEADING_PHONE').'</th>');
echo('<th width="*">'.JText::_('COM_TOURS_ORDER_HEADING_EMAIL').'</th>');
echo('<th width="*">'.JText::_('COM_TOURS_ORDER_HEADING_ADULTS').'</th>');
echo('<th width="*">'.JText::_('COM_TOURS_ORDER_HEADING_CHILDREN').'</th>');
echo('<th width="1%">'.JText::_('COM_TOURS_TOURS_HEADING_ID').'</th>');
echo('</tr>');

