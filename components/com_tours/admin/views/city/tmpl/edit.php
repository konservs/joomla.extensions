<?php
defined('_JEXEC') or die('No direct access!'); 
JHTML::_('behavior.tooltip');

echo('Edit city here...');

/*
//---------------------------------------------
// Get all languages used in joomla
//---------------------------------------------
$languages = BLanguages::getInstance();
$lang_list = $languages->languages_get_all();

//var_export($this->item);die();
$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$faction=JRoute::_('index.php?option=com_exhibitions&view=city&layout=edit&ct_id='.$id);
//---------------------------------------------
// Get current language
//---------------------------------------------
$lang =JFactory::getLanguage();
$curr_lang = $lang->getName();

echo('<form action="'.$faction.'" method="post" name="adminForm" id="cities-form">');
//---------------------------------------------
// Main area = fields with translations
//---------------------------------------------
echo('<div class="width-60 fltlft">');
echo('<div id="ind_mainwrapper">');
if(count($lang_list)>1){
	echo('<div id="tabs">');
	//Outputing tabs switchers...
	foreach($lang_list as $language){
		$this->language=$language;
		echo('<a class="'.($lang->getTag()==$language->lang_code?"active":"").'" for="tab_'.$this->language->sef.'" href="#tab_'.$this->language->sef.'">'.$this->language->title_native.'</a>');
		}
	echo('</div>');
	}
echo('<div id="tabs_content">');
foreach($lang_list as $language){
	$this->language=$language;
	$this->lang_tag=$lang->getTag();
	echo($this->loadTemplate('tab'));
	}
echo('</div>');

echo('</div>'); //end of wrapper
echo('</div>'); //end of main area
echo('<input type="hidden" name="task" value="" />');
echo(JHtml::_('form.token'));
echo('</form>');
*/