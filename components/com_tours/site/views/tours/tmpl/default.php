<?php
defined('_JEXEC') or die;
echo('<div class="exhibitions">');
$url_archive=JRoute::_('index.php?option=com_exhibitions&view=archive');
echo('<div class="title">');
echo('<h1 class="title">'.JText::_('COM_EXHIBITIONS_HEADER').'</h1>');
echo('</div>');
echo('<div class="exhibitions-intro">'.sprintf(JText::_('COM_EXHIBITIONS_INTROTEXT'),$url_archive).'</div>');
echo($this->loadTemplate('filter'));
echo('<div class="exhibitions-list">');
foreach($this->exhibitions as &$item){
	$this->item = &$item;
	echo($this->loadTemplate('item'));
	}
echo('</div>');
echo('<div class="exhibitions-smalltext">'.JText::_('COM_EXHIBITIONS_EXTRATEXT').'</div>');
echo('</div>')
?>