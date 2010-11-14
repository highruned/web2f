<?php

	class Robots extends Module
	{
		public function Setup()
		{
			echo "Setup in development.";
		}
		
		public function Configure()
		{
			echo "No configuration options.";
		}

		public function __CreatePage()
		{
			$this->Page->Header['Content-Type'] = "text/html; charset=UTF-8";
			
			echo <<< EOH
User-agent: *
Disallow: /cgi-bin
Sitemap: {$this->Site->URL}/sitemap.xml

EOH;
		}
	}

	if($this->Request['page'] == "robots.txt")
		$this->AddModule(new Robots());
	
?>