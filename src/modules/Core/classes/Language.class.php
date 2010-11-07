<?php

	class Language extends Registry
	{
		public function __construct($lang)
		{
			//if(is_string($lang))
			//{
			//	$sl_path = G::$Engine->Site->Path . "/modules/" . $module . "/lang/" . G::$Engine->Site['language'] . "/" . $file;
			//	$en_path = G::$Engine->Site->Path . "/modules/" . $module . "/lang/" . "en" . "/" . $file;
			//	
			//	$lang = file_exists($sl_path) ? G::$Engine->LoadFile($sl_path) : file_exists($en_path) ? $this->Lang->Add($en_path) : 0;
			//}
			
			if(is_string($lang))
			{
				// initialize registry
				parent::__construct();
				
				$this->Filename = $lang;
			}
			else if(is_array($lang))
				// initialize registry
				parent::__construct($lang);
			else 
				throw new Exception("Attempted to load bad language file", 69);
		}
	}
	
?>