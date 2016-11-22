<?php
defined('_JEXEC') or die('No direct access!'); 
$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$lang_code=$this->language->lang_code;
$sef=$this->language->sef;

//var_dump($this->language);die();
?>
<div id="tab_<?php echo $lang_code; ?>" class="tab<?php echo ($this->lang_tag==$lang_code?" active":""); ?>">
	<!-- Название города -->
	<div class="formfield">
		<label for="ct-name-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_CITY_NAME'); ?></label>
		<input type="text" id="ct-name-<?php echo $lang_code; ?>" name="city[name_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getName($lang_code)); ?>">
	</div>
	<!-- Алиас -->
	<div class="formfield">
		<label for="ct-alias-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_CITY_ALIAS'); ?></label>
		<input type="text" id="ct-alias-<?php echo $lang_code; ?>" name="city[alias_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('alias',$lang_code)); ?>">
	</div>
	<!-- Описание города -->
	<div class="formfield">
		<label for="ct-description-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_CITY_DESCRIPTION'); ?></label>
		<textarea id="ct-description-<?php echo $lang_code; ?>" name="city[description_<?php echo $lang_code; ?>]" class="required"><?php echo ($isNew?'':$this->item->getlangvar('description',$lang_code)); ?></textarea>
	</div>
	<!-- H1 -->
	<div class="formfield">
		<label for="ct-h1-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_CITY_H1'); ?></label>
		<input type="text" id="ct-h1-<?php echo $lang_code; ?>" name="city[h1_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('h1',$lang_code)); ?>">
	</div>
	<!-- title -->
	<div class="formfield">
		<label for="ct-title-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_CITY_TITLE'); ?></label>
		<input type="text" id="ct-title-<?php echo $lang_code; ?>" name="city[title_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('title',$lang_code)); ?>">
	</div>
	<!-- META Description -->
	<div class="formfield">
		<label for="ct-metadesc-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_CITY_METADESC'); ?></label>
		<input type="text" id="ct-metadesc-<?php echo $lang_code; ?>" name="city[metadesc_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('metadesc',$lang_code)); ?>">
	</div>	
	<!-- META Keywords -->
	<div class="formfield">
		<label for="ct-metakeyw-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_CITY_METAKEYW'); ?></label>
		<input type="text" id="ct-metakeyw-<?php echo $lang_code; ?>" name="city[metakeyw_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':$this->item->getlangvar('metakeyw',$lang_code)); ?>">
	</div>
	<!-- META Robots -->
	<div class="formfield">
		<label for="ct-metarobots-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_CITY_METAROBOTS'); ?></label>
		<?php $value=($isNew?'':$this->item->getlangvar('metarobots',$lang_code)); ?>
		<select id="ct-metarobots-<?php echo $lang_code; ?>" name="city[metarobots_<?php echo $lang_code; ?>]" class="required">
			<option value="0"<?php echo ($value==0?'selected':'') ?>>Index, Follow</option>
			<option value="1"<?php echo ($value==1?'selected':'') ?>>Noindex, Follow</option>
			<option value="2"<?php echo ($value==2?'selected':'') ?>>Index, Nofollow</option>
			<option value="3"<?php echo ($value==3?'selected':'') ?>>Noindex, Nofollow</option>
		</select>
	</div>

</div>
