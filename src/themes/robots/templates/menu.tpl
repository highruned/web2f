<div class="menu">
	<div class="main">
		<ul id="menu" class="menu fix">
			<li>
				<span>Shop</span>
				<ul class="submenu fix">
				<?=$this->ListCategories()?>
				</ul>
			</li>
			<li>
				<span>Help</span>
				<?=$this->DisplayMenu($this->BuildMenu("home_help"), "submenu fix")?>
			</li>
			<li>
				<span>Also Available</span>
				<?=$this->DisplayMenu($this->BuildMenu("home_also_available"), "submenu fix")?>
			</li>
		</ul>
	</div>
</div>
