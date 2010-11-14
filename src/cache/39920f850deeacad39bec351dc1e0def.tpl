<?php

	$this->Session->Logout();
	
	$this->Page->Theme->Menu = false;

	$this->Redirect($this->Site->URL . "/");

?>