<?php
echo('<form action="" method="POST">');
echo('<div class="exhibitions-filter">');


echo('<div class="top">');
//Cities
echo('<div class="city"><select name="city">');
echo('<option value="0"'.($this->city_id==0?' selected="selected"':'').'>'.JText::_('COM_EXHIBITIONS_FILTER_CITY_ALL').'</option>');
foreach($this->cities as $city)
	echo('<option value="'.$city->id.'"'.($this->city_id==$city->id?' selected="selected"':'').'>'.$city->getName().'</option>');
echo('</select></div>');
//Industries
echo('<div class="industry"><select name="industry">');
echo('<option value="0"'.($this->industry_id==0?' selected="selected"':'').'>'.JText::_('COM_EXHIBITIONS_FILTER_INDUSTRY_ALL').'</option>');
foreach($this->industries as $ind)
	echo('<option value="'.$ind->id.'"'.($this->industry_id==$ind->id?' selected="selected"':'').'>'.$ind->getName().'</option>');
echo('</select></div>');
echo('</div>');


echo('<div class="bottom">');
//Buttons
echo('<div class="buttons">');
echo('<input type="submit" class="submitbutton button" value="'.JText::_('COM_EXHIBITIONS_FILTER_SEARCH').'">');
echo('<input type="reset" class="button" value="'.JText::_('COM_EXHIBITIONS_FILTER_CLEAR').'">');
echo('</div>');
//Dates selection...
echo('<div class="dates">');
//$this->date_from
echo('<input type="text" class="textinput" name="date-from"'.(empty($this->date_from)?'':' value="'.$this->date_from->format('Y-m-d').'"').'>');
echo('<span class="divisor">-</span>');
echo('<input type="text" class="textinput" name="date-to"'.(empty($this->date_to)?'':' value="'.$this->date_to->format('Y-m-d').'"').'>');
echo('</div>');


echo('</div>');//.bottom
echo('</div>');//.exhibitions-filter
echo('</form>');
?>