<?php
/**
 * Virtuemart categories selection
 * ------------------------------------------------------------------------
 * author    Andrii Biriev
 * copyright Copyright (C) 2014 konservs.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl.html GNU/GPL
 * Websites: http://konservs.com/
 * Technical Support: http://konservs.com/
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();
jimport('joomla.form.formfield');

/**
 * A Virtuemart categories selection
 * @author Andrii Biriev
 */
class JFormFieldVmCatMultiSelect extends JFormField{
	protected $type = 'VmCatMultiSelect';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	function getInput() {
		VmConfig::loadJLang('com_virtuemart', false);
		//Get category list...
		$categorylist=ShopFunctions::categoryListTree(array());
		//We nees some JS...
		$js='';
		$js.='var vmcatmultiselect_categorylist=\''.$categorylist.'\';';
		$js.='var vmcatmultiselect_val;';
		$js.='var vmcatmultiselect_tbl;';
		$js.='var vmcatmultiselect_count=0;';
		//Refresh textarea by JS elements
		$js.='function vmcatmultiselect_refresh(){'.PHP_EOL.
			'var trs=vmcatmultiselect_tbl.getElementsByTagName(\'tr\');'.PHP_EOL.
			'var text=\'\';'.PHP_EOL.
			'for(var i=0, n=trs.length; i<n; i++){'.PHP_EOL.
			'	var id=(trs[i].id).substring(6);'.PHP_EOL.
			'	var cat=document.getElementById(\'vmcat-cat-\'+id);'.PHP_EOL.
			'	var price=document.getElementById(\'vmcat-price-\'+id);'.PHP_EOL.
			'	console.log(\'cat select:\');'.PHP_EOL.
			'	console.log(cat);'.PHP_EOL.
			'	console.log(price);'.PHP_EOL.
			'	if((cat)&&(price)){'.PHP_EOL.
			'		text+=cat.value+\'/\'+price.value+\'\\n\';'.PHP_EOL.
			'		}'.PHP_EOL.
			'	}'.PHP_EOL.
			'vmcatmultiselect_val.value=text;'.PHP_EOL.
			'vmcatmultiselect_val.innerHTML=text;'.PHP_EOL.
			'}'.PHP_EOL.PHP_EOL;
		//Adding categories to list
		$js.='function vmcatmultiselect_add(cat,price){'.PHP_EOL.
			'vmcatmultiselect_count++;'.PHP_EOL.
			'var tr=document.createElement(\'tr\');'.PHP_EOL.
			'tr.setAttribute(\'id\', \'vmcat-\'+vmcatmultiselect_count);'.PHP_EOL.
			'var td1=document.createElement(\'td\');'.PHP_EOL.
			'var td2=document.createElement(\'td\');'.PHP_EOL.
			'var td3=document.createElement(\'td\');'.PHP_EOL.
			'tr.appendChild(td1);'.PHP_EOL.
			'tr.appendChild(td2);'.PHP_EOL.
			'tr.appendChild(td3);'.PHP_EOL.
			'td1.innerHTML= \'<select>\'+vmcatmultiselect_categorylist+\'</select>\';'.PHP_EOL.
			'var select=td1.firstChild;'.PHP_EOL.
			'select.value=cat;'.PHP_EOL.
			'select.setAttribute(\'id\', \'vmcat-cat-\'+vmcatmultiselect_count);'.PHP_EOL.
			'select.onchange=vmcatmultiselect_refresh;'.PHP_EOL.
			'td2.innerHTML = \'<input type="text" value="\'+price+\'">\';'.PHP_EOL.
			'var price=td2.firstChild;'.PHP_EOL.
			'price.onchange=vmcatmultiselect_refresh;'.PHP_EOL.
			'price.setAttribute(\'id\', \'vmcat-price-\'+vmcatmultiselect_count);'.PHP_EOL.
			'td3.innerHTML = \'<a href="#" class="vmicon vmicon-16-remove price-remove" onclick="vmcatmultiselect_remove(\'+vmcatmultiselect_count+\'); vmcatmultiselect_refresh(); return false;"></a>\';'.PHP_EOL.
			'vmcatmultiselect_tbl.appendChild(tr);'.PHP_EOL.
			'}'.PHP_EOL.PHP_EOL;
		//Remove tr (category row) element
		$js.='function vmcatmultiselect_remove(id){'.PHP_EOL.
			'var tr=document.getElementById(\'vmcat-\'+id);'.PHP_EOL.
			'if(tr==undefined){return false;}'.PHP_EOL.
			'tr.parentNode.removeChild(tr);'.PHP_EOL.
			'}';
		//Load textarea rules & create elements...
		$js.='function vmcatmultiselect_load(){'.PHP_EOL.
			'vmcatmultiselect_val=document.getElementById(\''.$this->id.'\');'.PHP_EOL.
			'vmcatmultiselect_tbl=document.getElementById(\'vmcatmultiselect_tbl\');'.PHP_EOL.
			'var val=vmcatmultiselect_val.value;'.PHP_EOL.
			'var lines = val.match(/[^\r\n]+/g);'.PHP_EOL.
			'if(lines instanceof Array){'.PHP_EOL.
			'	lines.forEach(function(element,index,array){'.PHP_EOL.
			//'		window.console&&console.log(\'Processing "\'+element+\'"...\');'.PHP_EOL.
			'		var x=element.split("/");'.PHP_EOL.
			'		vmcatmultiselect_add(x[0],x[1]);'.PHP_EOL.
			'		});'.PHP_EOL.
			'	}'.PHP_EOL.
			'vmcatmultiselect_val.style.display=\'none\';'.PHP_EOL.
			'}';
		//Onload run our function
		$js.='window.onload=vmcatmultiselect_load;';
		//Our HTML...
		$html='<textarea id="'.$this->id.'" name="'.$this->name.'">'.htmlspecialchars($this->value).'</textarea>';
		$html.='<table id="vmcatmultiselect_tbl" class="adminlist" cellspacing="0" cellpadding="0"><tr>'.
			'<th>'.JText::_('VMSHIPMENT_CATEGORIES_CATEGORY').'</th>'.
			'<th>'.JText::_('VMSHIPMENT_CATEGORIES_PRICE').'</th>'.
			'<th>'.JText::_('VMSHIPMENT_CATEGORIES_ACTION').'</th>'.
			'</tr></table>';
		$html.='<button type="button" onclick="vmcatmultiselect_add(0,0); vmcatmultiselect_refresh(); return false;">+</button>';
		$html.='<script>'.$js.'</script>';
		//$html.='<pre>'.var_export($this,true).'</pre>';
		return $html;
		}
	}

