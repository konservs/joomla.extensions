<?php
defined('_JEXEC') or die('No direct access!');
jimport('joomla.application.component.helper');
$mapskey=JComponentHelper::getParams('com_regoffices')->get('googlemaps_key');
//$mapskey='AIzaSyCURPPXV_X66ukfddsJ_321GGtkhEpakI8';

$doc=JFactory::getDocument();
$lang=JFactory::getLanguage();
$url_offices_json=JRoute::_('index.php?option=com_regoffices&view=offices&format=json');

$doc->addStylesheet('/media/com_regoffices/css/regoffices.css');
$doc->addScript('https://maps.googleapis.com/maps/api/js?key='.$mapskey.'&signed_in=true&language='.$lang->getTag());//&callback=initMap
$jscontent='window.url_offices_json="'.$url_offices_json.'";'.PHP_EOL;
$jscontent.='window.regoffices_filter={};'.PHP_EOL;
$jscontent.='window.regoffices_filter.country='.$this->country->id.';'.PHP_EOL;
$jscontent.=file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'default.js');
$doc->addScriptDeclaration($jscontent);
?>
<div id="regoffices-country">
	<div class="title">
		<h1 class="title"><?php echo $this->heading; ?></h1>
	</div>

	<div class="regoffices-widget">
		<div class="map" id="regoffices-com-map"></div>
		<div class="selector"><div class="wr">
			<h2><?php echo JText::_('COM_REGOFFICES_SELECT_HEADING'); ?></h2>
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

			<div class="fld fld-city">
				<div><?php echo JText::_('COM_REGOFFICES_SELECT_CITY'); ?></div>
				<ul class="cities listtoselect">
					<?php foreach($this->cities as $city): ?>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_regoffices&view=city&id='.$city->id); ?>"><?php echo $city->getlangvar('name'); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>


		</div></div>
		<div class="clear"></div>
	</div>

	<div class="description"><?php echo $this->description; ?></div>
</div>
