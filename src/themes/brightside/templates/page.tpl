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
		<div id="wrap">
			<div id="header">				
				<h1 id="logo">bright<span class="green">side</span>of<span class="gray">life</span></h1>	
				<h2 id="slogan">Put your company slogan here...</h2> 
				<form method="post" class="searchform" action="#">
					<p>
						<input type="text" name="search_query" class="textbox" />
			  			<input type="submit" name="search" class="button" value="Search" />
		  			</p>
				</form>
				<?=$this->Page->Theme->Navigation?>						
			</div>	
			<div id="content-wrap">										
				<img src="<?=$this->Page->Theme->URL?>/images/headerphoto.jpg" width="820" height="120" alt="headerphoto" class="no-border" />
				<div id="sidebar" >							
					<?=$this->Page->Theme->Menu?>	
					<h1>Wise Words</h1>
					<p>&quot;Men are disturbed, not by the things that happen,
					but by their opinion of the things that happen.&quot;</p>		
					<p class="align-right">- Epictetus</p>					
				</div>
				<div id="main">	
					<?=$this->Page->Content?>			
				</div>	
				<div id="rightbar">
					<h1>Support Styleshout</h1>
					<p>If you are interested in supporting my work and would like to contribute, you are
					welcome to make a small donation through the 
					<a href="http://www.styleshout.com/">donate link</a> on my website - it will 
					be a great help and will surely be appreciated.</p>	
					<h1>Lorem Ipsum</h1>
					<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec libero. Suspendisse bibendum. 
					Cras id urna. Morbi tincidunt, orci ac convallis aliquam, lectus turpis varius lorem, eu 
					posuere nunc justo tempus leo. Donec mattis, purus nec placerat bibendum, dui pede condimentum 
					odio, ac blandit ante orci ut diam.</p>
				</div>				
			</div>
			<div id="footer">
				<div class="footer-left">
					<p class="align-left">			
					<?=$this->Site->Copyright?>
					</p>		
				</div>
				<div class="footer-right">
					<p class="align-right">
					<a href="<?=$this->Site->URL?>">Home</a>&nbsp;|&nbsp;
			  		<a href="<?=$this->Site->URL?>/sitemap.xml">SiteMap</a>&nbsp;|&nbsp;
			   	<a href="?rss=1">RSS Feed</a>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>