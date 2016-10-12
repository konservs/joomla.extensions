<?php
defined('_JEXEC') or die;
$url_actual='/exhibitions';//JRoute::_('index.php?option=com_exhibitions&view=exhibitions');
echo('<div class="title">');
echo('<h1 class="title">'.JText::_('COM_EXHIBITIONS_ARCHIVE_HEADER').'</h1>');
echo('</div>');
echo('<div class="exhibitionsarchive-intro">'.sprintf(JText::_('COM_EXHIBITIONS_ARCHIVE_INTROTEXT'),$url_actual).'</div>');
echo('<div class="exhibitionsarchive-list"><table width="100%" class="bordered">');
echo('<tr><th width="*">name</th><th width="100">start</th><th width="100">end</th></tr>');
foreach($this->exhibitions as &$item){
	echo('<tr>');
	echo('<td><a href="'.$item->url().'">'.$item->getName().'</a></td>');
	echo('<td>'.$item->getStartDate()->format('d.m.Y').'</td>');
	echo('<td>'.$item->getEndDate()->format('d.m.Y').'</td>');
	echo('</tr>');
	}
echo('</table></div>');
echo('</div>')
?>