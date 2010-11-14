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
		<link type="text/css" rel="stylesheet" href="<?=$this->Site->URL?>/css/jTooltips.css" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?=$this->Page->Theme->URL?>/css/main.css" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?=$this->Page->Theme->URL?>/css/colors.css" media="screen" />
		
		<script type="text/javascript" src="<?=$this->Site->URL?>/js/jquery.js"></script>
		<script type="text/javascript" src="<?=$this->Site->URL?>/js/jquery.jTooltips.js"></script>
		<script type="text/javascript" src="<?=$this->Site->URL?>/js/cms.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				CMS.Initialize({url: "<?=$this->Site->URL?>"});
			});
		</script>
		<script type="text/javascript" src="<?=$this->Page->Theme->URL?>/js/main.js"></script>
	</head>
	<body>
		<div class="top clear">
			<div class="header">
				<div class="header-l">
					<div class="header-r">
						<div class="header-bg">
							<h1>
								<a href="<?=$this->Site->URL?>/">
									<em><?=$this->Site->Title?></em>
								</a>
							</h1>
							<?=$this->Page->Theme->Navigation?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="wrapper">
			<table class="fluid" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="left">
						<?=$this->Page->Theme->Menu?>
					</td>
					<td class="right">
						<div class="main">
							<?=$this->Page->Content?>
						</div>
						<div class="footer clear">
							<div class="copyright">
								<?=$this->Site->Copyright?>
							</div>
							<div class="menu">
								<ul class="menu fix">
									<li><a href="<?=$this->URL?>/link/">Link</a></li>
									<li><a href="<?=$this->URL?>/link/">Link</a></li>
									<li><a href="<?=$this->URL?>/link/">Link</a></li>
									<li><a href="<?=$this->URL?>/link/">Link</a></li>
									<li><a href="<?=$this->URL?>/link/">Link</a></li>
								</ul>
								<div>
									<a href="<?=$this->Page->RequestURL?>?rss=1">
										<img src="<?=$this->Page->Theme->URL?>/images/rss-feed.jpg" title="<?=$this->Site->Title?> RSS Feed" width="20" height="20" />
									</a>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="bottom clear"></div>
	</body>
</html>
