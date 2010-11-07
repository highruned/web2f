<?php

	$this->Page->Theme->Menu = "manage";
	
	$this->Page->Theme->Location = <<< EOH
	
<div class="location clear">
	<div class="main">
		<a href="{$this->Site->URL}/admin/" title="{$this->Lang['title_admin']}">{$this->Lang['title_admin']}</a> » 
		<a href="{$this->Site->URL}/admin/manage/" title="{$this->Lang['title_manage']}">{$this->Lang['title_manage']}</a> » 
		<a href="{$this->Site->URL}/admin/manage/modules/" title="{$this->Lang['title_modules']}">{$this->Lang['title_modules']}</a> »  
		<a href="{$this->Site->URL}/admin/manage/modules/configure/Mailer/" title="Configure Module">Configure Module</a> » 
		<a href="{$this->Site->URL}/admin/manage/modules/configure/Mailer/templates/" title="Templates">Templates</a> » 
		<a href="{$this->Page->RequestURL}" title="Edit Template">Edit Template</a>
	</div>
</div>
	
EOH;

	if($id = $this->Request['id'])
	{
		if($this->Request['submit'])
		{
			$query = array();

			$query[] = "`template_name` = '" . addslashes(rawurldecode($this->Request['template_name'])) . "'";
			$query[] = "`template_title` = '" . addslashes(rawurldecode($this->Request['template_title'])) . "'";
			$query[] = "`template_content` = '" . addslashes(rawurldecode($this->Request['template_content'])) . "'";

			$query = 
				"UPDATE `{$this->DB->Prefix}mailer_templates` 
				SET " . fix_query(implode(",", $query)) . " 
				WHERE `template_id` = " . $id;

			if(!$this->DB->Query($query, "master", false))
				$this->Session['message'] = 1;
			else
				$this->Session['message'] = 2;

			$this->Redirect($this->Page->RequestURL);
		}

		$query = 
			"SELECT `template_name`, `template_title`, `template_content`
			FROM `{$this->DB->Prefix}mailer_templates` 
			WHERE `template_id` = {$id} 
			LIMIT 1";

		if($data = $this->DB->FetchRow($query, "slave"))
		{
			$data['template_content'] = str_replace("\n", "<br />", $data['template_content']);
			$data['template_content'] = stripslashes($data['template_content']);
			$data['template_title'] = stripslashes($data['template_title']);
			$data['template_name'] = stripslashes($data['template_name']);
			
			switch($this->Session['message'])
			{
				case 1: 
?>

<div class="error">Edit failed.</div>

<?php
				break;
				case 2: 
?>

<div class="success">Edit successful.</div>

<?php
				break;
			}

			unset($this->Session['message']);
?>

<form action="" method="post">
	<table cellpadding="0" cellspacing="5" border="0" style="width: 100%">
		<tr>
			<td style="width: 150px">
				<h4><strong>Name</strong><span class="formInfo"><a class="jTip" id="tt1" title="Help" rel='Name of the mail template you are creating.'>?</a></span><br class="clear" /></h4>
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="template_name" value="<?=$data['template_name']?>" class="reset" style="border: 1px solid #DDD;padding: 6px" />
			</td>
		</tr>
		<tr>
			<td>
				<h4><strong>Title</strong><span class="formInfo"><a class="jTip" id="tt2" title="Help" rel="">?</a></span><br class="clear" /></h4>
			</td>
			<td>
				<input type="text" maxlength="100" size="40" name="template_title" value="<?=$data['template_title']?>" class="reset" style="border: 1px solid #DDD;padding: 6px" />
			</td>
		</tr>
		<tr>
			<td class="fix">
				<h4><strong>Content</strong><span class="formInfo"><a class="jTip" id="tt3" title="Help" rel="">?</a></span><br class="clear" /></h4>
			</td>
			<td>
				<textarea id="template_content" name="template_content" wrap="none" class="reset mceEditor" style="width: 100%"><?=$data['template_content']?></textarea>
			</td>
		</tr>
	</table>
	<br />
	<div style="float: right">
		<input class="reset submit" type="submit" name="submit" value="Save" />
	</div>
</form>
<br /><br /><br /><br /><br />

<script type="text/javascript">
	jQuery(document).ready(function($)
	{
		$(".slideLink").click(function()
		{
			$(".slideBox").toggle();
		});
	});
</script>

<script language="javascript" type="text/javascript" src="<?=$this->Site->URL?>/libs/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
		editor_selector : "mceEditor",
		theme : "advanced",
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
		valid_elements : "*[*]",
		convert_urls : false,
		forced_root_block : false,
		force_br_newlines : true,
		spellchecker_languages : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv"
	});
</script>

<?php
		}
		else
		{
?>

<div class="error"><?=$this->Lang['cannot_find_group']?></div>

<?php
		}
	}
	else
	{
?>

<div class="error"><?=$this->Lang['select_group']?></div>

<?php
	}

?>