<?php

	class Theme extends MagicMethods
	{
		protected $Settings;
		
		public function __construct($name)
		{
			parent::__construct();

			$path = PATH . "/themes/" . $name . "/theme.xml";
	
			// load the theme settings file if it exists
			if($xml = get_file($path))
				// load xml object, and convert it to an array
				$settings = object2array(simplexml_load_string($xml));
			else 
				$settings = array();
			
			$path = PATH . "/themes/" . $name . "/macros.xml";
	
			// load the theme settings file if it exists
			if($xml = get_file($path))
				// load xml object, and convert it to an array
				$settings['macros'] = object2array(simplexml_load_string($xml));
			else 
				$settings['macros'] = array();

			$settings['path'] = $name;

			$settings['name'] = $name;
			
			foreach($settings as $key => $value)
				if($value !== "" && $value !== " ")
					$this->Settings[$key] = $value;
		}

		public function LoadTemplate($offset)
		{
			$offset_lc = strtolower($offset);
			$offset_uc = strtolower($offset);
			$offset_ucf = ucfirst($offset);
			
			// var is not false
			if($this->Data[$offset] === false)
				return false;
			else if(isset($this->Data[$offset]))
			{
				if(is_string($this->Data[$offset]))
				{
					//loading php
					if(strstr($this->Data[$offset], "<?"))
						return $this->Data[$offset];
					// check for sub-directory templates
					else
					
						if(file_exists($filename = G::$Engine->Site->Path . "/themes/" . $this->Path . "/templates/" . $this->Data[$offset] . "/" . $offset . ".tpl"))
							return ($this->Data[$offset] = new Template(G::$Engine->LoadFile($filename, OUTPUT_BUFFER)));
						else if(file_exists($filename = G::$Engine->Site->Path . "/themes/" . $this->Path . "/templates/" . $this->Data[$offset] . "/" . $offset_lc . ".tpl"))
							return ($this->Data[$offset] = new Template(G::$Engine->LoadFile($filename, OUTPUT_BUFFER)));
						else if(file_exists($filename = G::$Engine->Site->Path . "/themes/" . $this->Path . "/templates/" . $this->Data[$offset] . "/" . $offset_uc . ".tpl"))
							return ($this->Data[$offset] = new Template(G::$Engine->LoadFile($filename, OUTPUT_BUFFER)));
						else if(file_exists($filename = G::$Engine->Site->Path . "/themes/" . $this->Path . "/templates/" . $this->Data[$offset] . "/" . $offset_ucf . ".tpl"))
							return ($this->Data[$offset] = new Template(G::$Engine->LoadFile($filename, OUTPUT_BUFFER)));
						// a preset string (eg. location)
						else
							return $this->Data[$offset];
				}
				else if(is_object($this->Data[$offset]))
					if(get_class($this->Data[$offset]) === "Template")
						return $this->Data[$offset];
			}

			// check for base level templates
			if(file_exists($filename = G::$Engine->Site->Path . "/themes/" . $this->Path . "/templates/" . $offset . ".tpl"))
				return ($this->Data[$offset] = new Template(G::$Engine->LoadFile($filename, OUTPUT_BUFFER)));
			else if(file_exists($filename = G::$Engine->Site->Path . "/themes/" . $this->Path . "/templates/" . $offset_lc . ".tpl"))
				return ($this->Data[$offset] = new Template(G::$Engine->LoadFile($filename, OUTPUT_BUFFER)));
			else if(file_exists($filename = G::$Engine->Site->Path . "/themes/" . $this->Path . "/templates/" . $offset_uc . ".tpl"))
				return ($this->Data[$offset] = new Template(G::$Engine->LoadFile($filename, OUTPUT_BUFFER)));
			else if(file_exists($filename = G::$Engine->Site->Path . "/themes/" . $this->Path . "/templates/" . $offset_ucf . ".tpl"))
				return ($this->Data[$offset] = new Template(G::$Engine->LoadFile($filename, OUTPUT_BUFFER)));

			// dummy call - could be setting up a member array
			return ($this->Data[$offset] = null);
		}
		
		//--------------------------------
		// Magic methods
		//--------------------------------

		public function __get($offset)
		{
			$offset_lc = strtolower($offset);
			$offset_uc = strtolower($offset);
			$offset_ucf = ucfirst($offset);

			// an existing setting
			if(array_key_exists($offset, $this->Settings))
				return $this->Settings[$offset];
			else if(array_key_exists($offset_lc, $this->Settings))
				return $this->Settings[$offset_lc];
			else if(array_key_exists($offset_uc, $this->Settings))
				return $this->Settings[$offset_uc];
			else if(array_key_exists($offset_ucf, $this->Settings))
				return $this->Settings[$offset_ucf];
			// an on-the-fly var
			else
				return $this->LoadTemplate($offset);
		}

		public function __set($offset, $value)
		{
			$offset_lc = strtolower($offset);
			
			if($value !== null)
			{
				if(array_key_exists($offset, $this->Settings))
					return ($this->Settings[$offset] = $value);
				// check if lower case exists instead
				else if(array_key_exists($offset_lc, $this->Settings))
					return ($this->Settings[$offset_lc] = $value);
				else if(array_key_exists($offset, $this->Data))
					return ($this->Data[$offset] = $value);
				// check if lower case exists instead
				else if(array_key_exists($offset_lc, $this->Data))
					return ($this->Data[$offset_lc] = $value);
				else
					return ($this->Data[$offset] = $value);
			}
			else 
				throw new Exception(G::$Engine->Lang['null_value'], 85);
		}
		
		public function __isset($offset)
		{
			if(isset($this->Settings[$offset])) 
				return isset($this->Settings[$offset]);
			else if(isset($this->Data[$offset])) 
				return isset($this->Data[$offset]);
			else 
				return false;
		}
		
		public function __unset($offset)
		{
			if(isset($this->Settings[$offset]))
				unset($this->Settings[$offset]);
			else if(isset($this->Data[$offset]))
				unset($this->Data[$offset]);
		}
	}

?>