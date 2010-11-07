<?php

	class Blog extends Module
	{
		public function __construct()
		{
			//--------------------------------
			// Initialize settings
			//--------------------------------
			
			$this->Name = "Z Blog";
			$this->Version = "";
			$this->URL = "http://www.z.com";
			$this->Copyright = '' . $this->Version;
		}
		
		public function __Index()
		{
			
		}
		
		public function __Manage()
		{
			
		}
		
		public function __Configure()
		{
			
		}
	}

	//--------------------------------
	// Setup module
	//--------------------------------
	
	$this->AddModule(new Blog());
	
	//--------------------------------
	// Give our module a language
	//--------------------------------
	
	$this->Languages->Blog = new Language("Main");
	
?>
