<?php

	$this->Page->Theme->Menu = "manage";
	
	$this->Page->Theme->Location = <<< EOH
	
<div class="location clear">
	<div class="main">
		<a href="{$this->Site->URL}/admin/" title="{$this->Lang['title_admin']}">{$this->Lang['title_admin']}</a> » 
		<a href="{$this->Site->URL}/admin/manage/" title="{$this->Lang['title_manage']}">{$this->Lang['title_manage']}</a> » 
		<a href="{$this->Site->URL}/admin/manage/modules/" title="{$this->Lang['title_modules']}">{$this->Lang['title_modules']}</a> » 
		<a href="{$this->Site->URL}/admin/manage/modules/configure/Mailer/" title="Configure Module">Configure Module</a> » 
		<a href="{$this->Page->RequestURL}" title="Templates">Templates</a>
	</div>
</div>
	
EOH;

	if($this->Request['delete'] && count($this->Request['delete']) > 0)
	{
		$query = 
			"DELETE FROM {$this->DB->Prefix}mailer_templates
			WHERE `template_id` = " . implode(" OR `template_id` = ", $this->Request['delete']);

		$this->DB->Query($query);

		$this->Redirect($this->Page->FullURL . "/");
	}
?>

<div class="box-2"><a href="" onClick="document['main'].submit(); return false;" title="Delete"><h3>Delete</h3></a></div><div class="box-2"><a href="create/" title="Create"><h3>Create</h3></a></div>
<br />

<?php
	if($this->Request['sort'] == "ASC")
		$sort = "DESC";
	else
		$sort = "ASC";

	if(!$this->Request['order_by'])
		$this->Request['order_by'] = "template_name";

	if(!$this->Request['sort'])
		$this->Request['sort'] = "ASC";

	$query = 
		"SELECT `template_id`, `template_name`
		FROM `{$this->DB->Prefix}mailer_templates` 
		ORDER BY `{$this->Request['order_by']}` {$this->Request['sort']}";

	if($rows = $this->DB->FetchRows($query, "slave"))
	{
?>

<form name="main" action="" action="post">
	<table class="jTable" cellpadding="0" cellspacing="2" border="0" style="width: 100%">
		<thead>
			<tr>
				<th><input type="checkbox" class="deleteAll" /></th>
				<th style="width: 100%"><a href="?order_by=template_name&sort=<?=$sort?>"><?=$this->Lang['title_name']?></a></th>
			</tr>
		</thead>
		<tbody>
		
<?php
		foreach($rows as $row)
		{
?>

<tr>
	<td>
		<input type="checkbox" name="delete[]" value="<?=$row['template_id']?>" />
	</td>
	<td>
		<a href="edit/<?=$row['template_id']?>/" title="<?=$row['template_name']?>"><?=$row['template_name']?></a>
	</td>
</tr>
			
<?php
		}
?>

		</tbody>
	</table>
</form>

<?php
	}
?>