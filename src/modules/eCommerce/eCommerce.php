<?php

	class eCommerce extends Module
	{
		public function __ListCategories()
		{
			$query = 
				"SELECT `category_id`, `category_title`
				FROM `{$this->DB->Prefix}ecommerce_categories` 
				ORDER BY `category_id` ASC";
		
			foreach($this->DB->FetchRows($query, "slave") as $row)
			{
				$title = str_replace(' ', '-', strtolower($row['category_title']));
?>

<li>
	<a href="<?=$this->Site->URL?>/shop/category/<?=$row['category_id']?>/<?=$title?>/" title="<?=$row['category_title']?>"><?=$row['category_title']?></a>
</li>
		
<?php
			}
		}
		
		public function __Cart($action, $type = false)
		{
			$settings = $this->LoadSetting("ecommerce");

			if(!$settings['order_email'])
				$settings['order_email'] = $this->Site['email'];
			
			if($settings['enable_https'])
				$this->Site->EnableHTTPS();
			
			switch(strtolower($action))
			{
				case "success":
					$this->Page->Content = <<< EOH

Thank you very much for your purchase at {$this->Site->Title}. We hope you enjoy your purchase.
<br /><br />
Have a nice day!
<br /><br />
<a href="{$this->Site->URL}/"><< Return to {$this->Site->Title}</a>

EOH;
				break;
				
				case "cancel":
					if($settings['require_authorization'] && !$this->User->Validated)
						$this->Redirect($this->Site->URL . "/account/login/");
					
					$query = 
						"UPDATE `{$this->DB->Prefix}ecommerce_orders` 
						SET `order_status` 'Cancelled' 
						WHERE `order_id` = " . $this->Session['order_id'];
		
					$this->DB->Query($query);
					
					unset($_SESSION['order_id']);
					unset($_SESSION['token']);
					unset($_SESSION['amount']);
					unset($_SESSION['paymentType']);
					unset($_SESSION['currCodeType']);
					unset($_SESSION['payer_id']);
					unset($_SESSION['SERVER_NAME']);
					unset($_SESSION['type']);
					unset($_SESSION['shipping_first_name']);
					unset($_SESSION['shipping_last_name']);
					unset($_SESSION['shipping_street_1']);
					unset($_SESSION['shipping_street_2']);
					unset($_SESSION['shipping_city']);
					unset($_SESSION['shipping_state']);
					unset($_SESSION['shipping_postal_code']);
					unset($_SESSION['shipping_phone_number']);
					unset($_SESSION['shipping_country']);
					unset($_SESSION['billing_first_name']);
					unset($_SESSION['billing_last_name']);
					unset($_SESSION['billing_street_1']);
					unset($_SESSION['billing_street_2']);
					unset($_SESSION['billing_city']);
					unset($_SESSION['billing_state']);
					unset($_SESSION['billing_postal_code']);
					unset($_SESSION['billing_phone_number']);
					unset($_SESSION['billing_country']);
					unset($_SESSION['user_id']);
					
					$this->Page->Content = <<< EOH

Your checkout has been cancelled.
<br /><br />
<a href="{$this->Site->URL}/"><< Return to {$this->Site->Title}</a>

EOH;
				break;
				
				case "error":
					if($settings['require_authorization'] && !$this->User->Validated)
						$this->Redirect($this->Site->URL . "/account/login/");
					
					$resArray=$_SESSION['reshash']; 
											
					$this->Page->Content .= <<< EOH

<strong>We're sorry, there's been an error. Please try again.</strong>
<br />
EOH;

					if($settings['paypal_api_debug'])
					{
						$this->Page->Content .= <<< EOH

<table cellspacing="5" cellpadding="0" border="0">

EOH;

//it will print if any URL errors 
	if(isset($_SESSION['curl_error_no'])) { 
			$errorCode= $_SESSION['curl_error_no'];
			$errorMessage=$_SESSION['curl_error_msg'];	
			session_unset();
			
			$this->Page->Content .= <<< EOH

<tr>
		<td>Error Number:</td>
		<td>{$errorCode}</td>
	</tr>
	<tr>
		<td>Error Message:</td>
		<td>{$errorMessage}</td>
	</tr>
	
	</center>
	</table>
EOH;

} else {

/* If there is no URL Errors, Construct the HTML page with 
   Response Error parameters.   
   */

					$this->Page->Content .= <<< EOH

		<td>Ack:</td>
		<td>{$resArray['ACK']}</td>
	</tr>
	<tr>
		<td>Correlation ID:</td>
		<td>{$resArray['CORRELATIONID']}</td>
	</tr>
	<tr>
		<td>Version:</td>
		<td>{$resArray['VERSION']}</td>
	</tr>
EOH;

	$count=0;
	while (isset($resArray["L_SHORTMESSAGE".$count])) {		
		  $errorCode    = $resArray["L_ERRORCODE".$count];
		  $shortMessage = $resArray["L_SHORTMESSAGE".$count];
		  $longMessage  = $resArray["L_LONGMESSAGE".$count]; 
		  $count=$count+1; 


					$this->Page->Content .= <<< EOH

	<tr>
		<td>Error Number:</td>
		<td>{$errorCode}</td>
	</tr>
	<tr>
		<td>Short Message:</td>
		<td>{$shortMessage}</td>
	</tr>
	<tr>
		<td>Long Message:</td>
		<td>{$longMessage}</td>
	</tr>
EOH;

}
}

						$this->Page->Content .= <<< EOH

</center>
	</table>
	
EOH;
					}
				break;
				
				case "preview":
					if($settings['require_authorization'] && !$this->User->Validated)
						$this->Redirect($this->Site->URL . "/account/login/");
						
					$this->Session->Merge($_REQUEST);
					
					if($this->Session['same_information'] == "yes")
					{
						$this->Session['billing_first_name'] = $this->Session['shipping_first_name'];
						$this->Session['billing_last_name'] = $this->Session['shipping_last_name'];
						$this->Session['billing_street_1'] = $this->Session['shipping_street_1'];
						$this->Session['billing_street_2'] = $this->Session['shipping_street_2'];
						$this->Session['billing_city'] = $this->Session['shipping_city'];
						$this->Session['billing_state'] = $this->Session['shipping_state'];
						$this->Session['billing_postal_code'] = $this->Session['shipping_postal_code'];
						$this->Session['billing_phone_number'] = $this->Session['shipping_phone_number'];
						$this->Session['billing_fax_number'] = $this->Session['shipping_fax_number'];
						$this->Session['billing_country'] = $this->Session['shipping_country'];
					}
					

					
					$type = $this->Session['type'];
						
					$items = explode(',', $this->Session->Cart);

					$count = count($items);
					$s = $count > 1 ? 's' : '';
					
					$contents = array();
					
					foreach($items as $item)
					{
						$contents[$item] = isset($contents[$item]) ? ++$contents[$item] : 1;
					}
					
					$purchase_total = 0;
					$purchases = '';
					$shipping_total = $settings['base_shipping'];

					foreach($contents as $id => $quantity)
					{
						$query = 
							"SELECT `product_id`, `product_title`, `product_shipping_price`, `product_description`, `product_price`
							FROM `{$this->DB->Prefix}ecommerce_products` 
							WHERE `product_id` = {$id} 
							LIMIT 1";
						
						if($product = $this->DB->FetchRow($query, "slave"))
						{
							$purchases .= <<< EOH

{$quantity}x <a href="{$this->Site->URL}/shop/product/{$product['product_id']}/" target="_blank">{$product['product_title']}</a> (\${$product['product_price']})
<br />

EOH;
							if(isset($product['product_price']))
								$purchase_total += $product['product_price'] * $quantity;
								
							if(isset($product['product_shipping_price']))
								$shipping_total += $product['product_shipping_price'] * $quantity;
						}
					}

					$purchase_total = round($purchase_total, 2);
					
					$shipping_total = round($shipping_total, 2);
					
					if($settings['sales_tax_state'] == $this->Session['shipping_state'])
						$tax_total = round($purchase_total * ($settings['sales_tax_percentage'] / 100), 2);
					else 
						$tax_total = 0;
				
					$this->Session['amount'] = round($purchase_total + $shipping_total + $tax_total, 2);
						
					if($type == "pp_basic")
					{
						
							$query = array();
				
							$query[] = "`order_amount` = '" . $this->Session['amount'] . "'";
							if($this->Session['order_products']) $query[] = "`order_products` = '" . rawurldecode($this->Session['order_products']) . "'";
							//if($currCodeType) $query[] = "`order_currency` = '" . $currCodeType . "'";
							$query[] = "`shipping_first_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_first_name'])) . "'";
							$query[] = "`shipping_last_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_last_name'])) . "'";
							$query[] = "`shipping_street_1` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_street_1'])) . "'";
							$query[] = "`shipping_street_2` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_street_2'])) . "'";
							$query[] = "`shipping_city` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_city'])) . "'";
							$query[] = "`shipping_state` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_state'])) . "'";
							$query[] = "`shipping_postal_code` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_postal_code'])) . "'";
							$query[] = "`shipping_phone_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_phone_number'])) . "'";
							$query[] = "`shipping_fax_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_fax_number'])) . "'";
							$query[] = "`shipping_country` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_country'])) . "'";
	
								$query[] = "`billing_first_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_first_name'])) . "'";
								$query[] = "`billing_last_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_last_name'])) . "'";
								$query[] = "`billing_street_1` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_street_1'])) . "'";
								$query[] = "`billing_street_2` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_street_2'])) . "'";
								$query[] = "`billing_city` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_city'])) . "'";
								$query[] = "`billing_state` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_state'])) . "'";
								$query[] = "`billing_postal_code` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_postal_code'])) . "'";
								$query[] = "`billing_phone_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_phone_number'])) . "'";
								$query[] = "`billing_fax_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_fax_number'])) . "'";
								$query[] = "`billing_country` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_country'])) . "'";
							
							if($this->User['id']) $query[] = "`user_id` = '" . $this->User['id'] . "'";
							if($this->Session->Cart) $query[] = "`order_products` = '" . $this->Session->Cart . "'";
							$query[] = "`order_status` = 'Pending'";
							$query[] = "`order_type` = 'PayPal Basic'";
							$query[] = "`order_date` = FROM_UNIXTIME(" . time() . ")";
							$query[] = "`order_notes` = '" . mysql_real_escape_string(rawurldecode($this->Session['order_notes'])) . "'";
							
							$query = 
								"INSERT INTO `{$this->DB->Prefix}ecommerce_orders` 
								SET " . fix_query(implode(',', $query));
				
							$this->DB->Query($query);
							
							$order_id = mysql_insert_id();
							
							$this->Session['order_id'] = $order_id;
					}
					
					$this->Page->Content .= <<< EOH

<center>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/">View Cart</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/information/">Information</a></strong>
	</div>
	<div class="style1 active" style="float: left;">
		<strong>Preview</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Payment</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Review</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Complete</strong>
	</div>
	<div style="clear: both"></div>
</center>
<br /><br />
<strong>Purchases:</strong>  \${$purchase_total} USD
<br/>
<strong>Shipping:</strong> \${$shipping_total} USD
<br/>
<strong>Tax:</strong> \${$tax_total} USD
<br/>
<strong>Total:</strong> \${$this->Session['amount']} USD
<br /><br /><br />

EOH;
					
					$this->Page->Content .= <<< EOH

<center>
<form action="{$this->Site->URL}/shop/cart/payment/" method="post">
	<input type="submit" name="submit" value="Continue" class="submit" />
</form>
</center>

EOH;
	
				break;
				
				case "review":
					if($settings['require_authorization'] && !$this->User->Validated)
						$this->Redirect($this->Site->URL . "/account/login/");
						
					$items = explode(',', $this->Session->Cart);

					$count = count($items);
					$s = $count > 1 ? 's' : '';
					
					$contents = array();
					
					foreach($items as $item)
					{
						$contents[$item] = isset($contents[$item]) ? ++$contents[$item] : 1;
					}
					
					$purchases = '';
					
					foreach($contents as $id => $quantity)
					{
						$query = 
							"SELECT `product_pin`, `product_id`, `product_title`, `product_description`, `product_price`
							FROM `{$this->DB->Prefix}ecommerce_products` 
							WHERE `product_id` = {$id} LIMIT 1";
						
						if($product = $this->DB->FetchRow($query, "slave"))
						{
							$purchases .= <<< EOH

{$quantity}x <a href="{$this->Site->URL}/shop/product/{$product['product_id']}/" target="_blank">{$product['product_title']} (#{$product['product_pin']})</a> (\${$product['product_price']})
<br />

EOH;
						}
					}
					
					$this->Page->Content .= <<< EOH

<center>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/">View Cart</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/information/">Information</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/preview/">Preview</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/payment/">Payment</a></strong>
	</div>
	<div class="style1 active" style="float: left;">
		<strong>Review</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Complete</strong>
	</div>
	<div style="clear: both"></div>
</center>
<br /><br />
<center>
<strong>
	Before continuing, please confirm your order
	<br />
	and information are correct.
	<br />
	Thank you for your patience.
</strong>
</center>
<br /><br /><br />

EOH;

					$type = $this->Session['type'];

					if($type == "pp_express")
					{
						$this->Page->Content .= <<< EOH

<h2>Amount: \${$this->Session['amount']} USD</h2>
<br /><br /><br />
<h2>Purchases</h2>
<br />
{$purchases}
<br /><br /><br />
<h2>Payment Information</h2>
<br />
<strong>Payment Method:</strong> PayPal
<br /><br /><br />
<h2>Billing Information</h2>
<br />
<strong>First Name:</strong> {$this->Session['billing_first_name']}
<br />
<strong>Last Name:</strong> {$this->Session['billing_last_name']}
<br />
<strong>Country:</strong> {$this->Session['billing_country']}
<br />
<strong>State:</strong> {$this->Session['billing_state']}
<br />
<strong>City:</strong> {$this->Session['billing_city']}
<br />
<strong>Street Address 1:</strong> {$this->Session['billing_street_1']}
<br />
<strong>Street Address 2:</strong> {$this->Session['billing_street_2']}
<br />
<strong>Postal Code:</strong> {$this->Session['billing_postal_code']}
<br />
<strong>Phone Number:</strong> {$this->Session['billing_phone_number']}
<br />
<strong>Fax Number:</strong> {$this->Session['billing_fax_number']}
<br /><br /><br />
<h2>Shipping Information</h2>
<br />
<strong>First Name:</strong> {$this->Session['shipping_first_name']}
<br />
<strong>Last Name:</strong> {$this->Session['shipping_last_name']}
<br />
<strong>Country:</strong> {$this->Session['shipping_country']}
<br />
<strong>State:</strong> {$this->Session['shipping_state']}
<br />
<strong>City:</strong> {$this->Session['shipping_city']}
<br />
<strong>Street Address 1:</strong> {$this->Session['shipping_street_1']}
<br />
<strong>Street Address 2:</strong> {$this->Session['shipping_street_2']}
<br />
<strong>Postal Code:</strong> {$this->Session['shipping_postal_code']}
<br />
<strong>Phone Number:</strong> {$this->Session['shipping_phone_number']}
<br />
<strong>Fax Number:</strong> {$this->Session['shipping_fax_number']}
<br /><br /><br />
<h2>Order Notes</h2>
<br />
{$this->Session['order_notes']}
<br /><br /><br />
<center>
<form action="{$this->Site->URL}/shop/cart/complete/" method="post">
	<input type="submit" value="Continue" class="submit" />
</form>
</center>
<br /><br /><br />

EOH;
					}
					else if($type == "pp_cc")
					{
						$this->Session->Merge($_REQUEST);
						
						$creditCardNumber = "xxxx-xxxx-xxxx-" . substr($this->Session['creditCardNumber'], 12, 16);
						
						$this->Page->Content .= <<< EOH

<h2>Amount: \${$this->Session['amount']} USD</h2>
<br /><br /><br />
<h2>Purchases</h2>
<br />
{$purchases}
<br /><br /><br />
<h2>Payment Information</h2>
<br />
<strong>Payment Method:</strong> Credit Card
<br />
<strong>First Name:</strong> {$this->Session['user_first_name']}
<br />
<strong>Last Name:</strong> {$this->Session['user_last_name']}
<br />
<strong>Card Type:</strong> {$this->Session['creditCardType']}
<br />
<strong>Card Number:</strong> {$creditCardNumber}
<br />
<strong>Expiration Date:</strong> {$this->Session['expDateMonth']}/{$this->Session['expDateYear']}
<br />
<strong>Card Verification Number:</strong> {$this->Session['cvv2Number']}
<br />
<strong>Country:</strong> {$this->Session['user_country']}
<br />
<strong>State:</strong> {$this->Session['user_state']}
<br />
<strong>City:</strong> {$this->Session['user_city']}
<br />
<strong>Street Address 1:</strong> {$this->Session['user_street_1']}
<br />
<strong>Street Address 2:</strong> {$this->Session['user_street_2']}
<br />
<strong>Postal Code:</strong> {$this->Session['user_postal_code']}
<br /><br /><br />
<h2>Billing Information</h2>
<br />
<strong>First Name:</strong> {$this->Session['billing_first_name']}
<br />
<strong>Last Name:</strong> {$this->Session['billing_last_name']}
<br />
<strong>Country:</strong> {$this->Session['billing_country']}
<br />
<strong>State:</strong> {$this->Session['billing_state']}
<br />
<strong>City:</strong> {$this->Session['billing_city']}
<br />
<strong>Street Address 1:</strong> {$this->Session['billing_street_1']}
<br />
<strong>Street Address 2:</strong> {$this->Session['billing_street_2']}
<br />
<strong>Postal Code:</strong> {$this->Session['billing_postal_code']}
<br />
<strong>Phone Number:</strong> {$this->Session['billing_phone_number']}
<br />
<strong>Fax Number:</strong> {$this->Session['billing_fax_number']}
<br /><br /><br />
<h2>Shipping Information</h2>
<br />
<strong>First Name:</strong> {$this->Session['shipping_first_name']}
<br />
<strong>Last Name:</strong> {$this->Session['shipping_last_name']}
<br />
<strong>Country:</strong> {$this->Session['shipping_country']}
<br />
<strong>State:</strong> {$this->Session['shipping_state']}
<br />
<strong>City:</strong> {$this->Session['shipping_city']}
<br />
<strong>Street Address 1:</strong> {$this->Session['shipping_street_1']}
<br />
<strong>Street Address 2:</strong> {$this->Session['shipping_street_2']}
<br />
<strong>Postal Code:</strong> {$this->Session['shipping_postal_code']}
<br />
<strong>Phone Number:</strong> {$this->Session['shipping_phone_number']}
<br />
<strong>Fax Number:</strong> {$this->Session['shipping_fax_number']}
<br /><br /><br />
<h2>Order Notes</h2>
<br />
{$this->Session['order_notes']}
<br /><br /><br />
<center>
<form action="{$this->Site->URL}/shop/cart/complete/" method="post">
	<input type="submit" value="Continue" class="submit" />
</form>
</center>
<br /><br /><br />

EOH;
					}
					else if($type == "pp_basic")
					{
						$this->Page->Content .= <<< EOH

<h2>Amount: \${$this->Session['amount']} USD</h2>
<br /><br /><br />
<h2>Purchases</h2>
<br />
{$purchases}
<br /><br /><br />
<h2>Payment Information</h2>
<br />
<strong>Payment Method:</strong> PayPal
<br /><br /><br />
<h2>Billing Information</h2>
<br />
<strong>First Name:</strong> {$this->Session['billing_first_name']}
<br />
<strong>Last Name:</strong> {$this->Session['billing_last_name']}
<br />
<strong>Country:</strong> {$this->Session['billing_country']}
<br />
<strong>State:</strong> {$this->Session['billing_state']}
<br />
<strong>City:</strong> {$this->Session['billing_city']}
<br />
<strong>Street Address 1:</strong> {$this->Session['billing_street_1']}
<br />
<strong>Street Address 2:</strong> {$this->Session['billing_street_2']}
<br />
<strong>Postal Code:</strong> {$this->Session['billing_postal_code']}
<br />
<strong>Phone Number:</strong> {$this->Session['billing_phone_number']}
<br />
<strong>Fax Number:</strong> {$this->Session['billing_fax_number']}
<br /><br /><br />
<h2>Shipping Information</h2>
<br />
<strong>First Name:</strong> {$this->Session['shipping_first_name']}
<br />
<strong>Last Name:</strong> {$this->Session['shipping_last_name']}
<br />
<strong>Country:</strong> {$this->Session['shipping_country']}
<br />
<strong>State:</strong> {$this->Session['shipping_state']}
<br />
<strong>City:</strong> {$this->Session['shipping_city']}
<br />
<strong>Street Address 1:</strong> {$this->Session['shipping_street_1']}
<br />
<strong>Street Address 2:</strong> {$this->Session['shipping_street_2']}
<br />
<strong>Postal Code:</strong> {$this->Session['shipping_postal_code']}
<br />
<strong>Phone Number:</strong> {$this->Session['shipping_phone_number']}
<br />
<strong>Fax Number:</strong> {$this->Session['shipping_fax_number']}
<br /><br /><br />
<h2>Order Notes</h2>
<br />
{$this->Session['order_notes']}
<br /><br /><br />
<center>
<form action="{$this->Site->URL}/shop/cart/complete/" method="post">
	<input type="submit" value="Continue" class="submit" />
</form>
</center>
<br /><br /><br />

EOH;
					}
				break;
				
				case "information":
					if($settings['require_authorization'] && !$this->User->Validated)
						$this->Redirect($this->Site->URL . "/account/login/");
					
					$this->Session['type'] = $this->Request['type'] ? $this->Request['type'] : $this->Session['type'];
					
					if($this->Session['type'] === "pp_express")
					{
						$this->Session['paymentType'] = $this->Request['paymentType'] ? $this->Request['paymentType'] : $this->Session['paymentType'];
						$this->Session['currencyCodeType'] = $this->Request['currencyCodeType'] ? $this->Request['currencyCodeType'] : $this->Session['currencyCodeType'];
					}
					else if($this->Session['type'] === "pp_cc")
					{
						$this->Session['paymentType'] = $this->Request['paymentType'] ? $this->Request['paymentType'] : $this->Session['paymentType'];
					}
						
					//$this->Session->Merge($_REQUEST);
					
					$type = $this->Session['type'];
					
					
					$country = <<< EOH

	<option selected="selected" value="">--- Choose Country ---</option>
     <option value="United States">United States</option>
    <option value="Canada">Canada</option>
    <option value="Afghanistan">Afghanistan</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Anguilla">Anguilla</option>
    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Aruba">Aruba</option>
    <option value="Australia">Australia</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belarus">Belarus</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bermuda">Bermuda</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia">Bolivia</option>
    <option value="Borneo">Borneo</option>
    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
    <option value="Botswana">Botswana</option>
    <option value="Brazil">Brazil</option>
    <option value="Brunei">Brunei</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina 
      Faso</option>
    <option value="Burma">Burma</option>
    <option value="Burundi">Burundi</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cape Verde">Cape 
      Verde</option>
    <option value="Central African Rep">Central African 
      Republic</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="China">China</option>
    <option value="Cote d'Ivoire">Cote d'Ivoire</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo, Democratic Republic of">Congo, Democratic Republic 
      of</option>
    <option value="Costa Rica, Republic of the">Costa Rica, 
      Republic of the</option>
    <option value="Croatia">Croatia</option>
    <option value="Cuba">Cuba</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czech Republic">Czech Republic</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dominican Republic</option>
    <option value="East Timor">East Timor</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="France">France</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Germany">Germany</option>
    <option value="Ghana">Ghana</option>
    <option value="Gibraltar">Gibraltar</option>
    <option value="Greece">Greece</option>
    <option value="Greenland">Greenland</option>
    <option value="Grenada">Grenada</option>
    <option value="Guadeloupe">Guadeloupe</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Honduras">Honduras</option>
    <option value="Hong Kong">Hong 
      Kong</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Iran">Iran</option>
    <option value="Iraq">Iraq</option>
    <option value="Ireland">Ireland</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Japan">Japan</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kiribati">Kiribati</option>
    <option value="Korea, North">Korea, North</option>
    <option value="Korea, South">Korea, South</option>
    <option value="Kosovo">Kosovo</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Laos">Laos</option>
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Liberia">Liberia</option>
    <option value="Libya">Libya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Macedonia">Macedonia</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Marshall Islands">Marshall Islands</option>
    <option value="Martinique">Martinique</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mexico">Mexico</option>
    <option value="Micronesia, Federated States of">Micronesia, Federated 
      States of</option>
    <option value="Moldova">Moldova</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Montserrat">Montserrat</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Namibia">Namibia</option>
    <option value="Naura">Naura</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="New Zealand">New 
      Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="Norway">Norway</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Palau">Palau</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Qatar">Qatar</option>
    <option value="Romania">Romania</option>
    <option value="Russia">Russia</option>
    <option value="Rwanda">Rwanda</option>
    <option value="Samoa">Samoa</option>
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="Saint Lucia">Saint Lucia</option>
    <option value="Saint Vincent and Grenadines">Saint Vincent and 
      Grenadines</option>
    <option value="San Marino">San Marino</option>
    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra 
      Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Solomon Islands">Solomon 
      Islands</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="Sudan">Sudan</option>
    <option value="Suriname">Suriname</option>
    <option value="Swaziland">Swaziland</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syria">Syria</option>
    <option value="Taiwan">Taiwan</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania">Tanzania</option>
    <option value="Thailand">Thailand</option>
    <option value="Togo">Togo</option>
    <option value="Tonga">Tonga</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Tuvalu">Tuvalu</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">United Arab Emirates</option>
    <option value="United Kingdom">United Kingdom</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Vanuatu">Vanuatu</option>
    <option value="Vatican City">Vatican 
      City</option>
    <option value="Venezuela">Venezuela</option>
    <option value="Vietnam">Vietnam</option>
    <option value="Virgin Islands">Virgin 
      Islands</option>
    <option value="West Bank and Gaza">West Bank and 
      Gaza</option>
    <option value="Western Sahara">Western Sahara</option>
    <option value="Yemen">Yemen</option>
    <option value="Yugoslavia">Yugoslavia, 
      Federal Republic of</option>
    <option value="Zambia">Zambia</option>
    <option value="Zimbabwe">Zimbabwe</option>

EOH;
					
								$country = str_replace("value=\"{$this->User['country']}\"", "value=\"{$this->User['country']}\" selected=\"selected\"", $country);
					
								$state = <<< EOH

	<option value="">--- Choose State ---</option>
	<option value="NA">Outside USA</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">Dist of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>

EOH;

					$state = str_replace("value=\"{$this->User['state']}\"", "value=\"{$this->User['state']}\" selected=\"selected\"", $state);
			
					$this->Page->Content .= <<< EOH

<script src="{$this->Site->URL}/libs/spry/SpryValidationTextField.js" type="text/javascript"></script>
<script src="{$this->Site->URL}/libs/spry/SpryValidationSelect.js" type="text/javascript"></script>
<link href="{$this->Site->URL}/libs/spry/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="{$this->Site->URL}/libs/spry/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
					
<center>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/">View Cart</a></strong>
	</div>
	<div class="style1 active" style="float: left;">
		<strong>Information</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Preview</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Payment</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Review</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Complete</strong>
	</div>
	<div style="clear: both"></div>
</center>
<br /><br />

<form action="{$this->Site->URL}/shop/cart/preview/" method="post">
	<h2>Shipping Information</h2>
	<br />
	<center>
	<table id="shipping_information" width="100%" cellpadding="0" cellspacing="5">
        <tr>
            <td><strong>First Name <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield1">
            		<input name="shipping_first_name" type="text" size="20" maxlength="50" value="{$this->User['first_name']}" />
            		<span class="textfieldRequiredMsg">A value is required.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>Last Name <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield2">
            		<input name="shipping_last_name" type="text" size="20" maxlength="50" value="{$this->User['last_name']}" />
            		<span class="textfieldRequiredMsg">A value is required.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>Country <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="spryselect1">
            		<select name="shipping_country">{$country}</select>
            		<span class="selectRequiredMsg">Please select an item.</span>
            		<span class="selectInvalidMsg">Please select a valid item.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>State <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="spryselect2">
            		<select name="shipping_state">{$state}</select>
            		<span class="selectInvalidMsg">Please select a valid item.</span>
            		<span class="selectRequiredMsg">Please select an item.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>City <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield3">
            		<input name="shipping_city" type="text" size="20" maxlength="40" value="{$this->User['city']}" />
           			<span class="textfieldRequiredMsg">A value is required.</span>
           		</span>
			</td>
        </tr>
        <tr>
            <td><strong>Street Address 1 <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield4">
            		<input name="shipping_street_1" type="text" size="20" maxlength="100" value="{$this->User['street_1']}" />
        			<span class="textfieldRequiredMsg">A value is required.</span>
        		</span>
        	</td>
		</tr>
        <tr>
            <td><strong>Street Address 2</strong></td>
            <td>
            		<input name="shipping_street_2" type="text" size="20" maxlength="100" value="{$this->User['street_2']}" />
            </td>
        </tr>
        <tr>
            <td><strong>Postal Code <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield5">
            		<input name="shipping_postal_code" type="text" size="20" maxlength="10" value="{$this->User['postal_code']}" />
            		<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>Phone Number <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield6">
            		<input name="shipping_phone_number" type="text" size="20" maxlength="25" value="{$this->User['phone_number']}" />
            		<span class="textfieldRequiredMsg">A value is required.</span>
            		<span class="textfieldInvalidFormatMsg">Invalid format.</span>
            	</span>
           </td>
        </tr>
        <tr>
            <td><strong>Fax Number</strong></td>
            <td>
            	<span id="sprytextfield7">
            		<input name="shipping_fax_number" type="text" size="20" maxlength="25" value="{$this->User['fax_number']}" />
            	</span>
            </td>
        </tr>
    </table>
    </center>
    <br /><br />
	<h2>Billing Information</h2>
	<br />
	<center>
	<input checked type="checkbox" name="same_information" value="yes" /> Same billing and shipping information.
	<br />
	<table id="billing_information" width="100%" cellpadding="0" cellspacing="5">
        <tr>
            <td><strong>First Name <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield8">
            		<input disabled name="billing_first_name" type="text" size="20" maxlength="50" value="{$this->User['first_name']}" />
            		<span class="textfieldRequiredMsg">A value is required.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>Last Name <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield9">
            		<input disabled name="billing_last_name" type="text" size="20" maxlength="50" value="{$this->User['last_name']}" />
            		<span class="textfieldRequiredMsg">A value is required.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>Country <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="spryselect3">
            		<select disabled name="billing_country">{$country}</select>
            		<span class="selectRequiredMsg">Please select an item.</span>
            		<span class="selectInvalidMsg">Please select a valid item.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>State <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="spryselect4">
            		<select disabled name="billing_state">{$state}</select>
            		<span class="selectInvalidMsg">Please select a valid item.</span>
            		<span class="selectRequiredMsg">Please select an item.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>City <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield10">
            		<input disabled name="billing_city" type="text" size="20" maxlength="40" value="{$this->User['city']}" />
           			<span class="textfieldRequiredMsg">A value is required.</span>
           		</span>
			</td>
        </tr>
        <tr>
            <td><strong>Street Address 1 <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield11">
            		<input disabled name="billing_street_1" type="text" size="20" maxlength="100" value="{$this->User['street_1']}" />
        			<span class="textfieldRequiredMsg">A value is required.</span>
        		</span>
        	</td>
		</tr>
        <tr>
            <td><strong>Street Address 2</strong></td>
            <td>
            		<input disabled name="billing_street_2" type="text" size="20" maxlength="100" value="{$this->User['street_2']}" />
            </td>
        </tr>
        <tr>
            <td><strong>Postal Code <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield12">
            		<input disabled name="billing_postal_code" type="text" size="20" maxlength="10" value="{$this->User['postal_code']}" />
            		<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span>
            	</span>
            </td>
        </tr>
        <tr>
            <td><strong>Phone Number <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="sprytextfield13">
            		<input disabled name="billing_phone_number" type="text" size="20" maxlength="25" value="{$this->User['phone_number']}" />
            		<span class="textfieldRequiredMsg">A value is required.</span>
            		<span class="textfieldInvalidFormatMsg">Invalid format.</span>
            	</span>
           </td>
        </tr>
        <tr>
            <td><strong>Fax Number</strong></td>
            <td>
            	<span id="sprytextfield14">
            		<input disabled name="billing_fax_number" type="text" size="20" maxlength="25" value="{$this->User['fax_number']}" />
            	</span>
            </td>
        </tr>
    </table>
    </center>
    <br /><br />
	<h2>Ordering Notes</h2>
	<br />
	<center>
    <textarea name="order_notes" cols="40" rows="10"></textarea>
    </center>
	<br /><br />
	<center><input type="submit" value="Continue" class="submit" /></center>
</form>
<br /><br />
<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		$("[name='same_information']").click(function()
		{
			if($(this).is(":checked"))
				$("#billing_information").find("input, select").attr("disabled", true); 
			else
				$("#billing_information").find("input, select").removeAttr("disabled");
		});			
	});
</script>

<script type="text/javascript">
<!--
new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur", "change"]});
new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur", "change"]});
new Spry.Widget.ValidationSelect("spryselect1", {validateOn:["blur", "change"], invalidValue:""});
new Spry.Widget.ValidationSelect("spryselect2", {validateOn:["blur", "change"], invalidValue:""});
new Spry.Widget.ValidationTextField("sprytextfield3", "none", {validateOn:["blur", "change"]});
new Spry.Widget.ValidationTextField("sprytextfield4", "none", {validateOn:["blur", "change"]});
new Spry.Widget.ValidationTextField("sprytextfield5", "zip_code", {validateOn:["blur"]});
new Spry.Widget.ValidationTextField("sprytextfield6", "phone_number", {validateOn:["blur"], useCharacterMasking:true});

new Spry.Widget.ValidationTextField("sprytextfield8", "none", {validateOn:["blur", "change"]});
new Spry.Widget.ValidationTextField("sprytextfield9", "none", {validateOn:["blur", "change"]});
new Spry.Widget.ValidationSelect("spryselect3", {validateOn:["blur", "change"], invalidValue:""});
new Spry.Widget.ValidationSelect("spryselect4", {validateOn:["blur", "change"], invalidValue:""});
new Spry.Widget.ValidationTextField("sprytextfield10", "none", {validateOn:["blur", "change"]});
new Spry.Widget.ValidationTextField("sprytextfield11", "none", {validateOn:["blur", "change"]});
new Spry.Widget.ValidationTextField("sprytextfield12", "zip_code", {validateOn:["blur"]});
new Spry.Widget.ValidationTextField("sprytextfield13", "phone_number", {validateOn:["blur"], useCharacterMasking:true});
//-->
</script>

EOH;

				break;
				
				case "complete":
					if($settings['require_authorization'] && !$this->User->Validated)
						$this->Redirect($this->Site->URL . "/account/login/");
					
					$type = $this->Session['type'];
					
					if($type == "pp_express")
					{
						require_once(PATH . "/libs/paypal/constants.php");
						require_once(PATH . "/libs/paypal/CallerService.php");
	
	
						/* Gather the information to make the final call to
						   finalize the PayPal payment.  The variable nvpstr
						   holds the name value pairs
						   */
						$token = urlencode($this->Session['token']);
						$paymentAmount = urlencode($this->Session['amount']);
						$paymentType = urlencode($this->Session['paymentType']);
						$currCodeType = urlencode($this->Session['currCodeType']);
						$payerID = urlencode($this->Session['payer_id']);
						$serverName = urlencode($this->Session['SERVER_NAME']);
						
						$nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION='.$paymentType.'&AMT='.$paymentAmount.'&CURRENCYCODE='.$currCodeType.'&IPADDRESS='.$serverName ;
						
						 /* Make the call to PayPal to finalize payment
						    If an error occured, show the resulting errors
						    */
						$resArray = hash_call("DoExpressCheckoutPayment", $nvpstr);
						
						/* Display the API response back to the browser.
						   If the response from PayPal was a success, display the response parameters'
						   If the response was an error, display the errors received using APIError.php.
						   */
						$ack = strtoupper($resArray["ACK"]);
						
						
						if($ack=="SUCCESS")
						{
							$query = array();
				
							$query[] = "`order_amount` = '" . $resArray['AMT'] . "'";
							if($this->Session['order_products']) $query[] = "`order_products` = '" . rawurldecode($this->Session['order_products']) . "'";
							if($currCodeType) $query[] = "`order_currency` = '" . $currCodeType . "'";
							if($resArray['TRANSACTIONID']) $query[] = "`order_transaction_id` = '" . $resArray['TRANSACTIONID'] . "'";
							$query[] = "`shipping_first_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_first_name'])) . "'";
							$query[] = "`shipping_last_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_last_name'])) . "'";
							$query[] = "`shipping_street_1` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_street_1'])) . "'";
							$query[] = "`shipping_street_2` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_street_2'])) . "'";
							$query[] = "`shipping_city` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_city'])) . "'";
							$query[] = "`shipping_state` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_state'])) . "'";
							$query[] = "`shipping_postal_code` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_postal_code'])) . "'";
							$query[] = "`shipping_phone_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_phone_number'])) . "'";
							$query[] = "`shipping_fax_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_fax_number'])) . "'";
							$query[] = "`shipping_country` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_country'])) . "'";
	
								$query[] = "`billing_first_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_first_name'])) . "'";
								$query[] = "`billing_last_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_last_name'])) . "'";
								$query[] = "`billing_street_1` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_street_1'])) . "'";
								$query[] = "`billing_street_2` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_street_2'])) . "'";
								$query[] = "`billing_city` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_city'])) . "'";
								$query[] = "`billing_state` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_state'])) . "'";
								$query[] = "`billing_postal_code` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_postal_code'])) . "'";
								$query[] = "`billing_phone_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_phone_number'])) . "'";
								$query[] = "`billing_fax_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_fax_number'])) . "'";
								$query[] = "`billing_country` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_country'])) . "'";
							
							if($this->User['id']) $query[] = "`user_id` = '" . $this->User['id'] . "'";
							if($this->Session->Cart) $query[] = "`order_products` = '" . $this->Session->Cart . "'";
							$query[] = "`order_status` = 'Completed'";
							$query[] = "`order_type` = 'PayPal Express'";
							$query[] = "`order_date` = FROM_UNIXTIME(" . time() . ")";
							$query[] = "`order_notes` = '" . rawurldecode($this->Session['order_notes']) . "'";
							
							$query = "INSERT INTO `{$this->DB->Prefix}ecommerce_orders` SET " . fix_query(implode(',', $query));
				
							$this->DB->Query($query);
							
							$order_id = mysql_insert_id();
							
							// clear the cart
							$this->Session->Cart = '';
							
							$this->Page->Content .= <<< EOH

<center>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/">View Cart</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Information</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Preview</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Payment</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Review</strong>
	</div>
	<div class="style1 active" style="float: left;">
		<strong>Complete</strong>
	</div>
	<div style="clear: both"></div>
</center>
<br /><br />

<strong>Thank you for your payment!</strong>
<br /><br />
Your order number is {$order_id}. You should keep this number for future reference.
<br /><br />
If you have any questions about your order, please don't hesitate to <a href="{$this->Site->URL}/contact/">contact us</a>.
<br /><br />
<a href="{$this->Site->URL}/"><< Return to Home</a>

EOH;

							$template = &$this->Mail->LoadTemplate("ecommerce_order_received", array("order_id" => $order_id));
							$this->Mail->Send($this->User->Email, $settings['order_email'], $template['title'], $template['content']);

							$template = &$this->Mail->LoadTemplate("ecommerce_order_notify", array("order_id" => $order_id));
							$this->Mail->Send($settings['order_email'], $settings['order_email'], $template['title'], $template['content']);
						}
						else 
						{
							$this->Session['reshash']=$resArray;
							$this->Redirect($this->Site->URL . "/shop/cart/error/");
						}
					}
					else if($type == "pp_cc")
					{
						require_once(PATH . "/libs/paypal/constants.php");
						require_once(PATH . "/libs/paypal/CallerService.php");
						
						/**
						 * Get required parameters from the web form for the request
						 */
						$paymentType = urlencode($this->Session['paymentType']);
						$firstName = urlencode($this->Session['user_first_name']);
						$lastName = urlencode($this->Session['user_last_name']);
						$creditCardType = urlencode($this->Session['creditCardType']);
						$creditCardNumber = urlencode($this->Session['creditCardNumber']);
						$expDateMonth = urlencode($this->Session['expDateMonth']);
						
						// Month must be padded with leading zero
						$padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
						
						$expDateYear = urlencode($this->Session['expDateYear']);
						$cvv2Number = urlencode($this->Session['cvv2Number']);
						$address1 = urlencode($this->Session['user_street_1']);
						$address2 = urlencode($this->Session['user_street_2']);
						$city = urlencode($this->Session['user_city']);
						$state = urlencode($this->Session['user_state']);
						$zip = urlencode($this->Session['user_postal_code']);
						$amount = urlencode($this->Session['amount']);
						$currencyCode = "USD";
	
						/* Construct the request string that will be sent to PayPal.
						   The variable $nvpstr contains all the variables and is a
						   name value pair string with & as a delimiter */
						$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".         $padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state".
						"&ZIP=$zip&COUNTRYCODE=US&CURRENCYCODE=$currencyCode";
						
						/* Make the API call to PayPal, using API signature.
						   The API response is stored in an associative array called $resArray */
						$resArray = hash_call("doDirectPayment", $nvpstr);
						
						/* Display the API response back to the browser.
						   If the response from PayPal was a success, display the response parameters'
						   If the response was an error, display the errors received using APIError.php.
						   */
						$ack = strtoupper($resArray["ACK"]);
						
						if($ack == "SUCCESS")
						{
							$query = array();
				
							$query[] = "`order_amount` = '" . $resArray['AMT'] . "'";
							if($this->Session['user_id']) $query[] = "`user_id` = '" . rawurldecode($this->Session['user_id']) . "'";
							if($this->Session['order_products']) $query[] = "`order_products` = '" . rawurldecode($this->Session['order_products']) . "'";
							if($currCodeType) $query[] = "`order_currency` = '" . $currCodeType . "'";
							if($resArray['TRANSACTIONID']) $query[] = "`order_transaction_id` = '" . $resArray['TRANSACTIONID'] . "'";
							$query[] = "`shipping_first_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_first_name'])) . "'";
							$query[] = "`shipping_last_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_last_name'])) . "'";
							$query[] = "`shipping_street_1` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_street_1'])) . "'";
							$query[] = "`shipping_street_2` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_street_2'])) . "'";
							$query[] = "`shipping_city` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_city'])) . "'";
							$query[] = "`shipping_state` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_state'])) . "'";
							$query[] = "`shipping_postal_code` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_postal_code'])) . "'";
							$query[] = "`shipping_phone_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_phone_number'])) . "'";
							$query[] = "`shipping_fax_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_fax_number'])) . "'";
							$query[] = "`shipping_country` = '" . mysql_real_escape_string(rawurldecode($this->Session['shipping_country'])) . "'";
							
								$query[] = "`billing_first_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_first_name'])) . "'";
								$query[] = "`billing_last_name` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_last_name'])) . "'";
								$query[] = "`billing_street_1` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_street_1'])) . "'";
								$query[] = "`billing_street_2` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_street_2'])) . "'";
								$query[] = "`billing_city` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_city'])) . "'";
								$query[] = "`billing_state` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_state'])) . "'";
								$query[] = "`billing_postal_code` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_postal_code'])) . "'";
								$query[] = "`billing_phone_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_phone_number'])) . "'";
								$query[] = "`billing_fax_number` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_fax_number'])) . "'";
								$query[] = "`billing_country` = '" . mysql_real_escape_string(rawurldecode($this->Session['billing_country'])) . "'";
							
							if($this->User['id']) $query[] = "`user_id` = '" . $this->User['id'] . "'";
							if($this->Session->Cart) $query[] = "`order_products` = '" . $this->Session->Cart . "'";
							$query[] = "`order_status` = 'Completed'";
							$query[] = "`order_type` = 'PayPal Pro'";
							$query[] = "`order_date` = FROM_UNIXTIME(" . time() . ")";
							$query[] = "`order_notes` = '" . rawurldecode($this->Session['order_notes']) . "'";
							
							$query = fix_query(implode(',', $query));
				
							$this->DB->Query("INSERT INTO `{$this->DB->Prefix}ecommerce_orders` SET {$query}");
	
							$order_id = mysql_insert_id();
							
							// clear the cart
							$this->Session->Cart = '';
						
							
							$this->Page->Content .= <<< EOH

<center>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/">View Cart</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Information</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Preview</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Payment</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Review</strong>
	</div>
	<div class="style1 active" style="float: left;">
		<strong>Complete</strong>
	</div>
	<div style="clear: both"></div>
</center>
<br /><br />

<strong>Thank you for your payment!</strong>
<br /><br />
Your order number is {$order_id}. You should keep this number for future reference.
<br /><br />
If you have any questions about your order, please don't hesitate to <a href="{$this->Site->URL}/contact/">contact us</a>.
<br /><br />
<a href="{$this->Site->URL}/"><< Return to Home</a>

EOH;

									$template = &$this->Mail->LoadTemplate("ecommerce_order_received", array("order_id" => $order_id));
									$this->Mail->Send($this->User->Email, $settings['order_email'], $template['title'], $template['content']);
		
									$template = &$this->Mail->LoadTemplate("ecommerce_order_notify", array("order_id" => $order_id));
									$this->Mail->Send($settings['order_email'], $settings['order_email'], $template['title'], $template['content']);
								}
								else 
								{
										$this->Session['reshash']=$resArray;
										$this->Redirect($this->Site->URL . "/shop/cart/error/");
								}

						   }
						   else if($type == "pp_basic")
						   {
								$this->Page->Content .= <<< EOH

<center>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/">View Cart</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Information</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Preview</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Payment</strong>
	</div>
	<div class="style1" style="float: left;">
		<strong>Review</strong>
	</div>
	<div class="style1 active" style="float: left;">
		<strong>Complete</strong>
	</div>
	<div style="clear: both"></div>
</center>
<br /><br />

EOH;




								$query = 
									"SELECT `order_extra`, `order_amount`, `order_currency` FROM `{$this->DB->Prefix}ecommerce_orders` 
									WHERE `order_id` = " . $this->Session['order_id'];
					
								$row = $this->DB->FetchRow($query);
								
								
								$message = <<< EOH

<strong>We're sorry, there's been a problem with your order</strong>
<br /><br />
Your order number is {$this->Session['order_id']}. Please contact our <a href="{$this->Site->URL}/contact/">contact us</a>. Our customer support e-mail is <a href="mailto:{$this->Site['support_email']}">{$this->Site['support_email']}</a>.
<br /><br />
<a href="{$this->Site->URL}/"><< Return to {$this->Site->Title}</a>

EOH;
								
								$paypal = unserialize($row['extra']);

								$order_status = $paypal['payment_status'];
								$order_amount = $paypal['mc_gross'];
								//$order_currency = $paypal['mc_currency'];
								$order_transaction_id = $paypal['txn_id'];

								// check if txn_id has been processed before
								//$query = 
								//	"SELECT `order_transaction_id` 
								//	FROM `{$this->DB->Prefix}ecommerce_orders` 
								//	WHERE `order_transaction_id` = '" . $order_transaction_id . "'";
								
								//if(!$this->DB->FetchRow($query))
								//{
									if($order_status == "Completed" && $order_amount == $row['order_amount']) // && $order_currency == $row['order_currency'])
									{	
										// post back to PayPal system to validate
										$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
										$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
										$header .= "Content-Length: " . strlen($row['order_extra']) . "\r\n\r\n";
										$fp = fsockopen("www.paypal.com", 80, $errno, $errstr, 30);
												
										if(!$fp)
										{
											// HTTP ERROR
										}
										else
										{
											fputs($fp, $header . $row['order_extra']);
											
											while(!feof($fp))
											{
												$res = fgets($fp, 1024);
												
												if(strcmp($res, "VERIFIED") == 0)
												{
													$query = 
														"UPDATE `{$this->DB->Prefix}ecommerce_orders` 
														SET `order_status` 'Completed', `order_transaction_id` = '" . $order_transaction_id . "' 
														WHERE `order_id` = " . $this->Session['order_id'];
										
													$this->DB->Query($query);
												
													$message = <<< EOH

<strong>Thank you for your payment!</strong>
<br /><br />
Your order number is {$this->Session['order_id']}. You should keep this number for future reference.
<br /><br />
If you have any questions about your order, please don't hesitate to <a href="{$this->Site->URL}/contact/">contact us</a>.
<br /><br />
<a href="{$this->Site->URL}/"><< Return to {$this->Site->Title}</a>

EOH;
		
													$template = &$this->Mail->LoadTemplate("ecommerce_order_received", array("order_id" => $order_id));
													$this->Mail->Send($this->User->Email, $settings['order_email'], $template['title'], $template['content']);
					
													$template = &$this->Mail->LoadTemplate("ecommerce_order_notify", array("order_id" => $order_id));
													$this->Mail->Send($settings['order_email'], $settings['order_email'], $template['title'], $template['content']);
												}
												else if(strcmp($res, "INVALID") == 0)
												{
													$query = 
														"UPDATE `{$this->DB->Prefix}ecommerce_orders` 
														SET `order_status` 'Failed', `order_transaction_id` = '" . $order_transaction_id . "' 
														WHERE `order_id` = " . $this->Session['order_id'];
										
													$this->DB->Query($query);
												}
											}
											
											fclose($fp);
										}
									}
									else 
									{
										$query = 
											"UPDATE `{$this->DB->Prefix}ecommerce_orders` 
											SET `order_status` 'Failed', `order_transaction_id` = '" . $order_transaction_id . "' 
											WHERE `order_id` = " . $this->Session['order_id'];
							
										$this->DB->Query($query);
									}
								//}
						   }
							   
								$this->Page->Content .= $message;
								
								// clear the cart
								$this->Session->Cart = '';
						   
							unset($_SESSION['order_id']);
							unset($_SESSION['token']);
							unset($_SESSION['amount']);
							unset($_SESSION['paymentType']);
							unset($_SESSION['currCodeType']);
							unset($_SESSION['payer_id']);
							unset($_SESSION['SERVER_NAME']);
							unset($_SESSION['type']);
							unset($_SESSION['shipping_first_name']);
							unset($_SESSION['shipping_last_name']);
							unset($_SESSION['shipping_street_1']);
							unset($_SESSION['shipping_street_2']);
							unset($_SESSION['shipping_city']);
							unset($_SESSION['shipping_state']);
							unset($_SESSION['shipping_postal_code']);
							unset($_SESSION['shipping_phone_number']);
							unset($_SESSION['shipping_country']);
							unset($_SESSION['billing_first_name']);
							unset($_SESSION['billing_last_name']);
							unset($_SESSION['billing_street_1']);
							unset($_SESSION['billing_street_2']);
							unset($_SESSION['billing_city']);
							unset($_SESSION['billing_state']);
							unset($_SESSION['billing_postal_code']);
							unset($_SESSION['billing_phone_number']);
							unset($_SESSION['billing_country']);
							unset($_SESSION['user_id']);
				break;
				
				case "confirm":
					//$paypal = $_POST;
					//$paypal['cmd'] = "_notify-validate";
	
					$query[] = "`order_status` = 'Completed'";
					
					$query = 
						"UPDATE `{$this->DB->Prefix}ecommerce_orders` 
						SET " . fix_query(implode(',', $query)) . "
						WHERE `order_id` = " . $_POST['item_number'];
		
					$this->DB->Query($query);
				break;
			
				case "payment":
					if($settings['require_authorization'] && !$this->User->Validated)
						$this->Redirect($this->Site->URL . "/account/login/");
					
					//$this->Session->Merge($_REQUEST);

					$this->Page->Content .= <<< EOH

<script src="{$this->Site->URL}/libs/spry/SpryValidationTextField.js" type="text/javascript"></script>
<script src="{$this->Site->URL}/libs/spry/SpryValidationSelect.js" type="text/javascript"></script>
<link href="{$this->Site->URL}/libs/spry/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="{$this->Site->URL}/libs/spry/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
				
EOH;
					
					$type = $this->Session['type'];

					if($type == "pp_basic")
					{
							$this->Redirect("https://www.paypal.com/cgi-bin/webscr/?cmd=_xclick&business=" . urlencode("{$settings['paypal_email']}") . "&item_name=" . urlencode("{$this->Site->Title} Order #{$this->Session['order_id']}") . "&item_number={$this->Session['order_id']}&amount={$this->Session['amount']}&tax=0&no_note=1&currency_code=USD&cancel_return=" . urlencode("{$this->Site->URL}/shop/cart/cancel/") . "&return=" . urlencode("{$this->Site->URL}/shop/cart/thanks/") . "&notify_url=" . urlencode("{$this->Site->URL}/shop/cart/confirm/"));
							/*
							$this->Page->Content .= <<< EOH

<center>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/">View Cart</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/preview/">Preview</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/information/">Information</a></strong>
	</div>
	<div class="style1 active" style="float: left;">
		<strong>Payment</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Review</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Complete</strong>
	</div>
	<div style="clear: both"></div>
</center>
<br /><br />
						
<center>
<form method="post" action="https://www.paypal.com/cgi-bin/webscr" target="paypal">
	<input type="hidden" name="cmd" value="_xclick" />
	<input type="hidden" name="business" value="{$settings['paypal_email']}" />
	<input type="hidden" name="item_name" value="{$this->Site->Name} Order #{$order_id}" />
	<input type="hidden" name="item_number" value="{$order_id}" />
	<input type="hidden" name="amount" value="{$this->Session['amount']}" />
	<input type="hidden" name="tax" value="0" />
	<input type="hidden" name="no_note" value="1" />
	<input type="hidden" name="currency_code" value="USD" />
	<input type="hidden" name="cancel_return" value="{$this->Site->URL}/shop/cart/cancel/" />
	<input type="hidden" name="return" value="{$this->Site->URL}/shop/cart/review/" />
	<input type="hidden" name="notify_url" value="{$this->Site->URL}/shop/cart/confirm/" />
	<input type="submit" name="submit" value="Continue" class="submit" />
</form>
</center>

EOH;
*/
					}
					if($type == "pp_express")
					{
						require_once(PATH . "/libs/paypal/constants.php");
						require_once(PATH . "/libs/paypal/CallerService.php");
	
						$token = $this->Request['token'];
						
						if(!$token)
						{
							/* The servername and serverport tells PayPal where the buyer
							   should be directed back to after authorizing payment.
							   In this case, its the local webserver that is running this script
							   Using the servername and serverport, the return URL is the first
							   portion of the URL that buyers will return to after authorizing payment
							   */
							   //$serverName = $_SERVER['SERVER_NAME'];
							   //$serverPort = $_SERVER['SERVER_PORT'];
							   $url = $this->Site->URL . "/shop/cart/";
					
							   //$this->Session['currencyCodeType'] = $this->Request['currencyCodeType'] ? $this->Request['currencyCodeType'] : $this->Session['currencyCodeType'];
							   //$this->Session['paymentType'] = $this->Request['paymentType'] ? $this->Request['paymentType'] : $this->Session['paymentType'];
							   
							   $paymentAmount = $this->Session['amount'];
							   $currencyCodeType = $this->Session['currencyCodeType'];
							   $paymentType = $this->Session['paymentType'];
							 
					//die(var_dump($this->Session));
							 /* The returnURL is the location where buyers return when a
								payment has been succesfully authorized.
								The cancelURL is the location buyers are sent to when they hit the
								cancel button during authorization of payment during the PayPal flow
								*/
							   
							   $returnURL = urlencode($this->Site->URL . "/shop/cart/payment/");
							   $cancelURL = urlencode($this->Site->URL . "/shop/cart/cancel/");
					
							 /* Construct the parameter string that describes the PayPal payment
								the varialbes were set in the web form, and the resulting string
								is stored in $nvpstr
								*/
							  
							   $nvpstr="&Amt=".$paymentAmount."&PAYMENTACTION=".$paymentType."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType;
//die(var_dump($nvpstr));
							 /* Make the call to PayPal to set the Express Checkout token
								If the API call succeded, then redirect the buyer to PayPal
								to begin to authorize payment.  If an error occured, show the
								resulting errors
								*/
							   $resArray=hash_call("SetExpressCheckout", $nvpstr);
							   $this->Session['reshash'] = $resArray;
					
							   $ack = strtoupper($resArray['ACK']);
										
							   if($ack=="SUCCESS"){
										// Redirect to paypal.com here
										$token = urldecode($resArray['TOKEN']);
										$payPalURL = PAYPAL_URL.$token;
										$this->Redirect($payPalURL);
									  } else  {
										 //Redirecting to APIError.php to display errors. 
	
										$this->Redirect($this->Site->URL . "/shop/cart/error/");
										}
						}
						else
						{
							 /* At this point, the buyer has completed in authorizing payment
								at PayPal.  The script will now call PayPal with the details
								of the authorization, incuding any shipping information of the
								buyer.  Remember, the authorization is not a completed transaction
								at this state - the buyer still needs an additional step to finalize
								the transaction
								*/
					//die(var_dump($this->Request));
							   $token = urlencode($token);
					
							 /* Build a second API request to PayPal, using the token as the
								ID to get the details on the payment authorization
								*/
							   $nvpstr = "&TOKEN=" . $token;
					
							 /* Make the API call and store the results in an array.  If the
								call was a success, show the authorization details, and provide
								an action to complete the payment.  If failed, show the error
								*/
							   $resArray = hash_call("GetExpressCheckoutDetails", $nvpstr);
							   $this->Session['reshash'] = $resArray;
							   $ack = strtoupper($resArray["ACK"]);
					
							   if($ack=="SUCCESS"){			
								$this->Session['token'] = $this->Request['token'];
								$this->Session['payer_id'] = $this->Request['PayerID'];
								
								//$this->Session['currCodeType'] = $this->Request['currencyCodeType'];
								//$this->Session['paymentType'] = $this->Request['paymentType'];
								
								$resArray = $this->Session['reshash'];
								
								$this->Redirect($this->Site->URL . "/shop/cart/review/");
							}
								  else  {
									//Redirecting to APIError.php to display errors. 
									$this->Redirect($this->Site->URL . "/shop/cart/error/");
								  }

						}
					
					}
					else if($type == "pp_cc")
					{
						$this->Page->Title[] = "Checkout";
	
						$country = <<< EOH

	<option selected="selected" value="">--- Choose Country ---</option>
     <option value="United States">United States</option>
    <option value="Canada">Canada</option>
    <option value="Afghanistan">Afghanistan</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Anguilla">Anguilla</option>
    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Aruba">Aruba</option>
    <option value="Australia">Australia</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belarus">Belarus</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bermuda">Bermuda</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia">Bolivia</option>
    <option value="Borneo">Borneo</option>
    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
    <option value="Botswana">Botswana</option>
    <option value="Brazil">Brazil</option>
    <option value="Brunei">Brunei</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina 
      Faso</option>
    <option value="Burma">Burma</option>
    <option value="Burundi">Burundi</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cape Verde">Cape 
      Verde</option>
    <option value="Central African Rep">Central African 
      Republic</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="China">China</option>
    <option value="Cote d'Ivoire">Cote d'Ivoire</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo, Democratic Republic of">Congo, Democratic Republic 
      of</option>
    <option value="Costa Rica, Republic of the">Costa Rica, 
      Republic of the</option>
    <option value="Croatia">Croatia</option>
    <option value="Cuba">Cuba</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czech Republic">Czech Republic</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dominican Republic</option>
    <option value="East Timor">East Timor</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="France">France</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Germany">Germany</option>
    <option value="Ghana">Ghana</option>
    <option value="Gibraltar">Gibraltar</option>
    <option value="Greece">Greece</option>
    <option value="Greenland">Greenland</option>
    <option value="Grenada">Grenada</option>
    <option value="Guadeloupe">Guadeloupe</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Honduras">Honduras</option>
    <option value="Hong Kong">Hong 
      Kong</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Iran">Iran</option>
    <option value="Iraq">Iraq</option>
    <option value="Ireland">Ireland</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Japan">Japan</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kiribati">Kiribati</option>
    <option value="Korea, North">Korea, North</option>
    <option value="Korea, South">Korea, South</option>
    <option value="Kosovo">Kosovo</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Laos">Laos</option>
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Liberia">Liberia</option>
    <option value="Libya">Libya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Macedonia">Macedonia</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Marshall Islands">Marshall Islands</option>
    <option value="Martinique">Martinique</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mexico">Mexico</option>
    <option value="Micronesia, Federated States of">Micronesia, Federated 
      States of</option>
    <option value="Moldova">Moldova</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Montserrat">Montserrat</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Namibia">Namibia</option>
    <option value="Naura">Naura</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="New Zealand">New 
      Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="Norway">Norway</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Palau">Palau</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Qatar">Qatar</option>
    <option value="Romania">Romania</option>
    <option value="Russia">Russia</option>
    <option value="Rwanda">Rwanda</option>
    <option value="Samoa">Samoa</option>
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="Saint Lucia">Saint Lucia</option>
    <option value="Saint Vincent and Grenadines">Saint Vincent and 
      Grenadines</option>
    <option value="San Marino">San Marino</option>
    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra 
      Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Solomon Islands">Solomon 
      Islands</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="Sudan">Sudan</option>
    <option value="Suriname">Suriname</option>
    <option value="Swaziland">Swaziland</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syria">Syria</option>
    <option value="Taiwan">Taiwan</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania">Tanzania</option>
    <option value="Thailand">Thailand</option>
    <option value="Togo">Togo</option>
    <option value="Tonga">Tonga</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Tuvalu">Tuvalu</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">United Arab Emirates</option>
    <option value="United Kingdom">United Kingdom</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Vanuatu">Vanuatu</option>
    <option value="Vatican City">Vatican 
      City</option>
    <option value="Venezuela">Venezuela</option>
    <option value="Vietnam">Vietnam</option>
    <option value="Virgin Islands">Virgin 
      Islands</option>
    <option value="West Bank and Gaza">West Bank and 
      Gaza</option>
    <option value="Western Sahara">Western Sahara</option>
    <option value="Yemen">Yemen</option>
    <option value="Yugoslavia">Yugoslavia, 
      Federal Republic of</option>
    <option value="Zambia">Zambia</option>
    <option value="Zimbabwe">Zimbabwe</option>

EOH;
					
								$country = str_replace("value=\"{$this->User['country']}\"", "value=\"{$this->User['country']}\" selected=\"selected\"", $country);
					
								$state = <<< EOH

	<option value="">--- Choose State ---</option>
	<option value="NA">Outside USA</option>
	<option value="AL">Alabama</option>
	<option value="AK">Alaska</option>
	<option value="AZ">Arizona</option>
	<option value="AR">Arkansas</option>
	<option value="CA">California</option>
	<option value="CO">Colorado</option>
	<option value="CT">Connecticut</option>
	<option value="DE">Delaware</option>
	<option value="DC">Dist of Columbia</option>
	<option value="FL">Florida</option>
	<option value="GA">Georgia</option>
	<option value="HI">Hawaii</option>
	<option value="ID">Idaho</option>
	<option value="IL">Illinois</option>
	<option value="IN">Indiana</option>
	<option value="IA">Iowa</option>
	<option value="KS">Kansas</option>
	<option value="KY">Kentucky</option>
	<option value="LA">Louisiana</option>
	<option value="ME">Maine</option>
	<option value="MD">Maryland</option>
	<option value="MA">Massachusetts</option>
	<option value="MI">Michigan</option>
	<option value="MN">Minnesota</option>
	<option value="MS">Mississippi</option>
	<option value="MO">Missouri</option>
	<option value="MT">Montana</option>
	<option value="NE">Nebraska</option>
	<option value="NV">Nevada</option>
	<option value="NH">New Hampshire</option>
	<option value="NJ">New Jersey</option>
	<option value="NM">New Mexico</option>
	<option value="NY">New York</option>
	<option value="NC">North Carolina</option>
	<option value="ND">North Dakota</option>
	<option value="OH">Ohio</option>
	<option value="OK">Oklahoma</option>
	<option value="OR">Oregon</option>
	<option value="PA">Pennsylvania</option>
	<option value="RI">Rhode Island</option>
	<option value="SC">South Carolina</option>
	<option value="SD">South Dakota</option>
	<option value="TN">Tennessee</option>
	<option value="TX">Texas</option>
	<option value="UT">Utah</option>
	<option value="VT">Vermont</option>
	<option value="VA">Virginia</option>
	<option value="WA">Washington</option>
	<option value="WV">West Virginia</option>
	<option value="WI">Wisconsin</option>
	<option value="WY">Wyoming</option>

EOH;

					$state = str_replace("value=\"{$this->User['state']}\"", "value=\"{$this->User['state']}\" selected=\"selected\"", $state);
					
					$this->Page->Content .= <<< EOH
					
<center>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/">View Cart</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/information/">Information</a></strong>
	</div>
	<div class="style1" style="float: left;">
		<strong><a href="{$this->Site->URL}/shop/cart/preview/">Preview</a></strong>
	</div>
	<div class="style1 active" style="float: left;">
		<strong>Payment</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Review</strong>
	</div>
	<div class="style1 disabled" style="float: left;">
		<strong>Complete</strong>
	</div>
	<div style="clear: both"></div>
</center>
<br /><br />

<center>
<form action="{$this->Site->URL}/shop/cart/review/" method="post">
	<input type="hidden" name="paymentType" value="Sale" />

    <table cellspacing="5" cellpadding="0" border="0">
        <tr>
            <td><strong>Amount being charged:</strong></td>
            <td>\${$this->Session['amount']} USD</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td><strong>First Name <span style="color: #FF0000">*</span></strong></td>
            <td><span id="sprytextfield1">
			<input type="text" size="30" maxlength="32" name="user_first_name" value="{$this->User['first_name']}" />
			<span class="textfieldRequiredMsg"><br /><br />A first name is required.</span></span></td>
        </tr>
        <tr>
            <td><strong>Last Name <span style="color: #FF0000">*</span></strong></td>
			<td><span id="sprytextfield2">
			<input type="text" size="30" maxlength="32" name="user_last_name" value="{$this->User['last_name']}" />
			<span class="textfieldRequiredMsg"><br /><br />
			A last name is required.</span></span></td>
        </tr>
        <tr>
            <td><strong>Card Type <span style="color: #FF0000">*</span></strong></td>
			<td><span id="spryselect1">
			<select name="creditCardType">
			<option value="">--- Choose Card ---</option>
			<option value="Visa">Visa</option>
			<option value="MasterCard">MasterCard</option>
			<option value="Discover">Discover</option>
			<option value="Amex">American Express</option>
			</select>
			<span class="selectInvalidMsg"><br /><br />
			Please select a credit card type.</span>          <span class="selectRequiredMsg">Please select a card type.</span></span></td>
        </tr>
        <tr>
            <td><strong>Card Number <span style="color: #FF0000">*</span></strong></td>
			<td><span id="sprytextfield3">
			<input type="text" size="19" maxlength="19" name="creditCardNumber" value="" />
			<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid card format.</span></span></td>
		</tr>
        <tr>
            <td><strong>Expiration Date <span style="color: #FF0000">*</span></strong></td>
            <td>
            	<span id="spryselect2">
	                <select name="expDateMonth">
	                	<option value="">--- Choose Month ---</option>
	                    <option value="1">01</option>
	                    <option value="2">02</option>
	                    <option value="3">03</option>
	                    <option value="4">04</option>
	                    <option value="5">05</option>
	                    <option value="6">06</option>
	                    <option value="7">07</option>
	                    <option value="8">08</option>
	                    <option value="9">09</option>
	                    <option value="10">10</option>
	                    <option value="11">11</option>
	                    <option value="12">12</option>
	                </select>
	                <span class="selectInvalidMsg">Please select a month.</span>
	                <span class="selectRequiredMsg">Please select an item.</span>
				</span>
				<span id="spryselect3">
	                <select name="expDateYear">
	                	<option value="">--- Choose Year ---</option>
	                    <option value="2004">2004</option>
	                    <option value="2005">2005</option>
	                    <option value="2006">2006</option>
	                    <option value="2007">2007</option>
	                    <option value="2008">2008</option>
	                    <option value="2009">2009</option>
	                    <option value="2010">2010</option>
	                    <option value="2011">2011</option>
	                    <option value="2012">2012</option>
	                    <option value="2013">2013</option>
	                    <option value="2014">2014</option>
	                    <option value="2015">2015</option>
	                    <option value="2016">2016</option>
	                    <option value="2017">2017</option>
	                    <option value="2018">2018</option>
	                </select>
	                <span class="selectRequiredMsg">Please select a year.</span>
	                <span class="selectInvalidMsg">Please select a year.</span>
                </span>
            </td>
        </tr>
        <tr>
            <td><strong>Card Verification Number <span style="color: #FF0000">*</span></strong></td>
			<td>
			<span id="sprytextfield4">
			<input type="text" size="3" maxlength="4" name="cvv2Number" value="" />
			<span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span>
			</td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr>
            <td><strong>Country <span style="color: #FF0000">*</span></strong></td>
            <td><span id="spryselect4"><select name="user_country">{$country}</select><span class="selectInvalidMsg">Please select a Country.</span>              <span class="selectRequiredMsg">Please select an item.</span></span></td>
        </tr>
        <tr>
            <td><strong>State <span style="color: #FF0000">*</span></strong></td>
            <td><span id="spryselect5"><select name="user_state">{$state}</select><span class="selectInvalidMsg"><br /><br />
            Please select a State.</span>            <span class="selectRequiredMsg">Please select an item.</span></span></td>
        </tr>
        <tr>
            <td><strong>City <span style="color: #FF0000">*</span></strong></td>
            <td><span id="sprytextfield5"><input type="text" size="25" maxlength="40" name="user_city" value="{$this->User['city']}" />
            <span class="textfieldRequiredMsg"><br /><br />A City is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
        </tr>
        <tr>
            <td><strong>Street Address 1 <span style="color: #FF0000">*</span></strong></td>
            <td><span id="sprytextfield6"><input type="text" size="25" maxlength="100" name="user_street_1" value="{$this->User['street_1']}" /><span class="textfieldRequiredMsg"><br /><br />A delivery address is required.</span></span></td>
        </tr>
        <tr>
            <td><strong>Street Address 2</strong></td>
            <td><input type="text" size="25" maxlength="100" name="user_street_2" value="{$this->User['street_2']}" /></td>
        </tr>
        <tr>
            <td><strong>Postal Code <span style="color: #FF0000">*</span></strong></td>
            <td><span id="sprytextfield7"><input type="text" size="10" maxlength="10" name="user_postal_code" value="{$this->User['postal_code']}" />
            <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Continue" class="submit" /></td>
        </tr>
    </table>
</form>
</center>

<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "none", {validateOn:["blur", "change"]});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none", {validateOn:["blur", "change"]});
var spryselect1 = new Spry.Widget.ValidationSelect("spryselect1", {invalidValue:"", validateOn:["change", "blur"]});
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "credit_card", {useCharacterMasking:true, validateOn:["blur"], hint:""});
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2", {invalidValue:"", validateOn:["blur", "change"]});
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3", {invalidValue:"", validateOn:["blur", "change"]});
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "integer", {validateOn:["blur", "change"], useCharacterMasking:true, hint:""});
var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4", {invalidValue:"", validateOn:["blur", "change"]});
var spryselect5 = new Spry.Widget.ValidationSelect("spryselect5", {invalidValue:"", validateOn:["blur", "change"]});
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5", "custom", {validateOn:["blur", "change"]});
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "none", {validateOn:["blur", "change"]});
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7", "zip_code", {hint:"", validateOn:["blur"]});
//-->
</script>

EOH;
						}
				break;
				
				case "thanks":
					$this->Page->Content .= <<< EOH

<strong>Thank you for your payment!</strong>
<br /><br />
Your order number is {$this->Session['order_id']}. You should keep this number for future reference.
<br /><br />
If you have any questions about your order, please don't hesitate to <a href="{$this->Site->URL}/contact/">contact us</a>.
<br /><br />
<a href="{$this->Site->URL}/"><< Return to {$this->Site->Title}</a>

EOH;
				break;

				case "update":
					$this->Page->Title[] = "Update Cart";
					
					$this->Session->Cart = array();
					
					foreach($this->Request->ID as $offset => $id)
					{
						if(!$this->Request->Delete[$id])
						for($i = 0, $l = $this->Request->Quantity[$offset]; $i < $l; ++$i)
						{
							$this->Session->Cart[] = $id;
						}
					}
					
					$this->Page->Content = <<< EOH

<div class="success">Shopping cart updated. <a href="{$this->Site->URL}/">Continue shopping...</a></div>
<br />

EOH;

					$this->Session->Cart = implode(',', $this->Session->Cart);
				
				
				default:
					$this->Page->Title[] = "Shopping Cart";

					$this->Page->Content .= <<< EOH

<h2>Your Shopping Cart</h2> 
<br />

EOH;

					if(!$this->Session->Cart)
					{
						$this->Page->Content .= <<< EOH

You have no items in your shopping cart. <a href="{$this->Site->URL}/">Continue shopping...</a>
	
EOH;
					}
					else 
					{
						$items = explode(',', $this->Session->Cart);

						$count = count($items);
						$s = $count > 1 ? 's' : '';
						
						$contents = array();
						
						foreach($items as $item)
						{
							$contents[$item] = isset($contents[$item]) ? ++$contents[$item] : 1;
						}
						
						$this->Page->Content .= <<< EOH

You have {$count} item{$s} in your shopping cart. <a href="{$this->Site->URL}/">Continue shopping...</a>
<br /><br />

<br />
<h2>Your Choices</h2>
<form action="{$this->Site->URL}/shop/cart/update/" method="post">
	<table class="style1" cellpadding="0" cellspacing="10">
		<thead>
			<tr>
			<th align="center" style="width: 20%"><input type="checkbox" class="deleteAll" /> Remove</th>
			<th style="width: 50%">Title</th>
			<th align="right">Price</th>
			<th align="right">Quantity</th>
			<th align="right">Total</th>
			</tr>
		</thead>
		<tbody>
	
EOH;

						$total = 0;
						
						foreach($contents as $id => $quantity)
						{
							$query = "
								SELECT `product_pin`, `product_id`, `product_title`, `product_description`, `product_price`
								FROM `{$this->DB->Prefix}ecommerce_products` 
								WHERE `product_id` = {$id} LIMIT 1";
							
							if($product = $this->DB->FetchRow($query, "slave"))
							{
								$product_total = $product['product_price'] * $quantity;
								
								$this->Page->Content .= <<< EOH

<tr>
	<td align="center"><input type="hidden" value="{$product['product_id']}" name="id[]" /><input type="checkbox" value="{$product['product_id']}" name="delete[{$product['product_id']}]" /></td>
	<td><a href="{$this->Site->URL}/shop/product/{$product['product_id']}/">{$product['product_title']} (#{$product['product_pin']})</a></td>
	<td align="right">\${$product['product_price']}</td>
	<td align="right"><input type="text" name="quantity[]" value="{$quantity}" size="1" maxlength="4" /></td>
	<td align="right">\${$product_total}</td>
</tr>
								
EOH;

								$total += $product['product_price'] * $quantity;
							}
						}
						
						$this->Page->Content .= <<< EOH

		</tbody>
	</table>
	<span class="style1" style="float: right"><strong>Sub-total:</strong> \${$total}</span>
	<br />
	<a title="Update your choices" href="#" onclick="$(this).parent('form:first').submit()"><img src="{$this->Site->URL}/modules/eCommerce/images/cart-update.gif"/></a>
</form>
<br /><br />
<div style="text-align:right">

EOH;
						
						if($settings['paypal_pro'])
						{
							$this->Page->Content .= <<< EOH
						
	<form action="{$this->Site->URL}/shop/cart/information/" method="post">
		<input type="hidden" name="paymentType" value="Sale">
		
		<input type="hidden" name="paymentAmount" size="5" maxlength="7" value="{$total}" />
		<input type="hidden" name="currencyCodeType" value="USD" />
		
		<input type="hidden" name="type" value="pp_cc">
		
		<input type="image" name="submit" title="Checkout your choices" src="{$this->Site->URL}/modules/eCommerce/images/cart-checkout.gif" style="border: 0pt" />
	</form>
	<br />

EOH;
						}
							
						if($settings['paypal_express'])
						{
							$this->Page->Content .= <<< EOH
						
	<form action="{$this->Site->URL}/shop/cart/information/" method="post">
		<input type="hidden" name="paymentType" value="Sale">
		
		<input type="hidden" name="paymentAmount" size="5" maxlength="7" value="{$total}" />
		<input type="hidden" name="currencyCodeType" value="USD" />
		
		<input type="hidden" name="type" value="pp_express">
		
		<input type="image" name="submit" title="Checkout your choices" src="{$this->Site->URL}/modules/eCommerce/images/cart-checkout-paypal-express.gif" style="border: 0pt" />
	</form>
	<br />
	
EOH;
						}
						
						if($settings['paypal_basic'])
						{
							$this->Page->Content .= <<< EOH
	
	<form action="{$this->Site->URL}/shop/cart/information/" method="post">
		<input type="hidden" name="paymentType" value="Sale">
		
		<input type="hidden" name="paymentAmount" size="5" maxlength="7" value="{$total}" />
		<input type="hidden" name="currencyCodeType" value="USD" />
		
		<input type="hidden" name="type" value="pp_basic">
		
		<input type="image" name="submit" title="Checkout your choices" src="{$this->Site->URL}/modules/eCommerce/images/cart-checkout-paypal-basic.gif" style="border: 0pt" />
	</form>
	<br />
	
EOH;
						}

						$this->Page->Content .= <<< EOH
						
</div>

<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		$(".deleteAll").click(function()
		{
			var self = this;
			
			$(this).parents("table:first").find(":checkbox").each(function()
			{
				this.checked = self.checked;
			});
		});
	});
</script>

EOH;
					}
					
				break;
			}
		}
		
		public function __CalculatePrice()
		{
			$items = explode(',', $this->Session->Cart);

			$contents = array();
			
			foreach($items as $item)
			{
				$contents[$item] = isset($contents[$item]) ? ++$contents[$item] : 1;
			}
			
			$total = 0;
			
			foreach($contents as $id => $quantity)
			{
				$query = "
					SELECT `product_id`, `product_title`, `product_description`, `product_price`
					FROM `{$this->DB->Prefix}ecommerce_products` 
					WHERE `product_id` = {$id} LIMIT 1";
				
				if($product = $this->DB->FetchRow($query, "slave"))
				{
					$total += $product['product_price'] * $quantity;
				}
			}
			
			return $total;
		}
		
		public function __Product($action, $id = false)
		{
			switch(strtolower($action))
			{
				case "add":

					$settings = $this->LoadSetting("ecommerce");

					if($settings['enable_https'])
						$this->Site->EnableHTTPS();
					
					$this->Page->Title[] = "Add Product";
					
					$this->Page->Content = <<< EOH

<div class="success">Product added to cart. <a href="{$this->Site->URL}/">Continue shopping...</a></div>
<br />

EOH;

					$cart = $this->Session->Cart ? explode(',', $this->Session->Cart) :  array();
					$cart[] = $id;
					$cart = implode(',', $cart);

					$this->Session->Cart = $cart;

					$this->Cart();

				break;
				
				default:
					// fix for no type
					$id = $action;
					
					$this->Page->Title[] = "View Product";
					
					$query = "
						SELECT `product_pin`, `product_id`, `product_title`, `product_description`, `product_price`, `product_full_description`, `product_previews`, `product_related`, `product_video`, `product_meta_title`, `product_meta_description`, `product_meta_keywords`
						FROM `{$this->DB->Prefix}ecommerce_products` 
						WHERE `product_id` = {$id} LIMIT 1";
					
					if($product = $this->DB->FetchRow($query, "slave"))
					{
						if(isset($product['product_meta_title']) && $product['product_meta_title'] != '')
							$this->Page['meta_title'] = $product['product_meta_title'];
							
						if(isset($product['product_meta_description']) && $product['product_meta_description'] != '')
							$this->Page['meta_description'] = $product['product_meta_description'];
							
						if(isset($product['product_meta_keywords']) && $product['product_meta_keywords'] != '')
							$this->Page['meta_keywords'] = $product['product_meta_keywords'];
					
						$title = str_replace(" ", "-", strtolower($product['product_title']));
						
						$this->Page->Content .= <<< EOH

<h2>{$product['product_title']} (#{$product['product_pin']})</h2>
<br />
<div style="float: left; width: 200px;">
	
EOH;

						$images = explode(',', $product['product_previews']);
						
						foreach($images as $image)
						{
							$path = $this->Site->Path . "/uploads/products/" . $image;

							if(file_exists($this->Site->Path . "/uploads/products/" . $image))
								$this->Page->Content .= "<img src=\"{$this->Site->URL}/uploads/products/{$image}\" />";
							else 
								$this->Page->Content .= "<img src=\"{$this->Site->URL}/uploads/products/default.jpg\" />";
								
							$this->Page->Content .= "<br /><br />";
						}
						
						$related_html = '';
						
						$related_products = $product['product_related'] != "" ? explode(',', $product['product_related']): array();

						foreach($related_products as $related_id)
						{
							$query = 
								"SELECT `product_id`, `product_title`, `product_thumbnail`
								FROM `{$this->DB->Prefix}ecommerce_products` 
								WHERE `product_id` = {$related_id}";
						
							if($data = $this->DB->FetchRow($query, "slave"))
							{
								$related_title = str_replace(' ', '-', strtolower($data['product_title']));
							
								$related_html .= <<< EOH
						
<a class="left" href="{$this->Site->URL}/shop/product/{$data['product_id']}/{$related_title}" title="{$data['product_title']}"><img src="{$this->Site->URL}/uploads/products/{$data['product_thumbnail']}" alt="{$data['product_title']}" /></a>
						
EOH;
							}
						}

						if($product['product_video'] != '')
							$video = <<< EOH

<object width="191" height="141">
	<param name="movie" value="http://www.youtube.com/v/{$product['product_video']}"></param>
	<param name="wmode" value="transparent"></param>
	<embed src="http://www.youtube.com/v/{$product['product_video']}" type="application/x-shockwave-flash" wmode="transparent" width="185" height="155"></embed>
</object>
							
EOH;
						
						$this->Page->Content .= <<< EOH
						
{$video}
</div>
<div style="float: left; width: 210px;">
	<br />
	<strong>PRICE:</strong>
	<br />
	\${$product['product_price']}
	<br /><br />
	<a href="{$this->Site->URL}/shop/product/add/{$product['product_id']}/{$title}/" title="Add {$product['product_title']} to cart"><img src="{$this->Site->URL}/modules/eCommerce/images/cart-add.gif" /></a>
	<br /><br />
	{$product['product_full_description']}
	<br /><br />
	<strong>Related Products (or Accessories):</strong>
	<br /><br />
	{$related_html}
</div>

EOH;
					}
				break;
			}
		}
		
		public function __Category($id = false)
		{
			if($id)
			{
				$query = "
					SELECT `category_id`, `category_title`, `category_description`, `category_meta_title`, `category_meta_description`, `category_meta_keywords`
					FROM `{$this->DB->Prefix}ecommerce_categories` 
					WHERE `category_id` = {$id} LIMIT 1";
				
				if($category = $this->DB->FetchRow($query, "slave"))
				{
					if(isset($category['category_meta_title']) && $category['category_meta_title'] != '')
						$this->Page['meta_title'] = $category['category_meta_title'];
						
					if(isset($category['category_meta_description']) && $category['category_meta_description'] != '')
						$this->Page['meta_description'] = $category['category_meta_description'];
						
					if(isset($category['category_meta_keywords']) && $category['category_meta_keywords'] != '')
						$this->Page['meta_keywords'] = $category['category_meta_keywords'];
						
					$this->Page->Title[] = $category['category_title'];
				
					$this->Page->Content .= <<< EOH

<h2>{$category['category_title']}</h2>
<br />
{$category['category_description']}

EOH;

					$query = "
						SELECT `product_pin`, `product_id`, `product_title`, `product_description`, `product_thumbnail`
						FROM `{$this->DB->Prefix}ecommerce_products` 
						WHERE `category_id` = {$id} LIMIT 10";
					
					foreach($this->DB->FetchRows($query, "slave") as $product)
					{
						$title = str_replace(' ', '-', strtolower($product['product_title']));
						
						$this->Page->Content .= <<< EOH

<div class="box3">
	<div style="float: left">
		<a href="{$this->Site->URL}/shop/product/{$product['product_id']}/{$title}/"><img src="{$this->Site->URL}/uploads/products/{$product['product_thumbnail']}" /></a>
		<br /><br />
		<a href="{$this->Site->URL}/shop/product/{$product['product_id']}/{$title}/">Read more...</a>
	</div>
	<div class="content" style="margin-left: 115px; padding: 5px">
		<a href="{$this->Site->URL}/shop/product/{$product['product_id']}/{$title}/">{$product['product_title']} (#{$product['product_pin']})</a>
		<br /><br />
		{$product['product_description']}
		<br /><br />
	</div>
</div>

EOH;
					}
				}
				else 
				{
					$this->Page->Title[] = "Cannot Find Category";

					$this->Page->Content = <<< EOH

Sorry, that category cannot be found.

EOH;
				}
			}
			else 
			{
					$this->Page->Title[] = "Choose Category";

					$this->Page->Content = <<< EOH

Please choose a category.

EOH;
			}
			
		}
	}
	
	//--------------------------------
	// Setup controller
	//--------------------------------
	
	$this->AddController(new eCommerce(), "shop", "shopping-cart", "catelog");
	
	//--------------------------------
	// Give our module a language
	//--------------------------------

	$this->Languages->eCommerce = new Language("Main");
	
?>