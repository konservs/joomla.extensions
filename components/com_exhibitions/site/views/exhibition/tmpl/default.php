<?php
defined('_JEXEC') or die;
if(empty($this->exhibition))die;
echo('<div class="exhibition" itemscope itemtype="http://schema.org/BusinessEvent">');
echo('<div class="title">');
echo('<h1 class="title" itemprop="name">'.$this->exhibition->getName().'</h1>');
echo('</div>');
echo('<div class="image"><img itemprop="image" src="'.$this->exhibition->getFullimg().'" alt="'.$this->exhibition->getName().'" title="'.$this->exhibition->getName().'"></div>');
//Some schema.org data...
$dt_s=$this->exhibition->getStartdate();
$dt_e=$this->exhibition->getEnddate();
echo('<meta itemprop="startDate" content="'.$dt_s->format(DateTime::ISO8601).'">');
echo('<meta itemprop="endDate" content="'.$dt_e->format(DateTime::ISO8601).'">');

//doorTime
//duration
//location


echo('<p><b>'.JText::_('COM_EXHIBITIONS_EXB_DATE').'</b>: '.$this->exhibition->getPrettyDate().'</p>');
echo('<p><b>'.JText::_('COM_EXHIBITIONS_EXB_CITY').'</b>: '.$this->exhibition->getCity()->getName().'</p>');
echo('<p><b>'.JText::_('COM_EXHIBITIONS_EXB_INDUSTRY').'</b>: ');
$i=0;
$industries=$this->exhibition->getIndustries();
foreach($industries as $industry)
	echo(($i++?', ':'').'<span class="industry">'.$industry->getName().'</span>');
echo('</p>');

echo($this->exhibition->getFulltext());

$url_all=JRoute::_('index.php?option=com_exhibitions&view=exhibitions');
echo('<p>'.sprintf(JText::_('COM_EXHIBITIONS_EXB_VIEWALL'),$url_all).'</p>');
echo('</div>');
