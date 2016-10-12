<?php
defined('_JEXEC') or die('No direct access!'); 
$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$lang_code=$this->language->lang_code;
$sef=$this->language->sef;

//var_dump($this->language);die();
?>
<div id="tab_<?php echo $lang_code; ?>" class="tab<?php echo ($this->lang_tag==$lang_code?" active":""); ?>">
	<!-- Название региона -->
	<div class="formfield">
		<label for="rg-name-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_REGION_NAME'); ?></label>
		<input type="text" id="rg-name-<?php echo $lang_code; ?>" name="region[name_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getName($lang_code)); ?>">
	</div>
	<!-- Алиас -->
	<div class="formfield">
		<label for="rg-alias-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_REGION_ALIAS'); ?></label>
		<input type="text" id="rg-alias-<?php echo $lang_code; ?>" name="region[alias_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('alias',$lang_code)); ?>">
	</div>
	<!-- Описание города -->
	<div class="formfield">
		<label for="rg-description-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_REGION_DESCRIPTION'); ?></label>
		<textarea id="rg-description-<?php echo $lang_code; ?>" name="region[description_<?php echo $lang_code; ?>]" class="required"><?php echo ($isNew?'':$this->item->getlangvar('description',$lang_code)); ?></textarea>
	</div>
	<!-- H1 -->
	<div class="formfield">
		<label for="rg-h1-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_REGION_H1'); ?></label>
		<input type="text" id="rg-h1-<?php echo $lang_code; ?>" name="region[h1_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('h1',$lang_code)); ?>">
	</div>
	<!-- title -->
	<div class="formfield">
		<label for="rg-title-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_REGION_TITLE'); ?></label>
		<input type="text" id="rg-title-<?php echo $lang_code; ?>" name="region[title_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('title',$lang_code)); ?>">
	</div>
	<!-- META Description -->
	<div class="formfield">
		<label for="rg-metadesc-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_REGION_METADESC'); ?></label>
		<input type="text" id="rg-metadesc-<?php echo $lang_code; ?>" name="region[metadesc_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('metadesc',$lang_code)); ?>">
	</div>	
	<!-- META Keywords -->
	<div class="formfield">
		<label for="rg-metakeyw-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_REGION_METAKEYW'); ?></label>
		<input type="text" id="rg-metakeyw-<?php echo $lang_code; ?>" name="region[metakeyw_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('metakeyw',$lang_code)); ?>">
	</div>
</div>
