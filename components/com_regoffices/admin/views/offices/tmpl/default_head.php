<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<tr>
	<th width="1%">
		<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this);">
	</th>
	<th width="*"><?php echo JText::_('COM_REGOFFICES_OFFICES_HEADING_NAME'); ?></th>
	<th width="*"><?php echo JText::_('COM_REGOFFICES_OFFICES_HEADING_ADDRESS'); ?></th>
	<th width="100"><?php echo JText::_('COM_REGOFFICES_OFFICES_HEADING_PHONE'); ?></th>
	<th width="120"><?php echo JText::_('COM_REGOFFICES_OFFICES_HEADING_CITY'); ?></th>
	<th width="150"><?php echo JText::_('COM_REGOFFICES_OFFICES_HEADING_REGION'); ?></th>
	<th width="120"><?php echo JText::_('COM_REGOFFICES_OFFICES_HEADING_COUNTRY'); ?></th>
	<th width="1%">ID</th>
</tr>

