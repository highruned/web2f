<?php

	class User extends Registry
	{
		public function __construct()
		{
			// initialize registry
			parent::__construct();
			
			// start with guest access
			$this['group'] = new Group();
			$this['authorized'] = false;
		}
		
		public function IsSpider()
		{
			$spiders = array(
                "Teoma",                   
                "alexa",
                "froogle",
                "inktomi",
                "looksmart",
                "URL_Spider_SQL",
                "Firefly",
                "NationalDirectory",
                "Ask Jeeves",
                "TECNOSEEK",
                "InfoSeek",
                "WebFindBot",
                "girafabot",
                "crawler",
                "www.galaxy.com",
                "Googlebot",
                "Scooter",
                "Slurp",
                "appie",
                "FAST",
                "WebBug",
                "Spade",
                "ZyBorg",
                "rabaz");
                
			foreach($spiders as $spider)
			{
				if(ereg($spider, $_SERVER['HTTP_USER_AGENT']))
				{
					return true;
				}
			}

			return false;
		}
	}

?>