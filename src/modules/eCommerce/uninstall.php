<?php

	class Uninstall extends Module
	{
		public function __construct()
		{
			echo "Uninstallation in development.";
		}
	}
	
	$this->AddModule(new Uninstall());
	
?>