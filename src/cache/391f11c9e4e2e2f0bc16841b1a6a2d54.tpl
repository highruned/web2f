<?php

	$this->Page->Theme->Menu = false;
	
	$this->Page->Theme->Location = <<< EOH
	
<div class="location clear">
	<div class="main">
		<a href="{$this->Site->URL}/admin/" title="{$this->Lang['title_admin']}">{$this->Lang['title_admin']}</a> » 
		<a href="{$this->Site->URL}/admin/design/" title="{$this->Lang['title_design']}">{$this->Lang['title_design']}</a> » 
		<a href="{$this->Site->URL}/admin/design/themes/" title="{$this->Lang['title_themes']}">{$this->Lang['title_themes']}</a> » 
		<a href="{$this->Page->RequestURL}" title="{$this->Lang['title_edit_theme']}">{$this->Lang['title_edit_theme']}</a>
	</div>
</div>
	
EOH;

	if($theme_name = $this->Request['name'])
	{
		if($this->Request['default'] == 1)
		{
			$settings = &$this->LoadSetting("site");

			$settings['theme_name'] = $theme_name;

			$this->SaveSetting("site");

			$this->Redirect($this->Site->URL . "/admin/design/themes/");
		}

		if($this->Request['delete'] == 1)
		{
			$this->Redirect($this->Site->URL . "/admin/design/themes/");
		}
?>
<script type="text/javascript">
	jQuery(document).ready(function()
	{
		$('#container_id').fileTree({ root: '/themes/<?=$theme_name?>/', script: "<?=$this->Site->URL?>/jFileManager/" }, function(file) {
			window.location = "<?=$this->Site->URL?>/admin/design/themes/edit/?name=<?=$theme_name?>&filename=" + file;
		});
	});
</script>

<h2><?=$this->Lang['title_edit_theme']?></h2>
<br/>
<form action="" method="post" onsubmit="editor.saveCode()">
	<div style="float: left; width: 79%;">

<?php
		if($filename = $this->Request['filename'])
		{
			if($content = $this->Request['content'])
			{
				$content = rawurldecode($content);

				$this->SaveFile(fix_path($this->Site->Path . $filename), $content);
				
				$this->Redirect($this->Page->RequestURL);
			}
			else
			{
				$content = get_file($this->Site->Path . $filename);
	
				$extension = strtolower(substr(strrchr($filename, "."), 1));
	
				switch($extension)
				{
					case "tpl":
					case "php": $editor = "codepress php"; break;
					case "pl": $editor = "codepress perl"; break;
					case "js": $editor = "codepress javascript"; break;
					case "css": $editor = "codepress css"; break;
					case "xml":
					case "xsl": $editor = "codepress html"; break;
					case "htm":
					case "html": $editor = "codepress html"; break;
					case "txt": $editor = "codepress"; break;
					default: $editor = false; break;
				}
			}

			if($editor)
			{
?>

<div>
	<textarea id="editor" class="reset <?=$editor?>" name="content" wrap="none" style="height: 400px"><?=$content?></textarea>
</div>

<?php
			}
			else
			{
?>

<?=$this->Lang['editor_bad_file']?>

<?php
			}
?>

<br /><br />
<div style="float: right">
	<input class="reset submit" type="submit" name="submit" value="Save" />
</div>

<?php
		}
		else
		{
?>

<div><?=$this->Lang['choose_template']?></div>

<?php
		}
?>

	</div>
	<div style="float: left; width: 19%;" class="templates">
		<div style="padding-left: 20px">
			<div style="-moz-border-radius-topleft:3px;-moz-border-radius-topright:3px;background-color:#4B7FC4;color:#FFF;padding:10px;"><?=$this->Lang['editor_switch']?></div>
			<div id="container_id"></div>
		</div>
	</div>
</div>

<br /><br /><br /><br /><br />
<link type="text/css" rel="stylesheet" href="<?=$this->Site->URL?>/plugins/jFileManager/css/jFileManager.css" media="screen" />
<script type="text/javascript" src="<?=$this->Site->URL?>/plugins/jFileManager/js/jquery.jFileManager.js"></script>
<script type="text/javascript" src="<?=$this->Site->URL?>/libs/codepress/codepress.js"></script>
<script type="text/javascript" src="<?=$this->Site->URL?>/js/jquery.jResizer.js"></script>
<script type="text/javascript">
	jQuery(window).load(function()
	{
		$("iframe:not(.processed)").jResizer();
	});

	jQuery(document).ready(function()
	{
		$(".expandFolder").click(function()
		{
			$(this).parents("fieldset:first").find(".expandFiles:first").toggle();
		
			return false;
		});
	});
</script>

<?php
	}
	else
	{
?>

<div class="error"><?=$this->Lang['choose_theme']?></div>

<?php
	}
?>