<?php

	class Configure extends Module
	{
		public function __construct()
		{
			parent::__construct();

			$settings = &$this->LoadSetting("ecommerce");

			if($this->Request['submit'])
			{
				$settings['enable_https'] = $this->Request['enable_https'];
				$settings['require_authorization'] = $this->Request['require_authorization'];
				$settings['order_email'] = $this->Request['order_email'];
				
				$settings['base_shipping'] = $this->Request['base_shipping'];
				$settings['sales_tax_state'] = $this->Request['sales_tax_state'];
				$settings['sales_tax_percentage'] = $this->Request['sales_tax_percentage'];
				
				$settings['paypal_basic'] = $this->Request['paypal_basic'];
				$settings['paypal_email'] = $this->Request['paypal_email'];
				
				$settings['paypal_api_username'] = $this->Request['paypal_api_username'];
				$settings['paypal_api_password'] = $this->Request['paypal_api_password'];
				$settings['paypal_api_signature'] = $this->Request['paypal_api_signature'];
				$settings['paypal_api_sandbox'] = $this->Request['paypal_api_sandbox'];
				$settings['paypal_api_debug'] = $this->Request['paypal_api_debug'];
				$settings['paypal_pro'] = $this->Request['paypal_pro'];
				$settings['paypal_express'] = $this->Request['paypal_express'];
				
				$this->SaveSetting("ecommerce", $settings);

				$this->Redirect($this->Page->RequestURL);
			}
?>


<form action="" method="post">
	<fieldset>
		<legend>
			<strong>General</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Enable HTTPS</strong>
				</td>
				<td>

<?php
	if($settings['enable_https'])
	{
?>
					<input checked type="radio" value="1" name="enable_https" checked="" /> Yes <input type="radio" value="0" name="enable_https" /> No

<?php
	}
	else
	{
?>
					<input type="radio" value="1" name="enable_https" /> Yes <input checked type="radio" value="0" name="enable_https" checked="" /> No
<?php
	}
?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Require Registration</strong>
				</td>
				<td>

<?php
	if($settings['require_authorization'])
	{
?>
					<input checked type="radio" value="1" name="require_authorization" checked="" /> Yes <input type="radio" value="0" name="require_authorization" /> No

<?php
	}
	else
	{
?>
					<input type="radio" value="1" name="require_authorization" /> Yes <input checked type="radio" value="0" name="require_authorization" checked="" /> No
<?php
	}
?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Order E-mail</strong>
				</td>
				<td>
					<input class="special" type="text" name="order_email" maxlength="2048" size="40" value="<?=$settings['order_email']?>" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<fieldset>
		<legend>
			<strong>Shipping</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Additional Cost</strong>
				</td>
				<td>
					<input class="special" type="text" name="base_shipping" maxlength="2048" size="40" value="<?=$settings['base_shipping']?>" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>Sales Tax State</strong>
				</td>
				<td>
<?php
					$state = <<< EOH

	<option value="">--- Choose State ---</option>
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

					$state = str_replace("value=\"{$settings['sales_tax_state']}\"", "value=\"{$settings['sales_tax_state']}\" selected=\"selected\"", $state);
?>
					<select name="sales_tax_state"><?=$state?></select>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Sales Tax Percentage</strong>
				</td>
				<td>
					<input class="special" type="text" name="sales_tax_percentage" maxlength="2048" size="40" value="<?=$settings['sales_tax_percentage']?>" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<fieldset>
		<legend>
			<strong>Paypal Standard</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Enable PayPal Basic</strong>
				</td>
				<td>
<?php
	if($settings['paypal_basic'])
	{
?>
					<input checked type="radio" value="1" name="paypal_basic" /> Yes <input type="radio" value="0" name="paypal_basic" /> No

<?php
	}
	else
	{
?>
					<input type="radio" value="1" name="paypal_basic" /> Yes <input checked type="radio" value="0" name="paypal_basic" /> No
<?php
	}
?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal E-mail</strong>
				</td>
				<td>
					<input class="special" type="text" name="paypal_email" maxlength="2048" size="40" value="<?=$settings['paypal_email']?>" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<fieldset>
		<legend>
			<strong>Paypal API</strong>
		</legend>
		
		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Enable PayPal Pro</strong>
				</td>
				<td>
<?php
	if($settings['paypal_pro'])
	{
?>
					<input checked type="radio" value="1" name="paypal_pro" /> Yes <input type="radio" value="0" name="paypal_pro" /> No

<?php
	}
	else
	{
?>
					<input type="radio" value="1" name="paypal_pro" /> Yes <input checked type="radio" value="0" name="paypal_pro" /> No
<?php
	}
?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Enable PayPal Express</strong>
				</td>
				<td>
<?php
	if($settings['paypal_express'])
	{
?>
					<input checked type="radio" value="1" name="paypal_express" /> Yes <input type="radio" value="0" name="paypal_express" /> No

<?php
	}
	else
	{
?>
					<input type="radio" value="1" name="paypal_express" /> Yes <input checked type="radio" value="0" name="paypal_express" /> No
<?php
	}
?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Username</strong>
				</td>
				<td>
					<input class="special" type="text" name="paypal_api_username" maxlength="2048" size="40" value="<?=$settings['paypal_api_username']?>" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Password</strong>
				</td>
				<td>
					<input class="special" type="password" name="paypal_api_password" maxlength="2048" size="40" value="<?=$settings['paypal_api_password']?>" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Signature</strong>
				</td>
				<td>
					<input class="special" type="text" name="paypal_api_signature" maxlength="2048" size="40" value="<?=$settings['paypal_api_signature']?>" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Sandbox</strong>
				</td>
				<td>

<?php
	if($settings['paypal_api_sandbox'])
	{
?>
					<input checked type="radio" value="1" name="paypal_api_sandbox" /> Yes <input type="radio" value="0" name="paypal_api_sandbox" /> No
<?php
	}
	else
	{
?>
					<input type="radio" value="1" name="paypal_api_sandbox" /> Yes <input checked type="radio" value="0" name="paypal_api_sandbox" /> No
<?php
	}
?>
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Debug</strong>
				</td>
				<td>
<?php
	if($settings['paypal_api_debug'])
	{
?>
					<input checked type="radio" value="1" name="paypal_api_debug" /> Yes <input type="radio" value="0" name="paypal_api_debug" /> No

<?php
	}
	else
	{
?>
					<input type="radio" value="1" name="paypal_api_debug" /> Yes <input checked type="radio" value="0" name="paypal_api_debug" /> No
<?php
	}
?>
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<div style="float: right">
		<input class="reset submit" type="submit" name="submit" value="Save" />
	</div>
</form>
<br /><br /><br /><br /><br />

<?php
		}
	}
	
	$this->AddModule(new Configure());
	
?>