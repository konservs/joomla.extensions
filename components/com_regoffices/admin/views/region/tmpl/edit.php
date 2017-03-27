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
$faction=JRoute::_('index.php?option=com_regoffices&view=region&layout=edit&id='.$id);

//Load some JS
$jscontent='';
$jscontent.=file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'edit.js');
$doc->addScriptDeclaration($jscontent);
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
			<div class="">
				<a href="" class="onsite" target="_blank">На сайте</a>
			</div>
			<div class="formfield">
				<label for="regoffice_region_status">Состояние</label>
				<select name="region[status]" id="regoffice_region_status" required>
					<option value="P">Опубликовано</option>
					<option value="N">Не опубликовано</option>
					<option value="D">Удалено</option>
				</select>
			</div>


			<div class="formfield">
				<label for="regoffice_region_country">Страна</label>
				<select name="region[country]" class="ajaxloadregion" data-region-selector="#regoffice_region_region" id="regoffice_region_country" required="required">
					<option value="">Пожалуйста, выберите</option>
					<?php $country_active=isset($this->item)?$this->item->country:0; ?>
					<?php foreach($this->fcountries as $country): ?>
						<option value="<?php echo $country->id; ?>"<?php echo($country->id==$country_active?' selected':''); ?>><?php echo htmlspecialchars($country->name); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo(JHtml::_('form.token')); ?>
</form>
