<?php

	class Cron extends Module
	{
		public function Setup()
		{
			echo "Setup in development.";
		}
		
		public function Configure()
		{
			echo "Module in development.";
		}
	}
	
	$this->AddModule(new Cron());
	
?>