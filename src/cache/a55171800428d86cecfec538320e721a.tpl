<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Stoke Games | Administration » Configure » General Settings</title>
		
		<meta http-equiv="content-style-type" content="text/css" />
		<meta name="title" content="Stoke Games | Administration » Configure » General Settings" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="copyright" content="All Rights Reserved © 2008 <a href='http://www.stokegames.com/'>Stoke Games</a>" />
		<meta name="publisher" content="Stoke Games" />
		<meta name="robots" content="index, follow" />
		<meta name="generator" content="" />

		<link rel="shortcut icon" href="http://www.stokegames.com/images/favicon.ico" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/css/main.css?t=1260589032" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/css/jTooltips.css?t=1260589032" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/themes/admin/css/main.css?t=1260589032" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/themes/admin/css/colors.css?t=1260589032" media="screen" />
		
		<script type="text/javascript" src="http://www.stokegames.com/js/jquery.js"></script>
		<script type="text/javascript" src="http://www.stokegames.com/js/jquery.jTooltips.js?t=1260589032"></script>
		<script type="text/javascript" src="http://www.stokegames.com/js/cms.js?t=1260589032"></script>
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
			<a href="http://www.stokegames.com/admin/configure/" title="Configure">Configure</a> » 
			<a href="http://www.stokegames.com/admin/configure/general-settings/" title="General Settings">General Settings</a>
		</div>
	</div>
				<table class="fluid" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="left">
						<div class="menu">
	<div class="main">
		
<ul class="menu fix">
						
<li class=""><a href='http://www.stokegames.com/admin/configure/general-settings/' title='General Settings'>General Settings</a></li>

</ul>
								</div>
</div>					</td>
					<td class="right">
						<div class="main">
							
<form action="" method="post">
	<h2>Essentials</h2>
	<br />
	<fieldset>
		<legend>
			<strong>Website</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Title</strong>
				</td>
				<td>
					<input class="special" type="text" name="title" maxlength="2048" size="40" value="Stoke Games" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>Copyright</strong>
				</td>
				<td>
					<input class="special" type="text" name="copyright" maxlength="2048" size="40" value="All Rights Reserved © 2008 <a href='http://www.stokegames.com/'>Stoke Games</a>" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>Enable HTTPS</strong>
				</td>
				<td>

					<input type="radio" value="1" name="https_enabled" /> Yes <input checked type="radio" value="0" name="https_enabled" /> No
				</td>
			</tr>
			<tr>
				<td>
					<strong>Enable GZip</strong>
				</td>
				<td>

					<input checked type="radio" value="1" name="gzip_enabled" /> Yes <input type="radio" value="0" name="gzip_enabled" /> No
				</td>
			</tr>
			<tr>
				<td>
					<strong>Default Language</strong>
				</td>
				<td>
					<select name="language" style="width: 150px">
<option value='en' selected='selected'>English</a>
<option value='fr'>French (Incomplete)</a>					</select>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Default MIME Type</strong>
				</td>
				<td>
					<select name="mime_type" style="width: 150px">
<option value='text/html'>text/html</a>					</select>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Default Charset</strong>
				</td>
				<td>
					<select name="charset" style="width: 150px">
<option value='iso-8859-1' selected='selected'>iso-8859-1</a>
<option value='utf-8'>utf-8</a>					</select>
				</td>
			</tr>
			<tr>
				<td>
					<strong>Default Group</strong>
				</td>
				<td>
					<select name="group_id" style="width: 150px">

<option value="1" selected="">Guest</option>


<option value="2">Member</option>


<option value="3">Moderator</option>


<option value="4">Administrator</option>


<option value="5">Banned</option>


<option value="18">Validating</option>

					</select>
				</td>
			</tr>
			<tr style="display: none">
				<td>
					<strong>Default Cache Time</strong>
				</td>
				<td>
					<input class="special" type="text" name="cache_time" maxlength="100" size="40" value="" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<fieldset>
		<legend>
			<strong>Meta Tags</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Default Title</strong>
				</td>
				<td>
					<input class="special" type="text" name="meta_title" maxlength="2048" size="40" value="Stoke Games" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>Default Description</strong>
				</td>
				<td>
					<input class="special" type="text" name="meta_description" maxlength="2048" size="40" value="Games." />
				</td>
			</tr>
			<tr>
				<td>
					<strong>Default Keywords</strong>
				</td>
				<td>
					<input class="special" type="text" name="meta_keywords" maxlength="2048" size="40" value="stoke games, online games, rpg, mmorpg, fps, web games" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<fieldset>
		<legend>
			<strong>Administration</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Contact E-mail</strong>
				</td>
				<td>
					<input class="special" type="text" name="email" maxlength="2048" size="40" value="info@stokegames.com" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>Support E-mail</strong>
				</td>
				<td>
					<input class="special" type="text" name="support_email" maxlength="2048" size="40" value="support@stokegames.com" />
				</td>
			</tr>
			<tr>
				<td>
					<strong>Tech E-mail</strong>
				</td>
				<td>
					<input class="special" type="text" name="tech_email" maxlength="2048" size="40" value="tech@stokegames.com" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br /><br />
<div style="display: none">
	<h2>Search</h2>
	<br />
	<fieldset>
		<legend>
			<strong>Defaults</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Search Enabled</strong>
				</td>
				<td>
					<input type="radio" value="1" name="search_enabled" checked="" /> Yes <input type="radio" value="0" name="search_enabled" /> No
				</td>
			</tr>
			<tr>
				<td>
					<strong>Minimum Search Length</strong>
				</td>
				<td>
					<input class="special" type="text" name="search_minimum_length" maxlength="2048" size="40" value="" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br /><br />
	<h2>Web 2.0</h2>
	<br />
	<fieldset>
		<legend>
			<strong>AJAX</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>AJAX Enabled</strong>
				</td>
				<td>

					<input type="radio" value="1" name="ajax_enabled" /> Yes <input checked type="radio" value="0" name="ajax_enabled" /> No
				</td>
			</tr>
		</table>
	</fieldset>
	<br /><br />
	<h2>Advanced</h2>
	<br />
	<fieldset>
		<legend>
			<strong>Website</strong>
		</legend>

	</fieldset>
</div>
	<br />
	<div style="float: right">
		<input class="reset submit" type="submit" name="submit" value="Save" />
	</div>
</form>
<br /><br /><br /><br /><br />						</div>
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