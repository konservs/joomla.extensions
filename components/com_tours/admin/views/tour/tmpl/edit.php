<?php
defined('_JEXEC') or die('No direct access!'); 
jimport('brilliant_exhibitions.language');
jimport('brilliant_exhibitions.industries');
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

//echo('<pre>'); var_dump($this->item); echo('</pre>');
//echo('<pre>'); var_dump($this->cities); echo('</pre>');

//TODO Translations
JFactory::getDocument()->addScriptDeclaration("
	jQuery(document).ready(function(){
		var options = { dayNamesMin		: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'], 
						monthNames 		: [ 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь' ],
						dateFormat      : 'yy-mm-dd'
					};
		jQuery('.datepicker' ).datepicker(options);
		jQuery('#industries').select2({width : 'element'});		

	});
	//function for image insertion
	function jInsertFieldValue(value, id){			
		var old_value = jQuery('#' + id).val();
		if (old_value != value) {
			var elem = jQuery('#' + id);
			elem.val(value);
			elem.trigger('change');
			if (typeof(elem.get(0).onchange) === 'function'){
				elem.get(0).onchange();
			}
		}
	}
");

//---------------------------------------------
// Get all languages used in joomla
//---------------------------------------------
$languages = BLanguages::getInstance();
$lang_list = $languages->languages_get_all();

$id=empty($this->item)?0:(int)$this->item->id;
$isNew = ($id == 0);
$faction=JRoute::_('index.php?option=com_exhibitions&layout=edit&exb_id='.$id);

$editor =JFactory::getEditor();
//die(var_export($editor));

//---------------------------------------------
// Get current language
//---------------------------------------------
$lang =JFactory::getLanguage();
$curr_lang = $lang->getName();


//Get exhibition city ID if item exists
if(!$isNew){
	$exb_city=$this->item->getCity();
	$exb_city_id=empty($exb_city)?0:$exb_city->id;
}


echo('<form action="'.$faction.'" method="post" name="adminForm" id="exhibitions-form">');
//---------------------------------------------
// Main area = fields with translations
//---------------------------------------------
echo('<div class="width-60 fltlft">');
echo('<div id="exb_mainwrapper">');
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
//---------------------------------------------
// Service fields
//---------------------------------------------
echo('<div class="width-40 fltrt">');
echo('<div id="exb_panelwrapper">');
//Cities list
echo('<div class="formfield">');
echo('<label for="exb-city" class="hasTip" title="City::Plese, select the city.">'.JText::_('COM_EXHIBITIONS_EXHIBITION_CITY').'</label>');
echo('<select id="exb-city" name="exhibition[city]">');
foreach($this->cities as $city){
	echo('<option'.
		' value="'.$city->id.'"'.
		($exb_city_id==$city->id?' selected':'').
		'>');
	echo($city->getName());
	echo('</option>');
	}
echo('</select>');
echo('</div>');
//Exhibition start
//TODO: add calendar
echo('<div class="formfield">');
echo('<label for="exb-start" class="hasTip" title="Exhibition Start::Plese, select the date.">'.JText::_('COM_EXHIBITIONS_EXHIBITION_START').'</label>');
echo('<input type="text" id="exb-start" class="datepicker" name="exhibition[date_start]" value="'.($isNew?'':$this->item->getStartdate()->format('Y-m-d')).'">');
echo('</div>');
//Exhibition end
//TODO: add calendar
echo('<div class="formfield">');
echo('<label for="exb-end" class="hasTip" title="Exhibition End::Plese, select the date.">'.JText::_('COM_EXHIBITIONS_EXHIBITION_END').'</label>');
echo('<input type="text" id="exb-end" class="datepicker" name="exhibition[date_end]" value="'.($isNew?'':$this->item->getEnddate()->format('Y-m-d')).'">');
echo('</div>');
//Intro image
//TODO: add image picker
$clr_title=JText::_('COM_EXHIBITIONS_CLEAR_IMAGE');
$add_title=JText::_('COM_EXHIBITIONS_OPEN_IMAGE');
$class="";
echo('<div class="formfield">');
echo('<label for="exb-introimg">'.JText::_('COM_EXHIBITIONS_EXHIBITION_INTRO_IMAGE').'</label>');
echo('<input type="text" id="exb-introimg" name="exhibition[introimg]" value="'.($isNew?'':$this->item->getIntroimg()).'">');
echo('<a class="modal exb_button" href="'.JURI::base().'index.php?option=com_media&view=images&tmpl=component&asset=com_exhibitions&fieldid=exb-introimg" title="'.$add_title.'" rel="{handler: \'iframe\', size: {x: 880, y: 570}}">'.$add_title.'</a>');
echo('<a onclick="jInsertFieldValue(\'\', \'exb-introimg\'); return false;" href="#" title="" class="exb_button hasTooltip" title="'.$clr_title.'">'.$clr_title.'</a>');
echo('</div>');
//Full image
//TODO: add image picker
echo('<div class="formfield">');
echo('<label for="exb-fullimg">'.JText::_('COM_EXHIBITIONS_EXHIBITION_FULL_IMAGE').'</label>');
echo('<input type="text" id="exb-fullimg" name="exhibition[fullimg]" value="'.($isNew?'':$this->item->getFullimg()).'">');
echo('<a class="modal exb_button" href="'.JURI::base().'index.php?option=com_media&view=images&tmpl=component&asset=com_exhibitions&fieldid=exb-fullimg" title="'.$add_title.'" rel="{handler: \'iframe\', size: {x: 880, y: 570}}">'.$add_title.'</a>');
echo('<a onclick="jInsertFieldValue(\'\', \'exb-fullimg\'); return false;" href="#" title="" class="exb_button hasTooltip" title="'.$clr_title.'">'.$clr_title.'</a>');
echo('</div>');
//Exhibition industries
//TODO: add industries picker!
echo('<div class="formfield">');
echo('<label for="exb-industries">'.JText::_('COM_EXHIBITIONS_EXHIBITION_INDUSTRIES').'</label>');
$ind=BExhibitionsIndustries::getInstance();
$ind_list=$ind->industries_get_all();
//Get all industries ids of exhibbition;
if($id!=0){
	$inds=$this->item->getIndustries();
	foreach ($inds as $ind){
		$inds_ids[]=$ind->id;
	}
}
echo('<select multiple="multiple" id="industries" name="exhibition[industries][]">');
foreach($ind_list as $inustry){	
	echo('<option value="'.$inustry->id.'" '.(in_array($inustry->id, $inds_ids)?' selected':'').'>');
	echo($inustry->name[$lang->getTag()]);
	echo('</option>');
}
echo('</select>');
echo('</div>');
//
echo('</div>'); //end of exb_panelwrapper
echo('</div>'); //end of service fields
//---------------------------------------------
// Service fields
//---------------------------------------------
echo('<input type="hidden" name="task" value="" />');
echo(JHtml::_('form.token'));
echo('</form>');
