<?php 
defined('_JEXEC') or die;

//Callback window
echo('<div class="callback-form">');
echo('<div class="description">'.JText::_('MOD_BRILL_CALLBACK_DESCR').'</div>');

echo('<form action="'.JRoute::_('index.php?option=com_brillcallback&view=sendform').'" method="post">');

//echo('<div class="formfield">');
//echo('<label for="brillcallback_name">'.JText::_('MOD_BRILL_CALLBACK_NAME').'</label>');
//echo('<input type="text" name="name" id="brillcallback_name">');
//echo('</div>');

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
