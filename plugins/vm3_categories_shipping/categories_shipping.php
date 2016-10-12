<?php
/**
 * Virtuemart plugin for categories-based shipments
 *
 * @version 1.0.0
 * @package VirtueMart
 * @subpackage Plugins - shipment
 * @copyright Copyright (C) 2004-2014 VirtueMart Team - All rights reserved.
 * @copyright Copyright (C) 2014 Andrii Biriev, a@konservs.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright
 * notices and details.
 *
 * @author Andrii Biriev
 *
**/
defined('_JEXEC') or die('Restricted access');
define('VM2_SHIPPING_CATEGORIES_DEBUG',1);

if(!class_exists ('vmPSPlugin')) {
	require(JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
	}
/**
 * Shipping costs according to categories.
 */
class plgVmShipmentCategories_Shipping extends  vmPSPlugin{
	/**
	 * Plugin constructor
	 * @param object $subject
	 * @param array  $config
	 */
	function __construct (& $subject, $config) {
		parent::__construct ($subject, $config);
		$this->_loggable = TRUE;
		$this->_tablepkey = 'id';
		$this->_tableId = 'id';
		$this->tableFields = array_keys ($this->getTableSQLFields ());
		$varsToPush = $this->getVarsToPush ();
		$this->setConfigParameterable ($this->_configTableFieldName, $varsToPush);
		}
	/**
	 * Create the table for this plugin if it does not yet exist.
	 *
	 * @author Andrii Biriev
	 */
	public function getVmPluginCreateTableSQL() {
		return $this->createTableSQL('Shipment Rules Table');
		}

	/**
	 * Draw the warning...
	 * Keep track of warning messages, so we don't print them twice.
	 * @author Andrii Biriev
	 */	
	public function printWarning($message){
		global $printed_warnings;
		if(!isset($printed_warnings))
			$printed_warnings = array();
		if(!in_array($message, $printed_warnings)) {
			JFactory::getApplication()->enqueueMessage($message, 'error');
			$printed_warnings[] = $message;
			}
		}
	/**
	 *
	 * @author Andrii Biriev
	 * @return array
	 */
	function getTableSQLFields () {
		$SQLfields = array(
			'id'                           => 'int(1) UNSIGNED NOT NULL AUTO_INCREMENT',
			'virtuemart_order_id'          => 'int(11) UNSIGNED',
			'order_number'                 => 'char(32)',
			'virtuemart_shipmentmethod_id' => 'mediumint(1) UNSIGNED',
			'shipment_name'                => 'varchar(5000)',
			'rule_name'                    => 'varchar(500)',
			'order_weight'                 => 'decimal(10,4)',
			'order_articles'               => 'int(1)',
			'order_products'               => 'int(1)',
			'shipment_weight_unit'         => 'char(3) DEFAULT \'KG\'',
			'shipment_cost'                => 'decimal(10,2)',
			'tax_id'                       => 'smallint(1)'
			);
		return $SQLfields;
		}

	/**
	 * This method is fired when showing the order details in the frontend.
	 * It displays the shipment-specific data.
	 *
	 * @param integer $virtuemart_order_id The order ID
	 * @param integer $virtuemart_shipmentmethod_id The selected shipment method id
	 * @param string  $shipment_name Shipment Name
	 * @return mixed Null for shipments that aren't active, text (HTML) otherwise
	 *
	 * @author Andrii Biriev
	 */
	public function plgVmOnShowOrderFEShipment ($virtuemart_order_id, $virtuemart_shipmentmethod_id, &$shipment_name) {
		$this->onShowOrderFE ($virtuemart_order_id, $virtuemart_shipmentmethod_id, $shipment_name);
		}
	/**
	 * This event is fired after the order has been stored; it gets the shipment method-
	 * specific data.
	 *
	 * @param int    $order_id The order_id being processed
	 * @param object $cart  the cart
	 * @param array  $order The actual order saved in the DB
	 * @return mixed Null when this method was not selected, otherwise true
	 *
	 * @author Andrii Biriev
	 */
	function plgVmConfirmedOrder (VirtueMartCart $cart, $order) {
		//die('plgVmConfirmedOrder');

		if (!($method = $this->getVmPluginMethod ($order['details']['BT']->virtuemart_shipmentmethod_id))) {
			return NULL; // Another method was selected, do nothing
			}
		if (!$this->selectedThisElement ($method->shipment_element)) {
			return FALSE;
			}
		$values['virtuemart_order_id'] = $order['details']['BT']->virtuemart_order_id;
		$values['order_number'] = $order['details']['BT']->order_number;
		$values['virtuemart_shipmentmethod_id'] = $order['details']['BT']->virtuemart_shipmentmethod_id;
		$values['shipment_name'] = $this->renderPluginName ($method);
		$values['rule_name'] = $method->rule_name;
		$values['order_weight'] = $this->getOrderWeight ($cart, $method->weight_unit);
		$values['order_articles'] = $this->getOrderArticles ($cart);
		$values['order_products'] = $this->getOrderProducts ($cart);
		$values['shipment_weight_unit'] = $method->weight_unit;
		$values['shipment_cost'] = $method->cost;
		$values['tax_id'] = $method->tax_id;
		$this->storePSPluginInternalData ($values);

		return TRUE;
		}

	/**
	 * This method is fired when showing the order details in the backend.
	 * It displays the shipment-specific data.
	 * NOTE, this plugin should NOT be used to display form fields, since it's called outside
	 * a form! Use plgVmOnUpdateOrderBE() instead!
	 *
	 * @param integer $virtuemart_order_id The order ID
	 * @param integer $virtuemart_shipmentmethod_id The order shipment method ID
	 * @param object  $_shipInfo Object with the properties 'shipment' and 'name'
	 * @return mixed Null for shipments that aren't active, text (HTML) otherwise
	 *
	 * @author Andrii Biriev
	 */
	public function plgVmOnShowOrderBEShipment ($virtuemart_order_id, $virtuemart_shipmentmethod_id) {
		//die('plgVmOnShowOrderBEShipment');

		if (!($this->selectedThisByMethodId ($virtuemart_shipmentmethod_id))) {
			return NULL;
		}
		$html = $this->getOrderShipmentHtml ($virtuemart_order_id);
		return $html;
	}

	/**
	 * @param $virtuemart_order_id
	 * @return string
	 */
	function getOrderShipmentHtml ($virtuemart_order_id) {

		//die('getOrderShipmentHtml');

		$db = JFactory::getDBO ();
		$q = 'SELECT * FROM `' . $this->_tablename . '` '
			. 'WHERE `virtuemart_order_id` = ' . $virtuemart_order_id;
		$db->setQuery ($q);
		if (!($shipinfo = $db->loadObject ())) {
			vmWarn (500, $q . " " . $db->getErrorMsg ());
			return '';
		}

		if (!class_exists ('CurrencyDisplay')) {
			require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'currencydisplay.php');
		}

		$currency = CurrencyDisplay::getInstance ();
		$tax = ShopFunctions::getTaxByID ($shipinfo->tax_id);
		$taxDisplay = is_array ($tax) ? $tax['calc_value'] . ' ' . $tax['calc_value_mathop'] : $shipinfo->tax_id;
		$taxDisplay = ($taxDisplay == -1) ? JText::_ ('COM_VIRTUEMART_PRODUCT_TAX_NONE') : $taxDisplay;

		$html = '<table class="adminlist">' . "\n";
		$html .= $this->getHtmlHeaderBE ();
		$html .= $this->getHtmlRowBE ('RULES_SHIPPING_NAME', $shipinfo->shipment_name);
		$html .= $this->getHtmlRowBE ('RULES_WEIGHT', $shipinfo->order_weight . ' ' . ShopFunctions::renderWeightUnit ($shipinfo->shipment_weight_unit));
		$html .= $this->getHtmlRowBE ('RULES_ARTICLES', $shipinfo->order_articles . '/' . $shipinfo->order_products);
		$html .= $this->getHtmlRowBE ('RULES_COST', $currency->priceDisplay ($shipinfo->shipment_cost));
		$html .= $this->getHtmlRowBE ('RULES_TAX', $taxDisplay);
		$html .= '</table>' . "\n";
		return $html;
		}
	/**
	 * Include the rule name in the shipment name
	 *
	 * @param $plugin
	 *
	 * @return string
	 */
	protected function renderPluginName ($plugin) {
		$return = '';
		$plugin_name = $this->_psType . '_name';
		$plugin_desc = $this->_psType . '_desc';
		$description = '';
		//$params = new JParameter($plugin->$plugin_params);
		//$logo = $params->get($this->_psType . '_logos');
		$logosFieldName = $this->_psType . '_logos';
		$logos = $plugin->$logosFieldName;
		if(!empty($logos)) {
			$return = $this->displayLogos ($logos) . ' ';
			}
		if(!empty($plugin->$plugin_desc)) {
			$description = '<span class="' . $this->_type . '_description">' . $plugin->$plugin_desc . '</span>';
			}
		$rulename='';
		if(!empty($plugin->rule_name)) {
			$rulename=" (".htmlspecialchars($plugin->rule_name).")";
			}
		$pluginName = $return . '<span class="' . $this->_type . '_name">' . $plugin->$plugin_name . $rulename.'</span>' . $description;
		return $pluginName;
		}
	/**
	 * @param VirtueMartCart $cart
	 * @param int             $method
	 * @param array           $cart_prices
	 *
	 * @return bool
	 */
	protected function checkConditions($cart, $method, $cart_prices) {
		if(!isset($method->parsedcategories)){
			$this->parseMethodCategories($method);
			}
		return true;
		}
	/**
	 * @param VirtueMartCart $cart
	 * @param                $method
	 * @param                $cart_prices
	 * @return int
	 */
	function getCosts(VirtueMartCart $cart, $method, $cart_prices) {
		if(!isset($method->parsedcategories)){
			$this->parseMethodCategories($method);
			}
		$result=(double)0;
		foreach($cart->products as $product){
			$quantity=(int)$product->quantity;
			$price=(double)$product->product_price;
			$cat=(int)$product->virtuemart_category_id;
			if(($cat>0)&&(isset($method->parsedcategories[$cat]))){
				$result+=$method->parsedcategories[$cat]->price * $quantity;
				//echo('price='.$price);
				}else{
				}
			}
		return $result;
		}
	/**
	 * update the plugin cart_prices (
	 *
	 * @author Valérie Isaksen (original), Reinhold Kainhofer (tax calculations from shippingWithTax)
	 *
	 * @param $cart_prices: $cart_prices['salesPricePayment'] and $cart_prices['paymentTax'] updated. Displayed in the cart.
	 * @param $value :   fee
	 * @param $tax_id :  tax id
	 */
	function setCartPrices (VirtueMartCart $cart, &$cart_prices, $method) {
		if (!class_exists ('calculationHelper')) {
			require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'calculationh.php');
			}
		$_psType = ucfirst ($this->_psType);
		$calculator = calculationHelper::getInstance ();

		$cart_prices[$this->_psType . 'Value'] = $calculator->roundInternal ($this->getCosts ($cart, $method, $cart_prices), 'salesPrice');

		if($this->_psType=='payment'){
			$cartTotalAmountOrig=$this->getCartAmount($cart_prices);
			$cartTotalAmount=($cartTotalAmountOrig + $method->cost_per_transaction) / (1 -($method->cost_percent_total * 0.01));
			$cart_prices[$this->_psType . 'Value'] = $cartTotalAmount - $cartTotalAmountOrig;
			}

		$taxrules = array();
		if(isset($method->tax_id) and (int)$method->tax_id === -1){

			}
		elseif (!empty($method->tax_id)) {
			$cart_prices[$this->_psType . '_calc_id'] = $method->tax_id;

			$db = JFactory::getDBO ();
			$q = 'SELECT * FROM #__virtuemart_calcs WHERE `virtuemart_calc_id`="' . $method->tax_id . '" ';
			$db->setQuery ($q);
			$taxrules = $db->loadAssocList ();

			if(!empty($taxrules) ){
				foreach($taxrules as &$rule){
					if(!isset($rule['subTotal'])) $rule['subTotal'] = 0;
					if(!isset($rule['taxAmount'])) $rule['taxAmount'] = 0;
					$rule['subTotalOld'] = $rule['subTotal'];
					$rule['taxAmountOld'] = $rule['taxAmount'];
					$rule['taxAmount'] = 0;
					$rule['subTotal'] = $cart_prices[$this->_psType . 'Value'];
					}
				}
			}
		else {
			$taxrules = array_merge($calculator->_cartData['VatTax'],$calculator->_cartData['taxRulesBill']);
			if(!empty($taxrules) ){
				$denominator = 0.0;
				foreach($taxrules as &$rule){
					//$rule['numerator'] = $rule['calc_value']/100.0 * $rule['subTotal'];
					if(!isset($rule['subTotal'])) $rule['subTotal'] = 0;
					if(!isset($rule['taxAmount'])) $rule['taxAmount'] = 0;
					$denominator += ($rule['subTotal']-$rule['taxAmount']);
					$rule['subTotalOld'] = $rule['subTotal'];
					$rule['subTotal'] = 0;
					$rule['taxAmountOld'] = $rule['taxAmount'];
					$rule['taxAmount'] = 0;
					//$rule['subTotal'] = $cart_prices[$this->_psType . 'Value'];
					}
				if(empty($denominator)){
					$denominator = 1;
					}

				foreach($taxrules as &$rule){
					$frac = ($rule['subTotalOld']-$rule['taxAmountOld'])/$denominator;
					$rule['subTotal'] = $cart_prices[$this->_psType . 'Value'] * $frac;
					vmdebug('Part $denominator '.$denominator.' $frac '.$frac,$rule['subTotal']);
					}
				}
			}
		if(empty($method->cost_per_transaction)) $method->cost_per_transaction = 0.0;
		if(empty($method->cost_percent_total)) $method->cost_percent_total = 0.0;
		if(count($taxrules)>0){

			// BEGIN_RK_CHANGES
			if ($method->includes_tax) {

				$cart_prices['salesPrice' . $_psType] = $calculator->roundInternal ($cart_prices[$this->_psType . 'Value'], 'salesPrice');
				// Calculate the tax from the final sales price:
				$calculator->setRevert (true);
				$cart_prices[$this->_psType . 'Value'] = $calculator->roundInternal ($calculator->executeCalculation($taxrules, $cart_prices[$this->_psType . 'Value'], true));
				$cart_prices[$this->_psType . 'Tax'] = $cart_prices['salesPrice' . $_psType] - $cart_prices[$this->_psType . 'Value'];
				$calculator->setRevert (false);
			} else {
			// END_RK_CHANGES
			$cart_prices['salesPrice' . $_psType] = $calculator->roundInternal ($calculator->executeCalculation ($taxrules, $cart_prices[$this->_psType . 'Value'],true,false), 'salesPrice');
			//vmdebug('I am in '.get_class($this).' and have this rules now',$taxrules,$cart_prices[$this->_psType . 'Value'],$cart_prices['salesPrice' . $_psType]);
			$cart_prices[$this->_psType . 'Tax'] = $calculator->roundInternal (($cart_prices['salesPrice' . $_psType] -  $cart_prices[$this->_psType . 'Value']), 'salesPrice');
			// BEGIN_RK_CHANGES
			}
			// END_RK_CHANGES
			reset($taxrules);
			$taxrule =  current($taxrules);
			$cart_prices[$this->_psType . '_calc_id'] = $taxrule['virtuemart_calc_id'];

			foreach($taxrules as &$rule){
				if(isset($rule['subTotalOld'])) $rule['subTotal'] += $rule['subTotalOld'];
				if(isset($rule['taxAmountOld'])) $rule['taxAmount'] += $rule['taxAmountOld'];
			}

		} else {
			$cart_prices['salesPrice' . $_psType] = $cart_prices[$this->_psType . 'Value'];
			$cart_prices[$this->_psType . 'Tax'] = 0;
			$cart_prices[$this->_psType . '_calc_id'] = 0;
		}


		return $cart_prices['salesPrice' . $_psType];

	}

	/*protected function createMethodRule ($r, $countries, $tax) {
		return new ShippingRule($r, $countries, $tax);
	}

	private function parseMethodRule ($rulestring, $countries, $tax, &$method) {
		$rules1 = preg_split("/(\r\n|\n|\r)/", $rulestring);
		foreach ($rules1 as $r) {
			// Ignore empty lines
			if (empty($r)) continue;
			$method->rules[] = $this->createMethodRule ($r, $countries, $tax);
		}
	}*/
	
	protected function parseMethodCategories (&$method) {
		if(!isset($method->parsedcategories)){
			$method->parsedcategories = array();
			}
		$str=$method->categories;
		$arr=explode("\n", $str);
		foreach($arr as $itm){
			$itm=trim($itm);
			$x=explode("/", $itm);
			if((count($x)==2)&&(is_numeric($x[0]))&&(is_numeric($x[1]))){
				$cat=(int)$x[0];
				$price=(double)$x[1];
				$method->parsedcategories[$cat]=(object)array(
					'category'=>$cat,
					'price'=>$price);
				}
			}
		}

	protected function getOrderArticles (VirtueMartCart $cart) {
		/* Cache the value in a static variable and calculate it only once! */
		static $articles = 0;
		if(empty($articles) and count($cart->products)>0){
			foreach ($cart->products as $product) {
				$articles += $product->quantity;
			}
		}
		return $articles;
	}

	protected function getOrderProducts (VirtueMartCart $cart) {
		/* Cache the value in a static variable and calculate it only once! */
		static $products = 0;
		if(empty($products) and count($cart->products)>0){
			$products = count($cart->products);
		}
		return $products;
	}

	protected function getOrderDimensions (VirtueMartCart $cart, $length_dimension) {
		/* Cache the value in a static variable and calculate it only once! */
		static $calculated = 0;
		static $dimensions=array(
			'volume' => 0,
			'maxvolume' => 0, 'minvolume' => 9999999999,
			'maxlength' => 0, 'minlength' => 9999999999, 'totallength' => 0,
			'maxwidth'  => 0, 'minwidth' => 9999999999,  'totalwidth'  => 0,
			'maxheight' => 0, 'minheight' => 9999999999, 'totalheight' => 0,
			'maxpackaging' => 0, 'minpackaging' => 9999999999, 'totalpackaging' => 0,
		);
		if ($calculated==0) {
			$calculated=1;
			foreach ($cart->products as $product) {
	
				$l = ShopFunctions::convertDimensionUnit ($product->product_length, $product->product_lwh_uom, $length_dimension);
				$w = ShopFunctions::convertDimensionUnit ($product->product_width, $product->product_lwh_uom, $length_dimension);
				$h = ShopFunctions::convertDimensionUnit ($product->product_height, $product->product_lwh_uom, $length_dimension);

				$volume = $l * $w * $h;
				$dimensions['volume'] += $volume * $product->quantity;
				$dimensions['maxvolume'] = max ($dimensions['maxvolume'], $volume);
				$dimensions['minvolume'] = min ($dimensions['minvolume'], $volume);
				
				$dimensions['totallength'] += $l * $product->quantity;
				$dimensions['maxlength'] = max ($dimensions['maxlength'], $l);
				$dimensions['minlength'] = min ($dimensions['minlength'], $l);
				$dimensions['totalwidth'] += $w * $product->quantity;
				$dimensions['maxwidth'] = max ($dimensions['maxwidth'], $w);
				$dimensions['minwidth'] = min ($dimensions['minwidth'], $w);
				$dimensions['totalheight'] += $h * $product->quantity;
				$dimensions['maxheight'] = max ($dimensions['maxheight'], $h);
				$dimensions['minheight'] = min ($dimensions['minheight'], $h);
				$dimensions['totalpackaging'] += $product->packaging * $product->quantity;
				$dimensions['maxpackaging'] = max ($dimensions['maxpackaging'], $product->packaging);
				$dimensions['minpackaging'] = min ($dimensions['minpackaging'], $product->packaging);
			}
		}

		return $dimensions;
	}
	
	function getOrderWeights (VirtueMartCart $cart, $weight_unit) {
		static $calculated = 0;
		static $dimensions=array(
			'weight' => 0,
			'maxweight' => 0, 'minweight' => 9999999999,
		);
		if ($calculated==0 && count($cart->products)>0) {
			$calculated = 1;
			foreach ($cart->products as $product) {
				$w = ShopFunctions::convertWeigthUnit ($product->product_weight, $product->product_weight_uom, $weight_unit);
				$dimensions['maxweight'] = max ($dimensions['maxweight'], $w);
				$dimensions['minweight'] = min ($dimensions['minweight'], $w);
				$dimensions['weight'] += $w * $product->quantity;
			}
		}
		return $dimensions;
	}
	
	function getOrderListProperties (VirtueMartCart $cart) {
		$categories = array();
		$vendors = array();
		$skus = array();
		$manufacturers = array();
		foreach ($cart->products as $product) {
			$skus[] = $product->product_sku;
			$categories = array_merge ($categories, $product->categories);
			$vendors[] = $product->virtuemart_vendor_id;
			if ($product->virtuemart_manufacturer_id) {
				$manufacturers[] = $product->virtuemart_manufacturer_id;
			}
		}
		$categories = array_unique($categories);
		$vendors = array_unique($vendors);
		return array ('skus'=>$skus, 
			      'categories'=>$categories,
			      'vendors'=>$vendors,
			      'manufacturers'=>$manufacturers,
		);
	}
	
	function getOrderCountryState (VirtueMartCart $cart, $address) {
		$data = array (
			'countryid' => 0, 'country' => '', 'country2' => '', 'country3' => '',
			'stateid'   => 0, 'state'   => '', 'state2'   => '', 'state3'   => '',
		);
		
		$countriesModel = VmModel::getModel('country');
		if (isset($address['virtuemart_country_id'])) {
			$data['countryid'] = $address['virtuemart_country_id'];
			$countriesModel->setId($address['virtuemart_country_id']);
			$country = $countriesModel->getData();
			if (!empty($country)) {
				$data['country'] = $country->country_name;
				$data['country2'] = $country->country_2_code;
				$data['country3'] = $country->country_3_code;
			}
		}
		
		$statesModel = VmModel::getModel('state');
		if (isset($address['virtuemart_state_id'])) {
			$data['stateid'] = $address['virtuemart_state_id'];
			$statesModel->setId($address['virtuemart_state_id']);
			$state = $statesModel->getData();
			if (!empty($state)) {
				$data['state'] = $state->state_name;
				$data['state2'] = $state->state_2_code;
				$data['state3'] = $state->state_3_code;
			}
		}
		
		return $data;

	}

	/**
	 * Create the table for this plugin if it does not yet exist.
	 * This functions checks if the called plugin is active one.
	 * When yes it is calling the standard method to create the tables
	 *
	 * @author Valérie Isaksen
	 *
	 */
	function plgVmOnStoreInstallShipmentPluginTable ($jplugin_id) {
		//die('plgVmOnStoreInstallShipmentPluginTable');
		return $this->onStoreInstallPluginTable ($jplugin_id);
	}

	/**
	 * @param VirtueMartCart $cart
	 * @return null
	 */
	public function plgVmOnSelectCheckShipment (VirtueMartCart &$cart) {
		return $this->OnSelectCheck ($cart);
	}

	/**
	 * plgVmDisplayListFE
	 * This event is fired to display the pluginmethods in the cart (edit shipment/payment) for example
	 *
	 * @param object  $cart Cart object
	 * @param integer $selected ID of the method selected
	 * @return boolean True on success, false on failures, null when this plugin was not selected.
	 * On errors, JError::raiseWarning (or JError::raiseError) must be used to set a message.
	 *
	 * @author Valerie Isaksen
	 * @author Max Milbers
	 */
	public function plgVmDisplayListFEShipment (VirtueMartCart $cart, $selected = 0, &$htmlIn) {
		return $this->displayListFE ($cart, $selected, $htmlIn);
		}

	/**
	 * @param VirtueMartCart $cart
	 * @param array          $cart_prices
	 * @param                $cart_prices_name
	 * @return bool|null
	 */
	public function plgVmOnSelectedCalculatePriceShipment (VirtueMartCart $cart, array &$cart_prices, &$cart_prices_name) {
		return $this->onSelectedCalculatePrice ($cart, $cart_prices, $cart_prices_name);
		}

	/**
	 * plgVmOnCheckAutomaticSelected
	 * Checks how many plugins are available. If only one, the user will not have the choice. Enter edit_xxx page
	 * The plugin must check first if it is the correct type
	 *
	 * @author Valerie Isaksen
	 * @param VirtueMartCart cart: the cart object
	 * @return null if no plugin was found, 0 if more then one plugin was found,  virtuemart_xxx_id if only one plugin is found
	 *
	 */
	function plgVmOnCheckAutomaticSelectedShipment (VirtueMartCart $cart, array $cart_prices = array(), &$shipCounter) {
		if ($shipCounter > 1) {
			return 0;
		}
		return $this->onCheckAutomaticSelected ($cart, $cart_prices, $shipCounter);
	}

	/**
	 * This method is fired when showing when priting an Order
	 * It displays the the payment method-specific data.
	 *
	 * @param integer $_virtuemart_order_id The order ID
	 * @param integer $method_id  method used for this order
	 * @return mixed Null when for payment methods that were not selected, text (HTML) otherwise
	 * @author Valerie Isaksen
	 */
	function plgVmonShowOrderPrint ($order_number, $method_id) {
//var_dump($name);
//var_dump($id);
//var_dump($data);
//die('plgVmonShowOrderPrint');

		return $this->onShowOrderPrint ($order_number, $method_id);
		}

	function plgVmDeclarePluginParamsShipment ($name, $id, &$data) {
//var_dump($name);
//var_dump($id);
//var_dump($data);
//die('plgVmDeclarePluginParamsShipment');
		return $this->declarePluginParams ('shipment', $name, $id, $data);
		}

	/* This function is needed in VM 2.0.14 etc. because otherwise the params are not saved */
	function plgVmSetOnTablePluginParamsShipment ($name, $id, &$table) {
/*if($name!='categories_shipping'){
	var_dump($name); echo('<br>');
	var_dump($id); echo('<br>');
	var_dump($table); echo('<hr>');
	die('plgVmSetOnTablePluginParamsShipment');
	}*/
		return $this->setOnTablePluginParams ($name, $id, $table);
		}

	function plgVmDeclarePluginParamsShipmentVM3 (&$data) {
		return $this->declarePluginParams ('shipment', $data);
	}


	function plgVmSetOnTablePluginShipment(&$data,&$table){
//var_dump($table); echo('<hr>');
//var_dump($data); echo('<hr>');
//die('plgVmSetOnTablePluginShipment');
		$name = $data['shipment_element'];
		$id = $data['shipment_jplugin_id'];

		if (!empty($this->_psType) and !$this->selectedThis ($this->_psType, $name, $id)) {
			return FALSE;
			}
		if (isset($data['rules1'])) {
			// Try to parse all rules (and spit out error) to inform the user:
			$method = new StdClass ();
			$this->parseMethodRule ($data['rules1'], isset($data['countries1'])?$data['countries1']:array(), $data['tax_id1'], $method);
			$this->parseMethodRule ($data['rules2'], isset($data['countries2'])?$data['countries2']:array(), $data['tax_id2'], $method);
			$this->parseMethodRule ($data['rules3'], isset($data['countries3'])?$data['countries3']:array(), $data['tax_id3'], $method);
			$this->parseMethodRule ($data['rules4'], isset($data['countries4'])?$data['countries4']:array(), $data['tax_id4'], $method);
			$this->parseMethodRule ($data['rules5'], isset($data['countries5'])?$data['countries5']:array(), $data['tax_id5'], $method);
			$this->parseMethodRule ($data['rules6'], isset($data['countries6'])?$data['countries6']:array(), $data['tax_id6'], $method);
			$this->parseMethodRule ($data['rules7'], isset($data['countries7'])?$data['countries7']:array(), $data['tax_id7'], $method);
			$this->parseMethodRule ($data['rules8'], isset($data['countries8'])?$data['countries8']:array(), $data['tax_id8'], $method);
			}
		$ret=$this->setOnTablePluginParams ($name, $id, $table);
		return $ret;
		}
	}


// No closing tag
