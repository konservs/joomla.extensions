<?php 
defined('_JEXEC') or die;
//Callback window
echo('<div class="feedback">');
//echo('<div class="description">'.JText::_('MOD_BRILL_FEEDBACK_DESCR').'</div>');
$id_pref='brillfb_';
echo('<form action="'.JRoute::_('index.php?option=com_brillcallback&view=sendform').'" method="post">');
echo('<div class="formfield">');
echo('<label for="'.$id_pref.'name">'.JText::_('MOD_BRILL_CALLBACK_NAME').'</label>');
echo('<input type="text" name="name" id="'.$id_pref.'name">');
echo('</div>');

if(false){
	echo('<div class="formfield">');
	echo('<label for="'.$id_pref.'phone">'.JText::_('MOD_BRILL_CALLBACK_PHONE').'</label>');
	echo('<input type="text" name="phone" id="'.$id_pref.'phone">');
	echo('</div>');
	}

if(true){
	echo('<div class="formfield">');
	echo('<label for="'.$id_pref.'email">'.JText::_('MOD_BRILL_CALLBACK_EMAIL').'</label>');
	echo('<input type="text" name="email" id="'.$id_pref.'email">');
	echo('</div>');
	}

echo('<div class="formfield">');
echo('<label for="'.$id_pref.'comment">'.JText::_('MOD_BRILL_CALLBACK_COMMENT').'</label>');
echo('<textarea name="name" id="'.$id_pref.'comment"></textarea>');
echo('</div>');
echo('<input type="submit" class="button submit" value="'.JText::_('MOD_BRILL_CALLBACK_FEEDBACK_SUBMIT').'">');
echo('</form>');
echo('</div>');
