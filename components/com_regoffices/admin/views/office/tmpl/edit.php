<?php
defined('_JEXEC') or die('No direct access!'); 
//jimport('brilliant_exhibitions.language');
JHTML::_('behavior.tooltip');

$doc=JFactory::getDocument();

$lang_list=JLanguageHelper::getLanguages();
$lang=JFactory::getLanguage();
$curr_lang = $lang->getName();
$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$faction=JRoute::_('index.php?option=com_regoffices&view=office&layout=edit&id='.$id);

//Load some JS
$url_regions_json=JRoute::_('index.php?option=com_regoffices&view=regions&format=json',false);
$url_cities_json=JRoute::_('index.php?option=com_regoffices&view=cities&format=json',false);

$jscontent='window.url_regions_json="'.$url_regions_json.'";'.PHP_EOL;
$jscontent.='window.url_cities_json="'.$url_cities_json.'";'.PHP_EOL;
$jscontent.=file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'edit.js');
$doc->addScriptDeclaration($jscontent);
$doc->addScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyCJUAKn4dyMCYfQvFivjbBs9R279YBOCek');
?>
<form action="<?php echo $faction; ?>" method="post" name="adminForm" id="adminForm">
	<div class="brilliant-width-60 brilliant-fltlft">
		<div id="ind_mainwrapper">
			<?php if(count($lang_list)>1): ?>
				<div id="brillianttabs">
					<?php foreach($lang_list as $language): ?>
						<?php $this->language=$language; ?>
						<a class="<?php echo ($lang->getTag()==$language->lang_code?"active":""); ?>" for="tab_<?php echo $this->language->lang_code; ?>" href="#tab_<?php echo $this->language->lang_code; ?>"><?php echo htmlspecialchars($this->language->title_native); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div id="tabs_content">
				<?php foreach($lang_list as $language): ?>
					<?php $this->language=$language; ?>
					<?php $this->lang_tag=$lang->getTag(); ?>
					<?php echo($this->loadTemplate('tab')); ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<div class="brilliant-width-40 brilliant-fltlft brillsidebar">
		<div id="ind_rightwrapper">
			<div class="formfield">
				<label for="regoffice_office_status"><?php echo JText::_('COM_REGOFFICES_OFFICE_STATE'); ?></label>
				<select name="office[status]" id="regoffice_office_status" required>
					<option value="P"><?php echo JText::_('COM_REGOFFICES_OFFICE_STATE_P'); ?></option>
					<option value="N"><?php echo JText::_('COM_REGOFFICES_OFFICE_STATE_N'); ?></option>
					<option value="D"><?php echo JText::_('COM_REGOFFICES_OFFICE_STATE_D'); ?></option>
				</select>
			</div>
			<div class="formfield">
				<label for="regoffice_office_country"><?php echo JText::_('COM_REGOFFICES_OFFICE_COUNTRY'); ?></label>
				<select name="office[country]" class="ajaxloadregion" data-region-selector="#regoffice_office_region" id="regoffice_office_country" required="required">
					<option value=""><?php echo JText::_('COM_REGOFFICES_PLEASESELECT'); ?></option>
					<?php $country_active=isset($this->item)?$this->item->country:0; ?>
					<?php foreach($this->fcountries as $country): ?>
						<option value="<?php echo $country->id; ?>"<?php echo($country->id==$country_active?' selected':''); ?>><?php echo htmlspecialchars($country->name); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="formfield">
				<label for="regoffice_office_region"><?php echo JText::_('COM_REGOFFICES_OFFICE_REGION'); ?></label>
				<!-- <div class="selectwithbutton"> -->
					<?php $region_active=isset($this->item)?$this->item->region:0; ?>
					<select name="office[region]" class="ajaxloadcity" data-city-selector="#regoffice_office_city" id="regoffice_office_region" data-active="<?php echo $region_active; ?>" required>
						<option value=""><?php echo JText::_('COM_REGOFFICES_PLEASESELECT'); ?></option>
					</select>
				<!-- 	<button>+ Добавить</button>
				</div> -->
			</div>

			<div class="formfield">
				<label for="regoffice_office_city"><?php echo JText::_('COM_REGOFFICES_OFFICE_CITY'); ?></label>
				<!-- <div class="selectwithbutton"> -->
					<?php $city_active=isset($this->item)?$this->item->city:0; ?>
					<select name="office[city]" id="regoffice_office_city" data-active="<?php echo $city_active; ?>" required>
						<option value=""><?php echo JText::_('COM_REGOFFICES_PLEASESELECT'); ?></option>
					</select>
				<!-- 	<button>+ Добавить</button>
				</div> -->
			</div>

			<div class="formfield formfield-map">
				<label for="formfieldmap"><?php echo JText::_('COM_REGOFFICES_OFFICE_MAP'); ?></label>
				<div id="formfieldmap" style="height: 300px;"></div>
				<input type="hidden" id="formfieldmap_lat" name="office[lat]" value="<?php echo(isset($this->item)?$this->item->lat:''); ?>"/>
				<input type="hidden" id="formfieldmap_lng" name="office[lng]" value="<?php echo(isset($this->item)?$this->item->lng:''); ?>"/>
			</div>

		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo(JHtml::_('form.token')); ?>
</form>
