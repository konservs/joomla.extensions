<?php
defined('_JEXEC') or die('No direct access!'); 
$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$lang_code=$this->language->lang_code;
$sef=$this->language->sef;
//var_export($this->item);die();

echo('<div id="tab_'.$sef.'" class="tab'.($this->lang_tag==$lang_code?" active":"").'">');

//Exhibition name
echo('<div class="formfield">');
echo('<label for="ct-name-'.$sef.'">'.JText::_('COM_EXHIBITIONS_CITY_NAME').'</label>');
echo('<input type="text" id="ct-name-'.$sef.'" name="city[name_'.$sef.']" class="required" value="'.($isNew?'':$this->item->getName($lang_code)).'">');
echo('</div>');
echo('</div>');
