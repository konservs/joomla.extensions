<?php
defined('_JEXEC') or die('No direct access!');

//Get frontend router.
$app=JApplication::getInstance('site');
$router=$app->getRouter();
$canChange=true;
?>

<?php foreach($this->items as $i=>$item): ?>
	<tr>
		<!-- Checkbox -->
		<td><?php echo JHtml::_('grid.id',$i,$item->id); ?></td>
		<!-- Country name -->
		<?php $url=JRoute::_('index.php?option=com_regoffices&task=city.edit&id='.$item->id); ?>
		<td><a href="<?php echo $url; ?>"><?php echo $this->escape($item->getname()); ?></a></td>
		<!-- -->
		<td align="left">
			<?php $country=$item->getcountry(); ?>
			<span><?php echo(isset($country)?htmlspecialchars($country->getname()):'-'); ?></span>
		</td>
		<!-- -->
		<td align="left">
			<?php $region=$item->getregion(); ?>
			<span><?php echo(isset($region)?htmlspecialchars($region->getname()):'-'); ?></span>
		</td>
		<!-- -->
		<td align="center">
			<?php //echo JHtml::_('jgrid.published', ($item->status=='P'), $i, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
			<?php echo JHtml::_('jgrid.published', ($item->status=='P'), $i, 'cities.', $canChange, 'cb'); ?>
		</td>
		<!-- -->
		<?php $uri_site=$router->build('index.php?option=com_regoffices&view=city&id='.$item->id); $url_site=$uri_site->toString(); ?>
		<td align="center"><a href="<?php echo $url_site; ?>" class="btn btn-small">site</a></td>
		<!-- Offices count -->
		<td align="center"><?php echo (int)$item->offices; ?></td>
		<!-- Cities ID -->
		<td align="right"><?php echo $item->id; ?></td>
	</tr>
<?php endforeach; ?>
