<?php

	class Site extends Registry
	{
		public function __construct($settings)
		{
			// construct registry
			parent::__construct($settings);

			// create the default theme
			$this->Theme = new Theme($settings['theme_name']);
		}

		public function EnableHTTPS()
		{
			$this->HTTPS = true;
			
			if(empty($_SERVER['HTTPS']))
				G::$Engine->Redirect("https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		}
		
		public function DisableHTTPS()
		{
			$this->HTTPS = false;
			
			if(!empty($_SERVER['HTTPS']))
				G::$Engine->Redirect("http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		}
	}

?>