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
$faction=JRoute::_('index.php?option=com_regoffices&view=city&layout=edit&id='.$id);

//Load some JS
$url_regions_json=JRoute::_('index.php?option=com_regoffices&view=regions&format=json',false);
$jscontent='window.url_regions_json="'.$url_regions_json.'";'.PHP_EOL;
$jscontent.=file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'edit.js');
$doc->addScriptDeclaration($jscontent);
?>
<form action="<?php echo $faction; ?>" method="post" name="adminForm" id="cities-form">
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
			<div class="">
				<a href="" class="onsite" target="_blank">На сайте</a>
			</div>
			<div class="formfield">
				<label for="regoffice_city_status">Состояние</label>
				<select name="city[status]" id="regoffice_city_status" required>
					<option value="P">Опубликовано</option>
					<option value="N">Не опубликовано</option>
					<option value="D">Удалено</option>
				</select>
			</div>


			<div class="formfield">
				<label for="regoffice_city_country">Страна</label>
				<select name="city[country]" class="ajaxloadregion" data-region-selector="#regoffice_city_region" id="regoffice_city_country" required="required">
					<option value="">Пожалуйста, выберите</option>
					<?php $country_active=isset($this->item)?$this->item->country:0; ?>
					<?php foreach($this->fcountries as $country): ?>
						<option value="<?php echo $country->id; ?>"<?php echo($country->id==$country_active?' selected':''); ?>><?php echo htmlspecialchars($country->name); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="formfield">
				<label for="regoffice_city_region">Регион</label>
				<!-- <div class="selectwithbutton"> -->
					<?php $region_active=isset($this->item)?$this->item->region:0; ?>
					<select name="city[region]" id="regoffice_city_region" data-active="<?php echo $region_active; ?>" required>
						<option value="">Пожалуйста, выберите</option>
					</select>
				<!-- 	<button>+ Добавить</button>
				</div> -->
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo(JHtml::_('form.token')); ?>
</form>
