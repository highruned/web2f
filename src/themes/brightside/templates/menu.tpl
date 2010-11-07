<?php if($this->User->Validated) : ?>
<h1>Aaccount</h1>
<?=$this->DisplayMenu($this->BuildMenu("home_account"), "sidemenu")?>
<?php endif; ?>
<h1>Shop</h1>
<ul class="sidemenu">
<?=$this->ListCategories()?>
</ul>
<h1>Help</h1>
<?=$this->DisplayMenu($this->BuildMenu("home_help"), "sidemenu")?>
<h1>Also Available</h1>
<?=$this->DisplayMenu($this->BuildMenu("home_also_available"), "sidemenu")?>