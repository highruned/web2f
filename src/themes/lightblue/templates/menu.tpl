<div class="small box">
<?php

	if($this->User->Authorized)
	{
?>

Welcome back, <?=$this->User['first_name']?>

<?php
	}
	else 
	{
?>

<a href="<?=$this->Site->URL?>/account/register/" title="Register">Register</a>
<br /><br />
<a href="<?=$this->Site->URL?>/account/login/" title="Login">Already a member? Log in</a>
<br /><br />
<a href="<?=$this->Site->URL?>/account/password/recover/" title="Recover Password">Recover Password</a>

<?php
	}
?>
</div>

<?php if($this->User->Validated) : ?>
<h2>Account</h2>
<?=$this->DisplayMenu($this->BuildMenu("home_account"), "menublock")?>
<?php endif; ?>
<h2>Shop</h2>
<ul class="menublock">
<?=$this->ListCategories()?>
</ul>
<h2>Help</h2>
<?=$this->DisplayMenu($this->BuildMenu("home_help"), "menublock")?>
<h2>Also Available</h2>
<?=$this->DisplayMenu($this->BuildMenu("home_also_available"), "menublock")?>