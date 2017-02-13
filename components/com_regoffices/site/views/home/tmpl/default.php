<?php
defined('_JEXEC') or die('No direct access!');
$doc=JFactory::getDocument();
$lang=JFactory::getLanguage();
$doc->addStylesheet('/media/com_regoffices/css/regoffices.css');
$doc->addScript('https://maps.googleapis.com/maps/api/js?key='.$this->mapskey.'&signed_in=true&language='.$lang->getTag());//&callback=initMap
$jscontent='window.url_offices_json="'.$this->url_offices_json.'";'.PHP_EOL;
$jscontent.=file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'default.js');
$doc->addScriptDeclaration($jscontent);

$doc->addScript('/media/com_regoffices/js/regoffices.js');
?>
<div id="regoffices-home">
	<div class="title">
		<h1 class="title"><?php echo JText::_('COM_REGOFFICES_HEADING'); ?></h1>
	</div>

	<div class="regoffices-widget">
		<div class="map" id="regoffices-com-map"></div>
		<div class="selector"><div class="wr">
			<h2><?php echo JText::_('COM_REGOFFICES_SELECT_HEADING'); ?></h2>

			<?php if($this->show_page_country): ?>
				<div>
					<div><?php echo JText::_('COM_REGOFFICES_SELECT_COUNTRY'); ?></div>
					<ul class="countries listtoselect">
						<?php foreach($this->countries as $country): ?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_regoffices&view=country&id='.$country->id); ?>"><?php echo $country->getlangvar('name'); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php elseif($this->show_page_region): ?>
				<div>
					<div><?php echo JText::_('COM_REGOFFICES_SELECT_REGION'); ?></div>
					<ul class="regions listtoselect">
						<?php foreach($this->regions as $region): ?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_regoffices&view=region&id='.$region->id); ?>"><?php echo $region->getlangvar('name'); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php else: ?>
				<div class="alert alert-danger">Wrong configuration.</div>
			<?php endif; ?>
		</div></div>
		<div class="clear"></div>
	</div>

	<div class="description"><?php echo JText::_('COM_REGOFFICES_DESCRIPTION'); ?></div>
</div>
