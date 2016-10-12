<?php
defined('_JEXEC') or die('No direct access!');

foreach ($this->items as $i => $item){
	$url=JRoute::_('index.php?option=com_exhibitions&task=industry.edit&ind_id='.$item->id);
	
	$name=$this->escape($item->getName());
	if(empty($name)){
		$name='<span class="undefined">undefined</span>';
		}	
	echo('<tr>');
	echo('<td>'.JHtml::_('grid.id',$i,$item->id).'</td>');
	echo('<td><a href="'.$url.'">'.$name.'</a></td>');
	echo('<td>'.$item->id.'</td>');
	echo('</tr>');
	}
