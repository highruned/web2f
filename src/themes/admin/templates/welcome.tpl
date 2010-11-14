<div class="navigation" style="right: 15px">
<?php
	if($this->Authorized)
	{
?>
	<ul class="fix clear">
		<li style="background-position: 0% 0% !important">
			<div>Welcome, <?=$this->Session->Username?></div>
		</li>
	</ul>
<?php
	}
?>

	<?=$this->DisplayMenu($this->BuildMenu("admin_nav2"), "fix")?>
</div>