<?php

	$this->Page->Theme->Menu = "manage";
	
	$this->Page->Theme->Location = <<< EOH
	
<div class="location clear">
	<div class="main">
		<a href="{$this->Site->URL}/admin/" title="{$this->Lang['title_admin']}">{$this->Lang['title_admin']}</a> » 
		<a href="{$this->Site->URL}/admin/manage/" title="{$this->Lang['title_manage']}">{$this->Lang['title_manage']}</a> » 
		<a href="{$this->Page->RequestURL}" title="Menus">Menus</a>
	</div>
</div>
	
EOH;

	if($this->Request['delete'] && count($this->Request['delete']) > 0)
	{
		$query = 
			"DELETE FROM {$this->DB->Prefix}menus 
			WHERE `menu_id` = " . implode(" OR `menu_id` = ", $this->Request['delete']);

		$this->DB->Query($query);

		$this->Redirect($this->Page->URL . "/");
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
		$this->Request['order_by'] = "menu_name";

	if(!$this->Request['sort'])
		$this->Request['sort'] = "ASC";

	$query = 
		"SELECT `menu_id`, `menu_name`, `menu_title`
		FROM `{$this->DB->Prefix}menus` 
		ORDER BY `{$this->Request['order_by']}` {$this->Request['sort']}";

	if($rows = $this->DB->FetchRows($query, "slave"))
	{
?>

<form name="main" action="" action="post">
	<table class="jTable" cellpadding="0" cellspacing="2" border="0" style="width: 100%">
		<thead>
			<tr>
				<th><input type="checkbox" class="deleteAll" /></th>
				<th><a href="?order_by=menu_name&sort=<?=$sort?>"><?=$this->Lang['title_name']?></a></th>
				<th style="width: 90%"><a href="?order_by=menu_title&sort=<?=$sort?>"><?=$this->Lang['title_title']?></a></th>
			</tr>
		</thead>
		<tbody>
		
<?php
		foreach($rows as $row)
		{
?>

<tr>
	<td>
		<input type="checkbox" name="delete[]" value="<?=$row['menu_id']?>" />
	</td>
	<td>
		<a href="edit/<?=$row['menu_id']?>/" title="<?=$row['menu_name']?>"><?=$row['menu_name']?></a>
	</td>
	<td>
		<a href="edit/<?=$row['menu_id']?>/" title="<?=$row['menu_title']?>"><?=$row['menu_title']?></a>
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