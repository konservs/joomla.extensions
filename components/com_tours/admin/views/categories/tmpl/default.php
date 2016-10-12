<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');

//echo('Industries will be added in future');
?>
<div id="industries-list">
<form action="<?php echo JRoute::_('index.php?option=com_exhibitions'); ?>" method="post" name="adminForm" id="adminForm">
    <table class="adminlist">
        <thead><?php echo $this->loadTemplate('head');?></thead>
        <tbody><?php echo $this->loadTemplate('body');?></tbody>
        <tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
    </table>
    <div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
</div>