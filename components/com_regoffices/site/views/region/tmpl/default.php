<?php
defined('_JEXEC') or die('No direct access!');
$doc=JFactory::getDocument();
$doc->addStylesheet('/media/com_regoffices/css/regoffices.css');
$doc->addScript('https://maps.googleapis.com/maps/api/js?key='.$this->mapskey.'&signed_in=true');//&callback=initMap
$jscontent='window.url_offices_json="'.$this->url_offices_json.'";'.PHP_EOL;
$jscontent.='window.regoffices_filter={};'.PHP_EOL;
$jscontent.='window.regoffices_filter.region='.(int)$this->region->id.';'.PHP_EOL;
$jscontent.=file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'default.js');
$doc->addScriptDeclaration($jscontent);
$doc->addScript('/media/com_regoffices/js/regoffices.js');
?>
<div id="regoffices-region">
	<div class="title">
		<h1 class="title"><?php echo $this->heading; ?></h1>
	</div>

	<div class="regoffices-widget">
		<div class="map" id="regoffices-com-map"></div>
		<div class="selector"><div class="wr">
			<h2><?php echo JText::_('COM_REGOFFICES_SELECT_HEADING'); ?></h2>

			<?php if($this->show_page_country): ?>
				<div class="fld fld-country">
					<div><?php echo JText::_('COM_REGOFFICES_SELECT_COUNTRY'); ?></div>
					<ul class="countries listtoselect">
						<?php foreach($this->countries as $country): ?>
							<li<?php echo($country->id==$this->country->id?' class="active"':''); ?>>
								<a href="<?php echo JRoute::_('index.php?option=com_regoffices&view=country&id='.$country->id); ?>"><?php echo $country->getlangvar('name'); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>

			<div class="fld fld-region">
				<div><?php echo JText::_('COM_REGOFFICES_SELECT_REGION'); ?></div>
				<ul class="cities listtoselect">
					<?php foreach($this->regions as $region): ?>
						<li<?php echo($region->id==$this->region->id?' class="active"':''); ?>>
							<a href="<?php echo JRoute::_('index.php?option=com_regoffices&view=region&id='.$region->id); ?>"><?php echo $region->getlangvar('name'); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>


		</div></div>
		<div class="clear"></div>
	</div>

	<?php if(!empty($this->offices)): ?>
		<div>
			<?php foreach($this->offices as $office): ?>
				<div>
					<span><?php echo $office->getname(); ?></span>
					<span><?php echo $office->getlangvar('phone'); ?></span>
					<span><?php echo $office->getlangvar('address'); ?></span>
					<span><?php echo $office->getlangvar('site'); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="description"><?php echo $this->description; ?></div>
</div>
