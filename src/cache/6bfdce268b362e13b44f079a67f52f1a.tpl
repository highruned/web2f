<?php

	$this->Session->Logout();
	
	$this->Redirect($this->Site->URL . "/admin/login/");

?>