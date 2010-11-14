<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Stoke Games | Administration » Manage » Modules » Configure Module</title>
		
		<meta http-equiv="content-style-type" content="text/css" />
		<meta name="title" content="Stoke Games | Administration » Manage » Modules » Configure Module" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="copyright" content="All Rights Reserved © 2008 <a href='http://www.stokegames.com/'>Stoke Games</a>" />
		<meta name="publisher" content="Stoke Games" />
		<meta name="robots" content="index, follow" />
		<meta name="generator" content="" />

		<link rel="shortcut icon" href="http://www.stokegames.com/images/favicon.ico" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/css/main.css?t=1260589274" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/css/jTooltips.css?t=1260589274" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/themes/admin/css/main.css?t=1260589274" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/themes/admin/css/colors.css?t=1260589274" media="screen" />
		
		<script type="text/javascript" src="http://www.stokegames.com/js/jquery.js"></script>
		<script type="text/javascript" src="http://www.stokegames.com/js/jquery.jTooltips.js?t=1260589274"></script>
		<script type="text/javascript" src="http://www.stokegames.com/js/cms.js?t=1260589274"></script>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				CMS.Initialize({url: "http://www.stokegames.com"});
			});
		</script>
		<script type="text/javascript" src="http://www.stokegames.com/themes/admin/js/main.js"></script>
	</head>
	<body>
		<div class="top clear">
			<div class="header">
				<div class="header-l">
					<div class="header-r">
						<div class="header-bg">
							<h1>
								<a href="http://www.stokegames.com/admin/">
									<em></em> Administration
								</a>
							</h1>
							<div class="navigation" style="left: 15px">
	
<ul class="fix clear">
						
<li class=""><a href='http://www.stokegames.com/admin/manage/' title='Manage'>Manage</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/design/' title='Design'>Design</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/configure/' title='Configure'>Configure</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/tools/' title='Tools'>Tools</a></li>

</ul>
							</div><div class="navigation" style="right: 15px">

	
<ul class="fix">
						
<li class=""><a href='http://www.stokegames.com/admin/logout/' title='Logout'>Logout</a></li>

</ul>
							</div>						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="wrapper">
				
<div class="location clear">
	<div class="main">
		<a href="http://www.stokegames.com/admin/" title="Administration">Administration</a> » 
		<a href="http://www.stokegames.com/admin/manage/" title="Manage">Manage</a> » 
		<a href="http://www.stokegames.com/admin/manage/modules/" title="Modules">Modules</a> » 
		<a href="http://www.stokegames.com/admin/manage/modules/configure/eCommerce/" title="eCommerce">eCommerce</a> » 
		<a href="http://www.stokegames.com/admin/manage/modules/configure/eCommerce/" title="Configure Module">Configure Module</a>
	</div>
</div>
				<table class="fluid" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="left">
						<div class="menu">
	<div class="main">
		
<ul class="menu fix">
						
<li class=""><a href='http://www.stokegames.com/admin/manage/pages/' title='Pages'>Pages</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/users/' title='Users'>Users</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/groups/' title='Groups'>Groups</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/categories/' title='Shop Categories'>Shop Categories</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/products/' title='Shop Products'>Shop Products</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/orders/' title='Shop Orders'>Shop Orders</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/menus/' title='Menus'>Menus</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/rewrites/' title='Rewrites'>Rewrites</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/modules/' title='Modules'>Modules</a></li>

</ul>
								</div>
</div>					</td>
					<td class="right">
						<div class="main">
							
<h2>Module Configuration</h2>
<br/>



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

					<input type="radio" value="1" name="enable_https" /> Yes <input checked type="radio" value="0" name="enable_https" checked="" /> No
				</td>
			</tr>
			<tr>
				<td>
					<strong>Require Registration</strong>
				</td>
				<td>

					<input checked type="radio" value="1" name="require_authorization" checked="" /> Yes <input type="radio" value="0" name="require_authorization" /> No

				</td>
			</tr>
			<tr>
				<td>
					<strong>Order E-mail</strong>
				</td>
				<td>
					<input class="special" type="text" name="order_email" maxlength="2048" size="40" value="orders@constantcms.com" />
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
					<input class="special" type="text" name="base_shipping" maxlength="2048" size="40" value="0" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>Sales Tax State</strong>
				</td>
				<td>
					<select name="sales_tax_state">
	<option value="" selected="selected">--- Choose State ---</option>
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
</select>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Sales Tax Percentage</strong>
				</td>
				<td>
					<input class="special" type="text" name="sales_tax_percentage" maxlength="2048" size="40" value="" />
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
					<input checked type="radio" value="1" name="paypal_basic" /> Yes <input type="radio" value="0" name="paypal_basic" /> No

				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal E-mail</strong>
				</td>
				<td>
					<input class="special" type="text" name="paypal_email" maxlength="2048" size="40" value="paypal@constantcms.com" />
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
					<input type="radio" value="1" name="paypal_pro" /> Yes <input checked type="radio" value="0" name="paypal_pro" /> No
				</td>
			</tr>
			<tr>
				<td>
					<strong>Enable PayPal Express</strong>
				</td>
				<td>
					<input type="radio" value="1" name="paypal_express" /> Yes <input checked type="radio" value="0" name="paypal_express" /> No
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Username</strong>
				</td>
				<td>
					<input class="special" type="text" name="paypal_api_username" maxlength="2048" size="40" value="" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Password</strong>
				</td>
				<td>
					<input class="special" type="password" name="paypal_api_password" maxlength="2048" size="40" value="" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Signature</strong>
				</td>
				<td>
					<input class="special" type="text" name="paypal_api_signature" maxlength="2048" size="40" value="" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Sandbox</strong>
				</td>
				<td>

					<input type="radio" value="1" name="paypal_api_sandbox" /> Yes <input checked type="radio" value="0" name="paypal_api_sandbox" /> No
				</td>
			</tr>
			<tr>
				<td>
					<strong>PayPal API Debug</strong>
				</td>
				<td>
					<input type="radio" value="1" name="paypal_api_debug" /> Yes <input checked type="radio" value="0" name="paypal_api_debug" /> No
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

						</div>
						<div class="footer clear">
							<div class="copyright">
															</div>
							<div class="menu">
								<ul class="menu fix">
									<li><a href="/docs/" title=" Documentation" target="_blank">Documentation</a></li>
									<li><a href="/feedback/" title=" Feedback" target="_blank">Feedback</a></li>
									<li><a href="/support/" title=" Support" target="_blank">Support</a></li>
									<li><a href="/forums/" title=" Forums" target="_blank">Forums</a></li>
									<li><a href="/contact/" title=" Contact" target="_blank">Contact</a></li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="bottom clear"></div>
	</body>
</html>