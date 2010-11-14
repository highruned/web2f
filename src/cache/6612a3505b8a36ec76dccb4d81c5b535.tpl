<?php

	if($this->Request['delete'] && count($this->Request['delete']) > 0)
	{
		$query = 
			"DELETE FROM {$this->DB->Prefix}ecommerce_categories 
			WHERE `category_id` = " . implode(" OR `category_id` = ", $this->Request['delete']);

		$this->DB->Query($query);

		$this->Redirect($this->Page->URL . "/");
	}

	$this->Page->Theme->Menu = "manage";

	$this->Page->Theme->Location = <<< EOH
	
<div class="location clear">
	<div class="main">
		<a href="{$this->Site->URL}/admin/" title="{$this->Lang['title_admin']}">{$this->Lang['title_admin']}</a> » 
		<a href="{$this->Site->URL}/admin/manage/" title="{$this->Lang['title_manage']}">{$this->Lang['title_manage']}</a> » 
		<a href="{$this->Page->RequestURL}" title="{$this->Lang['title_categories']}">{$this->Lang['title_categories']}</a>
	</div>
</div>
	
EOH;

?>

<div class="box-2"><a href="" onClick="document['main'].submit(); return false;" title="Delete"><h3>Delete</h3></a></div><div class="box-2"><a href="create/" title="Create"><h3>Create</h3></a></div>
<br />

<?php

	if($this->Request['sort'] == "ASC")
		$sort = "DESC";
	else
		$sort = "ASC";

	if(!$this->Request['order_by'])
		$this->Request['order_by'] = "category_title";

	if(!$this->Request['sort'])
		$this->Request['sort'] = "ASC";

	$query = 
		"SELECT `category_id`, `category_title`, `category_description`
		FROM `{$this->DB->Prefix}ecommerce_categories` 
		ORDER BY `{$this->Request['order_by']}` {$this->Request['sort']}";

	if($rows = $this->DB->FetchRows($query, "slave"))
	{
?>

<form name="main" action="" action="post">
	<table class="jTable" cellpadding="0" cellspacing="2" border="0" style="width: 100%">
		<thead>
			<tr>
				<th><input type="checkbox" class="deleteAll" /></th>
				<th style="width: 90%"><a href="?order_by=category_title&sort=<?=$sort?>"><?=$this->Lang['title_title']?></a></th>
				<th style=""><?=$this->Lang['title_view']?></th>
			</tr>
		</thead>
		<tbody>
		
<?php
		foreach($rows as $row)
		{
?>

<tr>
	<td>
		<input type="checkbox" name="delete[]" value="<?=$row['category_id']?>" />
	</td>
	<td>
		<a href="edit/<?=$row['category_id']?>/" title="<?=$row['category_title']?>"><?=$row['category_title']?></a>
	</td>
	<td>
		<a href="<?=$this->Site->URL?>/shop/category/<?=$row['category_id']?>/" target="_blank" style="color: #6688DD"><img src="<?=$this->Site->URL?>/images/button_view.gif" /></a>
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