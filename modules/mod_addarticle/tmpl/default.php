<?php 
defined('_JEXEC') or die;
$doc=JFactory::getDocument();

//Callback JS
$js=
	'jQuery.noConflict();'.PHP_EOL.
	'jQuery(document).ready(function(){'.PHP_EOL.
	'	var popup=jQuery(\'.callback-popup\'),'.PHP_EOL.
	'	mask=jQuery(\'<div/>\',{class: \'callback-mask\'}).appendTo(\'body\'),'.PHP_EOL.
	'		center=function(){'.PHP_EOL.
	'		var T=jQuery(window).height() / 2 - popup.height() / 2 + jQuery(window).scrollTop(),'.PHP_EOL.
	'		    L=jQuery(window).width() / 2 - popup.width() / 2;'.PHP_EOL.
	'		popup.css({top: T,left: L});'.PHP_EOL.
	'       	};'.PHP_EOL.
	'	jQuery(\'.callback-button\').click(function(e){'.PHP_EOL.
	'		popup.show();'.PHP_EOL.
	'		mask.show();'.PHP_EOL.
	'		center();'.PHP_EOL.
	'   		});'.PHP_EOL.
	'	jQuery(\'.closebtn\',popup).click(function(e){'.PHP_EOL.
	'		popup.hide();'.PHP_EOL.
	'		mask.hide();'.PHP_EOL.
	'		});'.PHP_EOL.
	'	center();'.PHP_EOL.
	'	jQuery(window).scroll(center);'.PHP_EOL.
	'	jQuery(window).resize(center);'.PHP_EOL.
	'	});';
$doc->addScriptDeclaration($js);
//Callbacl css
$css='';

//Callback button
echo('<div class="callback-button">'.JText::_('MOD_BRILL_CALLBACK_CALLBACK').'</div>');

//Callback window
echo('<div class="callback-popup" style="display: none;">');
echo('<div class="closebtn">'.JText::_('MOD_BRILL_CALLBACK_CLOSE').'</div>');
echo('<div class="title">'.JText::_('MOD_BRILL_CALLBACK_CALLBACK').'</div>');
echo('<div class="description">'.JText::_('MOD_BRILL_CALLBACK_DESCR').'</div>');
echo('<form action="'.JRoute::_('index.php?option=com_brillcallback&view=sendform').'" method="post">');
echo('<div class="formfield">');
echo('<label for="brillcallback_name">'.JText::_('MOD_BRILL_CALLBACK_NAME').'</label>');
echo('<input type="text" name="name" id="brillcallback_name">');
echo('</div>');
echo('<div class="formfield">');
echo('<label for="brillcallback_phone">'.JText::_('MOD_BRILL_CALLBACK_PHONE').'</label>');
echo('<input type="text" name="phone" id="brillcallback_phone">');
echo('</div>');
echo('<div class="formfield">');
echo('<label for="brillcallback_comment">'.JText::_('MOD_BRILL_CALLBACK_COMMENT').'</label>');
echo('<textarea name="name" id="brillcallback_comment"></textarea>');
echo('</div>');
echo('<input type="submit" class="submit" value="'.JText::_('MOD_BRILL_CALLBACK_SUBMIT').'">');
echo('</form>');
echo('</div>');
