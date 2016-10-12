<?php
defined('_JEXEC') or die('No direct access!'); 
$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$lang_code=$this->language->lang_code;
$sef=$this->language->sef;

//var_dump($this->language);die();
?>
<div id="tab_<?php echo $lang_code; ?>" class="tab<?php echo ($this->lang_tag==$lang_code?" active":""); ?>">
	<!-- Название офиса -->
	<div class="formfield">
		<label for="of-name-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_OFFICE_NAME'); ?></label>
		<input type="text" id="of-name-<?php echo $lang_code; ?>" name="office[name_<?php echo $lang_code; ?>]" class="required" value="<?php echo ($isNew?'':htmlspecialchars($this->item->getName($lang_code))); ?>">
	</div>

	<!-- Адрес -->
	<div class="formfield">
		<label for="of-address-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_OFFICE_ADDRESS'); ?></label>
		<input type="text" id="of-address-<?php echo $lang_code; ?>" name="office[address_<?php echo $lang_code; ?>]" value="<?php echo ($isNew?'':htmlspecialchars($this->item->getlangvar('address',$lang_code))); ?>">
	</div>

	<!-- Телефон -->
	<div class="formfield">
		<label for="of-phone-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_OFFICE_PHONE'); ?></label>
		<input type="text" id="of-phone-<?php echo $lang_code; ?>" name="office[phone_<?php echo $lang_code; ?>]" value="<?php echo ($isNew?'':htmlspecialchars($this->item->getlangvar('phone',$lang_code))); ?>">
	</div>

	<!-- Сайт -->
	<div class="formfield">
		<label for="of-site-<?php echo $lang_code; ?>"><?php echo JText::_('COM_REGOFFICES_OFFICE_SITE'); ?></label>
		<input type="text" id="of-site-<?php echo $lang_code; ?>" name="office[site_<?php echo $lang_code; ?>]" value="<?php echo ($isNew?'':htmlspecialchars($this->item->getlangvar('site',$lang_code))); ?>">
	</div>



</div>
