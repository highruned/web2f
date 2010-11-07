<?php

	class Engine extends ModuleManager
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->Settings = array();
		}

		//--------------------------------
		// Methods
		//--------------------------------
		
		public function LoadModules()
		{
			//die(encode(array("Database" => array("enabled" => 1), "Core" => array("enabled" => 1), "Rewrites" => array("enabled" => 1), "CMS" => array("enabled" => 1))));

			$settings = &$this->LoadSetting("modules");

			foreach($settings as $module => &$options)
				if($options['enabled'] === "1")
					$this->LoadModule($module);
		}
		
		public function &LoadSetting($name)
		{
			if(isset($this->Settings[$name]))
			{
				$value = &$this->Settings[$name];

				return $value;
			}
			else
			{
				$query = 
					"SELECT `setting_data` 
					FROM `" . $this->DB->Prefix . "settings` 
					WHERE `setting_name` = '" . $name . "'";
	
				if($data = $this->DB->FetchRow($query, "slave")) 
				{
					$this->Settings[$name] = decode($data['setting_data']);
	
					$value = &$this->Settings[$name];
					
					return $value;
				}
				else 
					return false;
			}
		}

		public function SaveSetting($name, $data = null)
		{
			if(isset($this->Settings[$name]))
			{
				
				if($data === null)
					$data = encode($this->Settings[$name]);
				else 
					$data = encode($data);
				
				$query = 
					"INSERT INTO `" . $this->DB->Prefix . "settings` 
					SET `setting_name` = '" . $name . "', `setting_data` = '" . $data . "'
					ON DUPLICATE KEY UPDATE `setting_data` = '" . $data . "'";

				return $this->DB->Query($query);
			}
			else 
				return false;
		}
	
		public function FindClass($class)
		{
			foreach($this->Modules['loaded'] as $module => $value)
			{
				$path = PATH . "/modules/" . $module . "/classes/" . $class . ".class.php";

				if(file_exists($path))
					return $path;
			}

			return false;
		}

		// required to keep template scope inside object
		public function LoadFile($filename, $ob = false)
		{
			if(!file_exists($filename))
				return false;
				

				
			if($ob)
			{
		        ob_start();
		        
		        require_once($filename);
		        
		        $content = ob_get_contents();
		        
		        ob_end_clean();
				
				return $content;
			}
			else 
			{
				return require_once($filename);
			}
		}
		
		public function SaveFile($filename, $content, $type = 'w')
		{
			if(!is_writeable($filename))
			{
				
				//chmod($filename, 0777);
				
				//if(!is_writeable($filename))
				//	throw new Exception(printf($this->Lang['file_no_write'], $filename), 87);
			}
			
			$fp = fopen($filename, $type);
			
			fwrite($fp, $content);
			
			fclose($fp);
			
			chmod($filename, 0777);
		}
		
		public function DeleteFile($filename)
		{
			return unlink($filename);
		}
		
		//--------------------------------
		// Magic Methods
		//--------------------------------

		function __clone()
		{
			try
			{
				foreach($this as $offset)
					if(is_object($offset))
						$this->$offset = clone($this->$offset);
			}
			catch(Exception $e)
			{
				throw new Exception("Cloning failed.", 84);
			}
		}
	}

?>