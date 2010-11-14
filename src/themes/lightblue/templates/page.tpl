<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?=$this->Page->Title?></title>

		<meta http-equiv="content-style-type" content="text/css" />
		<meta name="title" content="<?=$this->Page['meta_title']?>" />
		<meta name="description" content="<?=$this->Page['meta_description']?>" />
		<meta name="keywords" content="<?=$this->Page['meta_keywords']?>" />
		<meta name="publisher" content="<?=$this->Site->Title?>" />
		<meta name="robots" content="index, follow" />
		<meta name="generator" content="<?=$this->Name?>" />

		<link rel="shortcut icon" href="<?=$this->Site->URL?>/images/favicon.ico" />
		<link type="application/rss+xml" rel="alternate" title="<?=$this->Page->Title?> - RSS" href="<?=$this->Page->RequestURL?>?rss=1">
		<link type="text/css" rel="stylesheet" href="<?=$this->Site->URL?>/css/main.css" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?=$this->Page->Theme->URL?>/css/main.css" media="screen" />

		<script type="text/javascript" src="<?=$this->Site->URL?>/js/jquery.js"></script>
		<script type="text/javascript" src="<?=$this->Site->URL?>/js/cms.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				CMS.Initialize({url: "<?=$this->Site->URL?>", ajax: 0});
			});
		</script>
	</head>
	<body>
<div id="container">

<div id="header">
	<h1><?=$this->Site->Title?></h1>
	<h2>space reserved for your website slogan!</h2>
</div>

<div id="navigation">
	<?=$this->Page->Theme->Navigation?>
</div>

<div id="content">
	<div id="sidecontent">
		<?=$this->Page->Content?>
	</div>
	<div id="sidebar">
		<?=$this->Page->Theme->Menu?>
		<div id="searchbar" style="display: none">
		<h2>Search</h2>
		<form action="<?=$this->Site->Title?>/search/" method="get">
			<fieldset>
				<input name="q" size="10" maxlength="2048" alt="Search <?=$this->Site->Title?>" />
				<input type="submit" value="Go!" />
			</fieldset>
		</form>
		</div>
	</div>
</div>

<div id="footer" style="position:relative">
    <div style="text-align:center"><?=$this->Page->Copyright?></div>
    <div style="position:absolute; top: 3px; right:3px;">
		<a href="<?=$this->Page->RequestURL?>?rss=1"><img src="<?=$this->Page->Theme->URL?>/images/rss-feed.jpg" title="<?=$this->Site->Title?> RSS Feed" width="20" height="20" /></a>
	</div>
</div>

</div>
	</body>
</html>