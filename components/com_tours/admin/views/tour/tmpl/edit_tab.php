<?php
defined('_JEXEC') or die('No direct access!'); 
$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$lang_code=$this->language->lang_code;
$sef=$this->language->sef;
//var_dump($lang_code);die();
echo('<div id="tab_'.$sef.'" class="tab'.($this->lang_tag==$lang_code?" active":"").'">');

//Exhibition name
echo('<div class="formfield">');
echo('<label for="exb-name-'.$sef.'">'.JText::_('COM_EXHIBITIONS_EXHIBITION_NAME').'</label>');
echo('<input type="text" id="exb-name-'.$sef.'" name="exhibition[name_'.$sef.']" class="required" value="'.($isNew?'':$this->item->getName($lang_code)).'">');
echo('</div>');

//Exhibition alias
echo('<div class="formfield">');
echo('<label for="exb-alias-'.$sef.'">'.JText::_('COM_EXHIBITIONS_EXHIBITION_ALIAS').'</label>');
echo('<input type="text" id="exb-alias-'.$sef.'" name="exhibition[alias_'.$sef.']" value="'.($isNew?'':$this->item->getAlias($lang_code)).'">');
echo('</div>');

$editor =JFactory::getEditor();
$fulltext=($isNew?'':$this->item->getFullText($lang_code));
$getIntrotext=($isNew?'':$this->item->getIntrotext($lang_code));
echo($editor->display('exhibition[fulltext_'.$sef.']', $fulltext,'100%','300px',80,80));
echo('<div style="clear: both;"></div>');

echo('<div class="width-50 fltlft">');
echo('<div class="textarea_wrapper">');
echo('<label for="exb-metakeyw-'.$sef.'">'.JText::_('COM_EXHIBITIONS_META_TEG_KEYWORDS').'</label>');
echo('<textarea id="exb-metakeyw-'.$sef.'" name="exhibition[metakeyw_'.$sef.']" class="width-100">'.($isNew?'':$this->item->getMetaKeyw($lang_code)).'</textarea>');
echo('</div>');//textarea_wrapper
echo('</div>');//width-50

echo('<div class="width-50 fltrt">');
echo('<div class="textarea_wrapper">');
echo('<label for="exb-metadesc-'.$sef.'">'.JText::_('COM_EXHIBITIONS_META_TEG_DESCRIPTION').'</label>');
echo('<textarea id="exb-metadesc-'.$sef.'" name="exhibition[metadesc_'.$sef.']" class="width-100">'.($isNew?'':$this->item->getMetaDesc($lang_code)).'</textarea>');
echo('</div>');//textarea_wrapper
echo('</div>');//width-50

echo('<div style="clear: both;"></div>');

echo('</div>');
