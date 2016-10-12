<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
?>
<div id="countries-list">
	<form action="<?php echo JRoute::_('index.php?option=com_regoffices&view=countries'); ?>" method="post" name="adminForm" id="adminForm">
		<?php if (!empty( $this->sidebar)) : ?>
			<div id="j-sidebar-container" class="span2"><?php echo $this->sidebar; ?></div>
			<div id="j-main-container" class="span10">
		<?php else : ?>
			<div id="j-main-container">
		<?php endif;?>
				<?php //echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>
				<?php if (empty($this->items)) : ?>
					<div class="alert alert-no-items">
						<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
					</div>
				<?php else : ?>
					<table class="adminlist table table-striped" width="100%">
						<thead><?php echo $this->loadTemplate('head');?></thead>
						<tbody><?php echo $this->loadTemplate('body');?></tbody>
						<tfoot><?php //echo $this->loadTemplate('foot');?></tfoot>
					</table>
				<?php endif; ?>
				<div>
					<input type="hidden" name="task" value="" />
					<input type="hidden" name="boxchecked" value="0" />
					<?php echo JHtml::_('form.token'); ?>
				</div>
			</div><!-- j-main-container -->
	</form>
</div>