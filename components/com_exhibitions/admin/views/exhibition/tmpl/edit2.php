<?php
defined('_JEXEC') or die('No direct access!'); 
echo('<pre>'); var_dump($this->item); echo('</pre>');
//echo('<pre>'); var_dump($this->cities); echo('</pre>');
$editor =&JFactory::getEditor();
$faction=JRoute::_('index.php?option=com_exhibitions&layout=edit&exb_id='.(int)$this->item->id);
$isNew = ($this->item->id == 0);

//Get exhibition city ID if item exists
if(!$isNew){
	$exb_city=$this->item->getCity();
	$exb_city_id=empty($exb_city)?0:$exb_city->id;
}


echo('<form action="'.$faction.'" method="post" name="adminForm" id="exhibitions-form">');
//echo('<fieldset>');
//echo('<legend>'.JText::_('COM_EXHIBITIONS_EXHIBITION_DETAILS').'</legend>');


//---------------------------------------------
// General fields
//---------------------------------------------
//Intro image
/*
echo('<div class="formfield">');
echo('<label for="exb-intro-image">Intro image</label>');
echo('<input id="exb-intro-image" type="file" name="introimg">');
echo('</div>');
*/

echo('<fieldset>');
	foreach ($this->form->getFieldset() as $field){
		echo('<div>');
		echo $field->label;
		echo $field->input;
		echo('</div>');
	}
echo('</fieldset>');

//Full image
/*
echo('<div class="formfield">');
echo('<label for="exb-full-image">Full image</label>');
echo('<input id="exb-full-image" type="text" name="fullimg">');
echo('</div>');
*/
//Cities list
echo('<div class="formfield">');
echo('<label for="exb-city">City</label>');
echo('<select id="exb-city" name="city">');
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

//echo('</fieldset>');

//---------------------------------------------
// Fields with translations
//---------------------------------------------
echo('<hr>tabs for languages....');


//---------------------------------------------
// TAB 1
//---------------------------------------------
echo('<div id="tab_1">');
//@params $field_name, $default_value, $width, $height;
//TODO value
echo $editor->display('fulltext_ru', '');
echo('</div>');


//---------------------------------------------
// Service fields
//---------------------------------------------
echo('<div>');
echo('<input type="hidden" name="task" value="" />');
echo JHtml::_('form.token');
echo('</div>');


echo('</form>');


