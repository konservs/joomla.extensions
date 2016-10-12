<?php
// Запрет прямого доступа.
defined('_JEXEC') or die;
?>
<tr>
	<th width="1%">
		<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this);">
	</th>
	<th width="*"><?php echo JText::_('COM_REGOFFICES_COUNTRIES_HEADING_NAME'); ?></th>
	<th width="100"><?php echo JText::_('COM_REGOFFICES_COUNTRIES_HEADING_ISO'); ?></th>
	<th width="100"><?php echo JText::_('COM_REGOFFICES_COUNTRIES_HEADING_REGIONS'); ?></th>
	<th width="100"><?php echo JText::_('COM_REGOFFICES_COUNTRIES_HEADING_CITIES'); ?></th>
	<th width="100"><?php echo JText::_('COM_REGOFFICES_COUNTRIES_HEADING_OFFICES'); ?></th>
	<th width="1%">ID</th>
</tr>

