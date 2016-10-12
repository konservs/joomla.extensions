<?php
defined('_JEXEC') or die;
echo('<div class="exhibition">');
echo('<div class="image"><a href="'.$this->item->url().'" title="'.$this->item->getName().'">');
echo('<img src="'.$this->item->getIntroimg().'" alt="'.$this->item->getName().'" title="'.$this->item->getName().'"></a></div>');
echo('<div class="info">');
echo('<h2><a href="'.$this->item->url().'">'.$this->item->getName().'</a></h2>');
echo('<div class="description">'.$this->item->getIntrotext().'</div>');
echo('<div class="foot">');
echo('<div class="date" title="'.JText::_('COM_EXHIBITIONS_EXB_DATE').'">'.$this->item->getPrettydate().'</div>');
echo('<div class="city" title="'.JText::_('COM_EXHIBITIONS_EXB_CITY').'">'.$this->item->getCity()->getName().'</div>');
echo('<div class="industries" title="'.JText::_('COM_EXHIBITIONS_EXB_INDUSTRY').'">');
$i=0;
$industries=$this->item->getIndustries();
foreach($industries as $industry)
	echo(($i++?', ':'').'<span class="industry">'.$industry->getName().'</span>');
echo('</div>');
echo('</div>'); //foot
echo('</div>'); //description
echo('</div>'); //exhibition
?>