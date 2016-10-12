<?php
defined('_JEXEC') or die('No direct access!');

//Get frontend router.
$app=JApplication::getInstance('site');
$router=$app->getRouter();
?>

<?php foreach($this->items as $i=>$item): ?>
	<tr>
		<!-- Checkbox -->
		<td><?php echo JHtml::_('grid.id',$i,$item->id); ?></td>
		<!-- Country name -->
		<?php $url=JRoute::_('index.php?option=com_regoffices&task=country.edit&id='.$item->id); ?>
		<td><a href="<?php echo $url; ?>"><?php echo $this->escape(JText::_('COM_REGOFFICES_COUNTRY_'.$item->iso)); ?></a></td>
		<!-- Country ISO code -->
		<td align="center"><?php echo $this->escape($item->iso); ?></td>
		<!-- -->
		<?php $uri_site=$router->build('index.php?option=com_regoffices&view=country&id='.$item->id); $url_site=$uri_site->toString(); ?>
		<td align="center"><a href="<?php echo $url_site; ?>" class="btn btn-small">site</a></td>
		<!-- Regions count -->
		<td align="center"><?php echo (int)$item->regions; ?></td>
		<!-- Cities count -->
		<td align="center"><?php echo (int)$item->cities; ?></td>
		<!-- Offices count -->
		<td align="center"><?php echo (int)$item->offices; ?></td>
		<!-- Country ID -->
		<td align="right"><?php echo $item->id; ?></td>
	</tr>
<?php endforeach; ?>
