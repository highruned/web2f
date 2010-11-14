<?php if($this->User->Validated) : ?>
<center><strong>Welcome back, <?=$this->User->Username?></strong></center>
<br />
<?php endif; ?>
<div class="menu">
	<div class="main">
		<ul id="menu" class="menu fix">
			<?php if($this->User->Validated) : ?>
			<li>
				<span>Account</span>
				<?=$this->DisplayMenu($this->BuildMenu("home_account"), "submenu fix")?>
			</li>
			<?php endif; ?>
			<li>
				<span>Help</span>
				<?=$this->DisplayMenu($this->BuildMenu("home_help"), "submenu fix")?>
			</li>
		</ul>
	</div>
</div>
