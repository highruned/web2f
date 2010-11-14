<?php

	class Languages extends Registry
	{
		public function __construct()
		{
			// construct registry
			parent::__construct();
		}

		public function __set($offset, $value)
		{
			$offset_lc = strtolower($offset);
			
			if($value !== null)
			{
				if(array_key_exists($offset_lc, $this->Data))
					return ($this->Data[$offset_lc] = $value);
				else
				{
					if(is_array($value))
						return ($this->Data[$offset_lc] = &$value);
					else if(is_string($value))
					{
						return ($this->Data[$offset_lc] = G::$Engine->LoadFile($value));
					}
					else if(get_class($value) === "Language")
					{
						if(isset($value->Filename) && is_string($value->Filename))
						{
							$sl_path = G::$Engine->Site->Path . "/modules/" . $offset . "/lang/" . G::$Engine->Site['language'] . "/" . $value->Filename . ".lang.php";
							$en_path = G::$Engine->Site->Path . "/modules/" . $offset . "/lang/" . "en" . "/" . $value->Filename . ".lang.php";
							
							if(file_exists($sl_path))
								$lang = &G::$Engine->LoadFile($sl_path);
							else if(file_exists($en_path))
								$lang = &G::$Engine->LoadFile($en_path);
							else 
								throw new Exception("Could not load language file '{$value->Filename}' for the module '{$offset}'", 69);

							$value->Merge($lang);
						}
						
						return ($this->Data[$offset_lc] = &$value);
					}
				}
			}
			else 
				throw new Exception(G::$Engine->Lang['null_value'], 85);
		}
	}

?>