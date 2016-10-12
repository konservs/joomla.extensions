<?php
defined('_JEXEC') or die('No direct access!');
foreach ($this->items as $i => $item){
	//$url=JRoute::_('index.php?option=com_exhibitions&view=exhibition&layout=edit&exb_id='.$item->id);
	$url=JRoute::_('index.php?option=com_exhibitions&task=exhibition.edit&exb_id='.$item->id);
	$catsc=$item->getIndustries();
	$catst='';
	foreach($catsc as $c){
		$catst.=(empty($catst)?'':', ').$c->getName();
		}
	//
	$name=$this->escape($item->getName());
	if(empty($name)){
		$name='<span class="undefined">undefined</span>';
		}
	echo('<tr>');
	echo('<td>'.JHtml::_('grid.id',$i,$item->id).'</td>');
	echo('<td><a href="'.$url.'">'.$name.'</a></td>');
	echo('<td>'.$item->getCity()->getName().'</td>');
	echo('<td>'.$catst.'</td>');
	echo('<td>'.$item->getStartDate()->format('d.m.Y').'</td>');
	echo('<td>'.$item->getEndDate()->format('d.m.Y').'</td>');
	echo('<td>'.$item->id.'</td>');
	echo('</tr>');
	}
