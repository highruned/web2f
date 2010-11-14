<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Stoke Games | Administration</title>
		
		<meta http-equiv="content-style-type" content="text/css" />
		<meta name="title" content="Stoke Games | Administration" />
		<meta name="description" content="CMS administration panel" />
		<meta name="keywords" content="" />
		<meta name="copyright" content="All Rights Reserved © 2008 <a href='http://www.stokegames.com/'>Stoke Games</a>" />
		<meta name="publisher" content="Stoke Games" />
		<meta name="robots" content="index, follow" />
		<meta name="generator" content="" />

		<link rel="shortcut icon" href="http://www.stokegames.com/images/favicon.ico" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/css/main.css?t=1261555897" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/css/jTooltips.css?t=1261555897" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/themes/admin/css/main.css?t=1261555897" media="screen" />
		<link type="text/css" rel="stylesheet" href="http://www.stokegames.com/themes/admin/css/colors.css?t=1261555897" media="screen" />
		
		<script type="text/javascript" src="http://www.stokegames.com/js/jquery.js"></script>
		<script type="text/javascript" src="http://www.stokegames.com/js/jquery.jTooltips.js?t=1261555897"></script>
		<script type="text/javascript" src="http://www.stokegames.com/js/cms.js?t=1261555897"></script>
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
		<a href="http://www.stokegames.com/admin/" title="Administration">Administration</a>
	</div>
</div>
				<table class="fluid" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="left">
						<div class="menu">
	<div class="main">
		
<ul class="menu fix">
						
<li class=""><a href='http://www.stokegames.com/admin/manage/pages/create/' title='Create Page'>Create Page</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/manage/products/' title='Manage Products'>Manage Products</a></li>
						
<li class=""><a href='http://www.stokegames.com/admin/design/themes/edit/?name=default' title='Edit Theme'>Edit Theme</a></li>

</ul>
								</div>
</div>					</td>
					<td class="right">
						<div class="main">
							
<h2>Administrator Notes</h2>
<br />


<form action="" method="post">
	<input type="hidden" name="note_id" value="1" />

	<textarea name="note_content" wrap="none" class="reset mceEditor" style="width: 100%"><p>Admin notes go here... Great for reminders to do your daily tasks or whatever! <img title="Cool" src="/libs/tiny_mce/plugins/emotions/img/smiley-cool.gif" border="0" alt="Cool" /></p>
<ol>
<li>Need to upload 20 images for new products on 7-30-08</li>
<li>Need to upload 1 pdf for clients</li>
</ol></textarea>


	<br />
	<div style="float: right">
		<input class="reset submit" type="submit" name="submit" value="Save" />
	</div>
</form>

<script language="javascript" type="text/javascript" src="http://www.stokegames.com/libs/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
		theme : "advanced",
		editor_selector : "mceEditor",
		plugins : "safari,spellchecker,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,pagebreak",
		theme_advanced_buttons1_add_before : "newdocument,separator",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,spellchecker,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		plugin_insertdate_dateFormat : "%Y-%m-%d",
		plugin_insertdate_timeFormat : "%H:%M:%S",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		convert_urls : false,
		forced_root_block : false,
		spellchecker_languages : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv"
	});
</script>
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