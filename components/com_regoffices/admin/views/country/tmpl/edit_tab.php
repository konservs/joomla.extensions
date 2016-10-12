<?php
defined('_JEXEC') or die('No direct access!'); 
$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$lang_code=$this->language->lang_code;
$sef=$this->language->sef;
?>
<div id="tab_<?php echo $lang_code; ?>" class="tab<?php echo ($this->lang_tag==$lang_code?" active":""); ?>">
	<!-- Название города -->
	<div class="formfield">
		<label for="country-name-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_COUNTRY_NAME'); ?></label>
		<input type="text" id="country-name-<?php echo $lang_code; ?>" name="country[name_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getName($lang_code)); ?>">
	</div>
	<!-- Алиас -->
	<div class="formfield">
		<label for="country-alias-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_COUNTRY_ALIAS'); ?></label>
		<input type="text" id="country-alias-<?php echo $lang_code; ?>" name="country[alias_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('alias',$lang_code)); ?>">
	</div>
	<!-- Описание города -->
	<div class="formfield">
		<label for="country-description-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_COUNTRY_DESCRIPTION'); ?></label>
		<textarea id="country-description-<?php echo $lang_code; ?>" name="country[description_<?php echo $lang_code; ?>]" class="required"><?php echo ($isNew?'':$this->item->getlangvar('description',$lang_code)); ?></textarea>
	</div>
	<!-- H1 -->
	<div class="formfield">
		<label for="country-h1-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_COUNTRY_H1'); ?></label>
		<input type="text" id="country-h1-<?php echo $lang_code; ?>" name="country[h1_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('h1',$lang_code)); ?>">
	</div>
	<!-- title -->
	<div class="formfield">
		<label for="country-title-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_COUNTRY_TITLE'); ?></label>
		<input type="text" id="country-title-<?php echo $lang_code; ?>" name="country[title_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('title',$lang_code)); ?>">
	</div>
	<!-- META Description -->
	<div class="formfield">
		<label for="country-metadesc-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_COUNTRY_METADESC'); ?></label>
		<input type="text" id="country-metadesc-<?php echo $lang_code; ?>" name="country[metadesc_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('metadesc',$lang_code)); ?>">
	</div>	
	<!-- META Keywords -->
	<div class="formfield">
		<label for="country-metakeyw-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_COUNTRY_METAKEYW'); ?></label>
		<input type="text" id="country-metakeyw-<?php echo $lang_code; ?>" name="country[metakeyw_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('metakeyw',$lang_code)); ?>">
	</div>
</div>
