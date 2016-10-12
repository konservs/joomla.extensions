<?php 
/**
 * Шаблон модуля калькулятора авиадоставки (10-14 дней).
 *
 * Позволяет выполнить просчёт стоимости доставки и отправить заявку
 * на почту.
 *
 * @author Andrii Biriev <a@konservs.com>
 * @copyright (c) 2015 Brilliant IT, http://it.brilliant.ua
 */
defined('_JEXEC') or die;
//Generate some URLs
$url_action=JRoute::_('index.php?option=com_calculator&view=calculator');
$url_action_json=JRoute::_('index.php?option=com_calculator&view=calculator&format=json');
//Get goods types...
jimport('chinacalc.chinacalc');
$chinacalc=BChinaCalc::getInstance();
$goodstype=$chinacalc->getgoods();
$denttype=$chinacalc->getdentrules();

//Forming script..
$script=PHP_EOL.PHP_EOL.PHP_EOL;
$script.='//=================================================================='.PHP_EOL;
$script.='// This code is made by Brilliant IT guys (http://it.brilliant.ua). '.PHP_EOL;
$script.='//=================================================================='.PHP_EOL;
$script.=''.PHP_EOL;
$script.='//   --==  [ Some global variables ]  ==--'.PHP_EOL;
$script.='window.chinaaviaajax=\''.$url_action_json.'\';'.PHP_EOL;
$script.='//   --==  [ Languages ]  ==--'.PHP_EOL;
$script.='window.brilllang={};'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_PLEASECALL=\''.JText::_('MOD_CHINACALCAVIA_PLEASECALL').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_USDPERKG=\''.JText::_('MOD_CHINACALCAVIA_AVIA_USDPERKG').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_USDPERITEM=\''.JText::_('MOD_CHINACALCAVIA_AVIA_USDPERITEM').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_TARIF=\''.JText::_('MOD_CHINACALCAVIA_AVIA_TARIF').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_COST=\''.JText::_('MOD_CHINACALCAVIA_AVIA_COST').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_FINAL=\''.JText::_('MOD_CHINACALCAVIA_AVIA_FINAL').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_USD=\''.JText::_('MOD_CHINACALCAVIA_AVIA_USD').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_MASS=\''.JText::_('MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_MASS').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_VOLUME=\''.JText::_('MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_VOLUME').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_COUNT=\''.JText::_('MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_COUNT').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_DENT=\''.JText::_('MOD_CHINACALCAVIA_AVIA_DENT').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_KGM3=\''.JText::_('MOD_CHINACALCAVIA_AVIA_KGM3').'\';'.PHP_EOL;
$script.='window.brilllang.MOD_CHINACALCAVIA_AVIA_DENT_UNKNOWN=\''.JText::_('MOD_CHINACALCAVIA_AVIA_DENT_UNKNOWN').'\';'.PHP_EOL;
$script.='//   --==  [ Good types ]  ==--'.PHP_EOL;
$script.='window.chinaaviagoodtypes=[];'.PHP_EOL;
foreach($goodstype as $gt){
	$script.=
		'window.chinaaviagoodtypes['.$gt->id.']={'.
		'id: '.$gt->id.', '.
		'name: "'.$gt->name.'", '.
		'cost_weight: '.$gt->cost_weight.', '.
		'cost_count: '.$gt->cost_count.', '.
		'insurance: '.$gt->insurance.
		'};'.PHP_EOL;
	}
$script.='//   --==  [ Dentisity types ]  ==--'.PHP_EOL;
$script.='window.chinaaviadenttypes=[];'.PHP_EOL;
foreach($denttype as $dt){
	$script.=
		'window.chinaaviadenttypes['.$dt->id.']={'.
		'id: '.$dt->id.', '.
		'dent_from: '.$dt->dent_from.', '.
		'dent_to: '.$dt->dent_to.', '.
		'cost_mass: '.$dt->cost_mass.', '.
		'message: "'.$dt->message.'"'.
		'};'.PHP_EOL;
	}
$script.=
'//   --==  [ Calculation function ]  ==--
function chinaaviachange(){
	var selectedid=jQuery(\'#chinacalc_avia_goodstype\').find(\':selected\').val();
	var goods=chinaaviagoodtypes[selectedid];
	window.console&&console.log(\'[China Calculator Avia]: calculating delivery for id=\'+selectedid+\' (\'+goods.name+\')...\');
	//Maybe, need to show items count input?
	var count=0;
	var countok=false;
	//Цена договорная
	if(goods.cost_weight<=0){
		jQuery(\'#formfield_count\').fadeOut(300);
		var htmltext=\'<p>\'+brilllang.MOD_CHINACALCAVIA_PLEASECALL+\'</p>\';
		jQuery(\'#chinacalc_avia_result\').html(htmltext);
		return true;
		}

	if(goods.cost_count>0){
		jQuery(\'#formfield_count\').fadeIn(300);
		var count=jQuery(\'#chinacalc_avia_count\').val();
		count=Math.floor(count);
		if((count==undefined)||(count==\'\')){
			count=0;
			}
		if(count>0){
			//The count is inputed right.
			countok=true;
			}
		}else{
		jQuery(\'#formfield_count\').fadeOut(300);
		//We do not need the count, the count is OK.
		countok=true;
		}

	//Get mass
	var mass=jQuery(\'#chinacalc_avia_mass\').val();
	mass=mass.replace(/,/g, \'.\');
	if((mass==undefined)||(mass==\'\')){
		mass=0;
		}
	//Get volume
	var volume=jQuery(\'#chinacalc_avia_volume\').val();
	volume=volume.replace(/,/g, \'.\');
	if((volume==undefined)||(volume==\'\')){
		volume=0;
		}
	window.console&&console.log(\'[China Calculator Avia]: mass=\'+mass+\', volume=\'+volume+\', count=\'+count+\'...\');	

	//Calculating...
	var html_tarif=goods.cost_weight+brilllang.MOD_CHINACALCAVIA_AVIA_USDPERKG;
	if(goods.cost_count>0){
		html_tarif+=\' + \'+goods.cost_count+brilllang.MOD_CHINACALCAVIA_AVIA_USDPERITEM;
		}
	var htmltext=\'<p><b>\'+brilllang.MOD_CHINACALCAVIA_AVIA_TARIF+\'</b>\'+html_tarif+\'</p>\';


	if(!countok){
		htmltext+=\'<p>\'+brilllang.MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_COUNT+\'</p>\';
		}
	if(mass<=0){
		htmltext+=\'<p>\'+brilllang.MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_MASS+\'</p>\';
		}
	if(volume<=0){
		htmltext+=\'<p>\'+brilllang.MOD_CHINACALCAVIA_AVIA_PLEASE_INPUT_RIGHT_VOLUME+\'</p>\';
		}

	if((mass>0)&&(volume>0)&&(countok)){
		var cost_main=(goods.cost_weight * mass) + (goods.cost_count * count);
		var cost_final;
		var html_cost=cost_main+brilllang.MOD_CHINACALCAVIA_AVIA_USD;
		//htmltext+=\'<p><b>\'+brilllang.MOD_CHINACALCAVIA_AVIA_COST+\'</b>\'+html_cost+\'</p>\';
		//Counting denticity
		var dent=mass / volume;
		var denti=-1;
		jQuery(window.chinaaviadenttypes).each(function(i,dt){
			if((dt!=undefined)&&(dent>=dt.dent_from)&&(dent<dt.dent_to)){
				denti=i;
				}
			});
		window.console&&console.log(\'dentisity = \'+dent+\' (type=\'+denti+\')\');
		var dentrount=Math.round(dent*100)/100;
		htmltext+=\'<p><b>\'+brilllang.MOD_CHINACALCAVIA_AVIA_DENT+\'</b>\'+dentrount+\'&nbsp;\'+brilllang.MOD_CHINACALCAVIA_AVIA_KGM3+\'</p>\';

		//Known denticity
		if(denti>=0){
			htmltext+=\'<p>\'+window.chinaaviadenttypes[denti].message+\'</p>\';
			cost_final=cost_main+(window.chinaaviadenttypes[denti].cost_mass * mass);
			var html_cost2=cost_final+brilllang.MOD_CHINACALCAVIA_AVIA_USD;
			htmltext+=\'<p><b>\'+brilllang.MOD_CHINACALCAVIA_AVIA_FINAL+\'</b>\'+html_cost2+\'</p>\';
			}
		//Unknown denticity
		else{
			htmltext+=\'<p><b>\'+brilllang.MOD_CHINACALCAVIA_AVIA_COST+\'</b>\'+html_cost+\'</p>\';
			htmltext+=\'<p>\'+brilllang.MOD_CHINACALCAVIA_AVIA_DENT_UNKNOWN+\'</p>\';
			}
		}
	jQuery(\'#chinacalc_avia_result\').html(htmltext);
	}
//   --==  [ Add events listeners ]  ==--
jQuery(document).ready(function(){
	window.console&&console.log(\'[China Calculator Avia]: the document is ready!\');
	jQuery(\'#chinacalc_avia_goodstype\').change(chinaaviachange);
	jQuery(\'#formfield_count\').change(chinaaviachange);
	jQuery(\'#chinacalc_avia_mass\').change(chinaaviachange);
	jQuery(\'#chinacalc_avia_volume\').change(chinaaviachange);
	chinaaviachange();
	jQuery(\'#chinacalc_avia_form\').submit(function(){
		window.console&&console.log(\'[China Calculator Avia]: submiting form. Checking form...\');
		//Get form data and check it...
		var formok=true;
		var ff_phone=jQuery(\'#chinacalc_avia_phone\').val();
		if(ff_phone==\'\'){
			jQuery(\'#chinacalc_avia_phone\').focus();
			jQuery(\'#chinacalc_avia_phone\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_avia_phone\').removeClass(\'error\');
			}
		var ff_email=jQuery(\'#chinacalc_avia_email\').val();
		if(ff_email==\'\'){
			jQuery(\'#chinacalc_avia_email\').focus();
			jQuery(\'#chinacalc_avia_email\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_avia_email\').removeClass(\'error\');
			}
		var ff_name=jQuery(\'#chinacalc_avia_name\').val();
		if(ff_name==\'\'){
			jQuery(\'#chinacalc_avia_name\').focus();
			jQuery(\'#chinacalc_avia_name\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_avia_name\').removeClass(\'error\');
			}
		var ff_volume=jQuery(\'#chinacalc_avia_volume\').val();
		if(ff_volume==\'\'){
			jQuery(\'#chinacalc_avia_volume\').focus();
			jQuery(\'#chinacalc_avia_volume\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_avia_volume\').removeClass(\'error\');
			}
		var ff_mass=jQuery(\'#chinacalc_avia_mass\').val();
		if(ff_mass==\'\'){
			jQuery(\'#chinacalc_avia_mass\').focus();
			jQuery(\'#chinacalc_avia_mass\').addClass(\'error\');
			formok=false;
			}else{
			jQuery(\'#chinacalc_avia_mass\').removeClass(\'error\');
			}
		var ff_count=jQuery(\'#chinacalc_avia_count\').val();
		//if(ff_count==\'\'){
		//	jQuery(\'#chinacalc_avia_count\').focus();
		//	jQuery(\'#chinacalc_avia_count\').addClass(\'error\');
		//	formok=false;
		//	}else{
		//	jQuery(\'#chinacalc_avia_count\').removeClass(\'error\');
		//	}
		var ff_goodstype=jQuery(\'#chinacalc_avia_goodstype\').val();


		if(!formok){
			return false;
			}
		//Do AJAX request...
		window.console&&console.log(\'[China Calculator Avia]: Performing AJAX request...\');
		var data={
			goodstype: ff_goodstype,
			mass: ff_mass,
			volume: ff_volume,
			count: ff_count,
			email: ff_email,
			name: ff_name,
			phone: ff_phone
			};
		jQuery.ajax({
			url: window.chinaaviaajax,
			data: data,
			format: "json",
			success:function(data){
				window.console&&console.log(\'[China Calculator Avia]: AJAX request success!\');
				window.console&&console.log(data);

				if(data.errorcode==0){
					//ga(\'send\', \'event\', \'estimator\', \'click\', \'calculated\', Math.round(price));
					ga(\'send\', \'event\', \'estimator\', \'click\', \'calculated\', 0);

					jQuery(\'#chinacalc_avia_form\').hide();
					jQuery(\'#chinacalc_avia_ajax\').addClass(\'success\');
					jQuery(\'#chinacalc_avia_ajax\').html(data.errormessage);
					}
				else{
					jQuery(\'#chinacalc_avia_ajax\').addClass(\'error\');
					jQuery(\'#chinacalc_avia_ajax\').html(data.errormessage);
					}
				},
			error:function(){
				window.console&&console.log(\'[China Calculator Avia]: AJAX request failed!\');
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
	<div id="chinacalc_avia_ajax"></div>
	<form class="chinacalculator-avia-form" id="chinacalc_avia_form" method="POST" action="<?php echo $url_action; ?>">
		<div class="w50 floatleft">
			<div class="formfield" id="formfield_goodstype">
				<label for="chinacalc_avia_goodstype"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_GOODSTYPE'); ?></label>
				<select id="chinacalc_avia_goodstype" name="goodstype" class="textinput">
					<?php foreach($goodstype as $gt): ?>
					<option value="<?php echo $gt->id; ?>"><?php echo $gt->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="formfield" id="formfield_count" style="display: none;">
				<label for="chinacalc_avia_count"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_COUNT'); ?></label>
				<input type="text" id="chinacalc_avia_count" name="count" class="textinput">
			</div>

			<div class="formfield" id="formfield_mass">
				<label for="chinacalc_avia_mass"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_MASS'); ?></label>
				<input type="text" id="chinacalc_avia_mass" name="mass" class="textinput">
			</div>

			<div class="formfield" id="formfield_volume">
				<label for="chinacalc_avia_volume"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_VOLUME'); ?></label>
				<input type="text" id="chinacalc_avia_volume" name="volume" class="textinput">
			</div>
		</div>
		<div class="w50 floatleft">
			<div class="formfield" id="formfield_name">
				<label for="chinacalc_avia_name"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_NAME'); ?></label>
				<input type="text" id="chinacalc_avia_name" name="name" class="textinput">
			</div>

			<div class="formfield" id="formfield_email">
				<label for="chinacalc_avia_email"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_EMAIL'); ?></label>
				<input type="email" id="chinacalc_avia_email" name="email" class="textinput">
			</div>

			<div class="formfield" id="formfield_phone">
				<label for="chinacalc_avia_phone"><?php echo JText::_('MOD_CHINACALCAVIA_AVIA_PHONE'); ?></label>
				<input type="text" id="chinacalc_avia_phone" name="phone" class="textinput">
			</div>

		</div>
		<div class="clear"></div>

		<div id="chinacalc_avia_result"></div>
		<div id="chinacalc_avia_submit">
			<input type="hidden" name="calctype" value="1"/>
			<input type="submit" value="<?php echo JText::_('MOD_CHINACALCAVIA_AVIA_SUBMIT'); ?>">
		</div>
	</form>
</div>
