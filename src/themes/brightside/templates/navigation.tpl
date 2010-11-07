<ul style="left: 0pt;">
	<li><a href="<?=$this->Site->URL?>/" title="HOME"><span>HOME</span></a></li>
	<li><a href="<?=$this->Site->URL?>/about/" title="ABOUT US"><span>ABOUT US</span></a></li>
	<li><a href="<?=$this->Site->URL?>/faqs/" title="FAQs"><span>FAQs</a></li>
	<li><a href="<?=$this->Site->URL?>/links/" title="LINKS"><span>LINKS</a></li>
	<li><a href="<?=$this->Site->URL?>/blog/" title="BLOG"><span>BLOG</span></a></li>
	<li><a href="<?=$this->Site->URL?>/forum/" title="FORUM" style="border-right: 0pt none"><span>FORUM</span></a></li>
</ul>

<ul style="right: 0pt;">
<?php

if($this->User->Group['access_level'] > 5)
	echo "<li><strong><a href='{$this->Site->URL}/admin/' title='ADMIN CP'><span>ADMIN CP</span></a></strong></li>";

if($this->User->Authorized)
	echo "<li><a href='{$this->Site->URL}/account/' title='MY ACCOUNT'><span>MY ACCOUNT</span></a></li>";
else
	echo "<li><a href='{$this->Site->URL}/account/login/' title='LOGIN'><span>LOGIN</span></a></li>";

if($this->User->Authorized)
	echo "<li><a href='{$this->Site->URL}/account/logout/' title='LOGOUT'><span>LOGOUT</span></a></li>";
else 
	echo "<li><a href='{$this->Site->URL}/account/register/' title='REGISTER'><span>REGISTER</span></a></li>";

?>
	<li><span><a href="<?=$this->Site->URL?>/shop/cart/" title="VIEW CART"><span>VIEW CART</span></a></li>
	<li><span><a href="<?=$this->Site->URL?>/contact/" title="CONTACT" style="border-right: 0pt none"><span>CONTACT</span></a></li>
</ul>