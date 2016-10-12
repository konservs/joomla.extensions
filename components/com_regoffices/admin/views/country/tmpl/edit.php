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
$faction=JRoute::_('index.php?option=com_regoffices&view=country&layout=edit&id='.$id);

//Load some JS
$jscontent=file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR.'edit.js');
$doc->addScriptDeclaration($jscontent);
?>
<form action="<?php echo $faction; ?>" method="post" name="adminForm" id="cities-form">
	<div class="width-60 fltlft">
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
	<div class="width-40 fltlft brillsidebar">
		<div id="ind_rightwrapper">
			<div class="">
				<a href="" class="onsite" target="_blank">На сайте</a>
			</div>
			<div class="formfield">
				<label for="regoffice_country_status">Состояние</label>
				<select name="country[status]" id="regoffice_country_status" required>
					<option value="P">Опубликовано</option>
					<option value="N">Не опубликовано</option>
					<option value="D">Удалено</option>
				</select>
			</div>
			<div class="formfield">
				<label for="regoffice_country_country">ISO код</label>
				<input type="text" name="country[iso]" class="ajaxloadregion" id="regoffice_country_iso" value="<?php echo $this->item->iso; ?>">
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo(JHtml::_('form.token')); ?>
</form>
