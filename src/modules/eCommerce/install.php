<?php

	class Install extends Module
	{
		public function __construct()
		{
			echo "Installation in development.";
		}
	}
	
	$this->AddModule(new Install());
	
?>