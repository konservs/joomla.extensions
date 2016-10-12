<?php 
/**
 * Шаблон модуля экспресс доставки (10-14 дней).
 *
 * Позволяет выполнить просчёт стоимости доставки и отправить заявку
 * на почту.
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copyright (c) 2015 Brilliant IT, http://it.brilliant.ua
 */
defined('_JEXEC') or die('No direct access!');
$doc=JFactory::getDocument();
//Generate some URLs
$url_action=JRoute::_('index.php?option=com_calculator&view=calculator');
$url_action_json=JRoute::_('index.php?option=com_calculator&view=calculator&format=json');
//Get rules.
jimport('chinacalc.chinacalc');
$chinacalc=BChinaCalc::getInstance();
$expressrules=$chinacalc->getexpressrules();
//Forming script..
$script=PHP_EOL.PHP_EOL.PHP_EOL;
$script.='//=================================================================='.PHP_EOL;
$script.='// This code is made by Brilliant IT guys (http://it.brilliant.ua). '.PHP_EOL;
$script.='//=================================================================='.PHP_EOL;
$script.=''.PHP_EOL;
$script.='//   --==  [ Some global variables ]  ==--'.PHP_EOL;
$script.='window.chinaexpressajax=\''.$url_action_json.'\';'.PHP_EOL;
$script.='//   --==  [ Languages ]  ==--'.PHP_EOL;
$script.='window.brilllang={};'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_PLEASECALL=\''.JText::_('MOD_CHINACALCAVIA_PLEASECALL').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_USD=\''.JText::_('MOD_CHINACALCAVIA_AVIA_USD').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_USDPERKG=\''.JText::_('MOD_CHINACALCAVIA_AVIA_USDPERKG').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_EXPRESS_TARIF=\''.JText::_('MOD_CHINACALCAVIA_EXPRESS_TARIF').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_EXPRESS_FINAL=\''.JText::_('MOD_CHINACALCAVIA_EXPRESS_FINAL').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_EXPRESS_MASS_UNKNOWN=\''.JText::_('MOD_CHINACALCAVIA_EXPRESS_MASS_UNKNOWN').'\';'.PHP_EOL;
$script.='//   --==  [ Good types ]  ==--'.PHP_EOL;
$script.='window.chinaexpressrules=[];'.PHP_EOL;
foreach($expressrules as $er){
	$script.=
		'window.chinaexpressrules['.$er->id.']={'.
		'id: '.$er->id.', '.
		'mass_from: '.$er->mass_from.', '.
		'mass_to: '.$er->mass_to.', '.
		'cost_weight: '.$er->cost_weight.
		'};'.PHP_EOL;
	}
$script.='//   --==  [ Calculation function ]  ==--
function chinaexpresschange(){
	window.console&&console.log(\'[China Calculator Express]: some fields changed. Calculating!\');
	//Get mass
	var mass=jQuery(\'#chinacalc_express_mass\').val();
	mass=mass.replace(/,/g, \'.\');
	if((mass==undefined)||(mass==\'\')){
		mass=0;
		}
	var massi=-1;
	jQuery(window.chinaexpressrules).each(function(i,mi){
		if((mi!=undefined)&&(mass>=mi.mass_from)&&(mass<mi.mass_to)){
			massi=i;
			}
		});
	window.console&&console.log(\'[China Calculator Express]: mass=\'+mass+\', massi=\'+massi+\'...\');	
	var htmltext=\'\';
	//Known mass
	if(massi>=0){
		cost_weight=window.chinaexpressrules[massi].cost_weight;
		cost_final=cost_weight * mass;
		window.console&&console.log(\'[China Calculator Express]: cost=\'+cost_final+\' (\'+cost_weight+\' per kg).\');

		var html_cost1=cost_weight+brilllang.MOD_CHINACALCAVIA_AVIA_USDPERKG;
		var html_cost2=cost_final+brilllang.MOD_CHINACALCAVIA_AVIA_USD;

		htmltext+=\'<p><b>\'+brilllang.MOD_CHINACALCAVIA_EXPRESS_TARIF+\'</b>\'+html_cost1+\'</p>\';
		htmltext+=\'<p><b>\'+brilllang.MOD_CHINACALCAVIA_EXPRESS_FINAL+\'</b>\'+html_cost2+\'</p>\';
		}
	//Unknown denticity
	else{
		htmltext+=\'<p>\'+brilllang.MOD_CHINACALCAVIA_EXPRESS_MASS_UNKNOWN+\'</p>\';
		}
	jQuery(\'#chinacalc_express_result\').html(htmltext);
	}
//   --==  [ Add events listeners ]  ==--
jQuery(document).ready(function(){
	window.console&&console.log(\'[China Calculator Express]: the document is ready!\');
	jQuery(\'#chinacalc_express_mass\').change(chinaexpresschange);
	chinaexpresschange();
	jQuery(\'#chinacalc_express_form\').submit(function(){
		window.console&&console.log(\'[China Calculator Express]: submiting form. Checking form...\');
		//Get form data and check it...
		var formok=true;
		var ff_phone=jQuery(\'#chinacalc_express_phone\').val();
		if(ff_phone==\'\'){
			jQuery(\'#chinacalc_express_phone\').focus();
			jQuery(\'#chinacalc_express_phone\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_express_phone\').removeClass(\'error\');
			}
		var ff_email=jQuery(\'#chinacalc_express_email\').val();
		if(ff_email==\'\'){
			jQuery(\'#chinacalc_express_email\').focus();
			jQuery(\'#chinacalc_express_email\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_express_email\').removeClass(\'error\');
			}
		var ff_name=jQuery(\'#chinacalc_express_name\').val();
		if(ff_name==\'\'){
			jQuery(\'#chinacalc_express_name\').focus();
			jQuery(\'#chinacalc_express_name\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_express_name\').removeClass(\'error\');
			}
		var ff_volume=jQuery(\'#chinacalc_express_volume\').val();
		if(ff_volume==\'\'){
			jQuery(\'#chinacalc_express_volume\').focus();
			jQuery(\'#chinacalc_express_volume\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_express_volume\').removeClass(\'error\');
			}
		var ff_mass=jQuery(\'#chinacalc_express_mass\').val();
		if(ff_mass==\'\'){
			jQuery(\'#chinacalc_express_mass\').focus();
			jQuery(\'#chinacalc_express_mass\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_express_mass\').removeClass(\'error\');
			}
		if(!formok){
			return false;
			}
		//Do AJAX request...
		window.console&&console.log(\'[China Calculator Express]: Performing AJAX request...\');
		var data={
			goodstype: ff_goodstype,
			mass: ff_mass,
			email: ff_email,
			name: ff_name,
			phone: ff_phone
			};
		jQuery.ajax({
			url: window.chinaexpressajax,
			data: data,
			format: "json",
			success:function(data){
				window.console&&console.log(\'[China Calculator Express]: AJAX request success!\');
				window.console&&console.log(data);

				if(data.errorcode==0){
					ga(\'send\', \'event\', \'estimator\', \'click\', \'calculated\', 0);
					jQuery(\'#chinacalc_express_form\').hide();
					jQuery(\'#chinacalc_express_ajax\').addClass(\'success\');
					jQuery(\'#chinacalc_express_ajax\').html(data.errormessage);
					}
				else{
					jQuery(\'#chinacalc_express_ajax\').addClass(\'error\');
					jQuery(\'#chinacalc_express_ajax\').html(data.errormessage);
					}
				},
			error:function(){
				window.console&&console.log(\'[China Calculator Express]: AJAX request failed!\');
				//alert(\'Error!\');
				}
			});
		return false;
		});
	});';

$script.=PHP_EOL;
$script.='//=================================================================='.PHP_EOL;
$script.='// End of cool code by cool guys ;-)'.PHP_EOL;
$script.='//=================================================================='.PHP_EOL;

$doc=JFactory::getDocument();
$doc->addScriptDeclaration($script);
?>
<div class="chinacalculator-avia">
	<form class="chinacalculator-avia-form" method="POST" action="<?php echo $url_action; ?>">
		<div class="w50 floatleft">
			<div class="formfield" id="formfield_goodstype">
				<label for="chinacalc_express_goodstype"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_GOODSTYPE'); ?></label>
				<input type="text" id="chinacalc_express_count" name="count" class="textinput">
			</div>
			<div class="formfield" id="formfield_mass">
				<label for="chinacalc_express_mass"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_MASS'); ?></label>
				<input type="text" id="chinacalc_express_mass" name="mass" class="textinput">
			</div>
		</div>
		<div class="w50 floatleft">
			<div class="formfield" id="formfield_name">
				<label for="chinacalc_express_name"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_NAME'); ?></label>
				<input type="text" id="chinacalc_express_name" name="name" class="textinput">
			</div>
			<div class="formfield" id="formfield_email">
				<label for="chinacalc_express_email"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_EMAIL'); ?></label>
				<input type="email" id="chinacalc_express_email" name="email" class="textinput">
			</div>
			<div class="formfield" id="formfield_phone">
				<label for="chinacalc_express_phone"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_PHONE'); ?></label>
				<input type="text" id="chinacalc_express_phone" name="phone" class="textinput">
			</div>
		</div>
		<div class="clear"></div>

		<div id="chinacalc_express_result"></div>
		<div id="chinacalc_express_submit">
			<input type="hidden" name="calctype" value="1"/>
			<input type="submit" value="<?php echo JText::_('MOD_CHINACALCAVIA_AVIA_SUBMIT'); ?>">
		</div>
	</form>
</div>
