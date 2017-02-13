<?php
defined('_JEXEC') or die('No direct access!');
//var_dump($this->items);die();
?>

<?php foreach($this->items as $i=>$item): ?>
	<tr>
		<!-- Checkbox -->
		<td><?php echo JHtml::_('grid.id',$i,$item->id); ?></td>
		<!-- Office name -->
		<?php $url=JRoute::_('index.php?option=com_regoffices&task=office.edit&id='.$item->id); ?>
		<?php $name=$item->getname(); ?>
		<td><a href="<?php echo $url; ?>"><?php echo(empty($name)?'- unnamed -':$this->escape($name)); ?></a></td>
		<!-- Country ISO code -->
		<td><?php echo $item->getlangvar('address'); ?></td>
		<!-- Cities count -->
		<td><?php echo $item->getlangvar('phone'); ?></td>


		<!-- Office city -->
		<?php $city=$item->getcity(); ?>
		<td><?php echo(empty($city)?'':$city->getname()); ?></td>
		<!-- Office region -->
		<?php $region=$item->getregion(); ?>
		<td><?php echo(empty($region)?'':$region->getname()); ?></td>
		<!-- Office country -->
		<?php $country=$item->getcountry(); ?>
		<td><?php echo(empty($country)?'':$country->getname()); ?></td>
		<!-- Country ID -->
		<td><?php echo $item->id; ?></td>
	</tr>
<?php endforeach; ?>
