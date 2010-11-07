<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?=$this->Page->Title?></title>
		
		<meta http-equiv="content-style-type" content="text/css" />
		<meta name="title" content="<?=$this->Page->Title?>" />
		<meta name="description" content="<?=$this->Page->Description?>" />
		<meta name="keywords" content="<?=$this->Page->Keywords?>" />
		<meta name="copyright" content="<?=$this->Page->Copyright?>" />
		<meta name="publisher" content="<?=$this->Site->Title?>" />
		<meta name="robots" content="index, follow" />
		<meta name="generator" content="<?=$this->Name?>" />

		<link rel="shortcut icon" href="<?=$this->Site->URL?>/images/favicon.ico" />
		<link type="text/css" rel="stylesheet" href="<?=$this->Site->URL?>/css/main.css?t=<?=time()?>" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?=$this->Site->URL?>/css/jTooltips.css?t=<?=time()?>" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?=$this->Page->Theme->URL?>/css/main.css?t=<?=time()?>" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?=$this->Page->Theme->URL?>/css/colors.css?t=<?=time()?>" media="screen" />
		
		<script type="text/javascript" src="<?=$this->Site->URL?>/js/jquery.js"></script>
		<script type="text/javascript" src="<?=$this->Site->URL?>/js/jquery.jTooltips.js?t=<?=time()?>"></script>
		<script type="text/javascript" src="<?=$this->Site->URL?>/js/cms.js?t=<?=time()?>"></script>
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
								<a href="<?=$this->Site->URL?>/admin/">
									<em><?=$this->Name?></em> Administration
								</a>
							</h1>
							<?=$this->Page->Theme->Navigation?><?=$this->Page->Theme->Welcome?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="wrapper">
			<?=$this->Page->Theme->Location?>
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
								<?=$this->Copyright?>
							</div>
							<div class="menu">
								<ul class="menu fix">
									<li><a href="<?=$this->URL?>/docs/" title="<?=$this->Name?> Documentation" target="_blank">Documentation</a></li>
									<li><a href="<?=$this->URL?>/feedback/" title="<?=$this->Name?> Feedback" target="_blank">Feedback</a></li>
									<li><a href="<?=$this->URL?>/support/" title="<?=$this->Name?> Support" target="_blank">Support</a></li>
									<li><a href="<?=$this->URL?>/forums/" title="<?=$this->Name?> Forums" target="_blank">Forums</a></li>
									<li><a href="<?=$this->URL?>/contact/" title="<?=$this->Name?> Contact" target="_blank">Contact</a></li>
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