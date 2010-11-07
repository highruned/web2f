<?php

	class Page extends Registry
	{
		public function __construct($settings)
		{
			//--------------------------------
			// Initialize settings
			//--------------------------------

			//$settings['requesturl'] = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$settings['requesturl'] = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			$settings['title'] = array($settings['title']);
			
			//--------------------------------
			// Initialize header settings
			//--------------------------------
			
			$settings['headers'] = array();
			$settings['headers']['Expires'] = "Sun, 19 nov 1978 05:00:00 GMT";
			$settings['headers']['Last-Modified'] = gmdate("D, d M Y H:i:s") . " GMT";
			$settings['headers']['Cache-Control'] = "no-store, no-cache, must-revalidate, post-check=0, pre-check=0";
			$settings['headers']['Pragma'] = "no-cache";
			
			//--------------------------------
			// Save settings
			//--------------------------------

			// construct registry
			parent::__construct($settings);
		}
		
		public function Create($page)
		{
			$this->Buffer = '';
			
			$page = fix_path($page);
			
			if(!$data = G::$Engine->DB->FetchRow("SELECT * FROM `" . G::$Engine->DB->Prefix . "pages` WHERE `page_path` = '{$page}' OR `page_path` = '/{$page}' OR `page_path` = '{$page}/' OR `page_path` = '/{$page}/' LIMIT 1", "slave"))
				return false;
			
			// if the page is disabled, load the disabled page
			if($data['state'] === "private")
				$data = G::$Engine->DB->FetchRow("SELECT * FROM `" . G::$Engine->DB->Prefix . "pages` WHERE `page_path` = 'disabled' OR `page_path` = '/disabled'  OR `page_path` = 'disabled/' OR `page_path` = '/disabled/' LIMIT 1", "slave");

			//--------------------------------
			// Lets make this easier to manage
			//--------------------------------
			$data = preg_replace_array("/^page_/i", '', $data);

			// add to our title history
			$data['title'] = isset($data['name']) && $data['name'] != '' ? array($data['name']) : isset($data['title']) && $data['title'] != '' ? array_merge($this['title'], array($data['title'])) : $this['title'];

			$this->Merge($data);

			// set mime-type header
			$this->Headers['Content-Type'] = $this['mime_type'];

			return true;
		}
		
		public function Input($input)
		{
			$this->Buffer .= $input;
		}

		public function Output()
		{
			$buffer = $this->Buffer;
			
			$this->Buffer = '';
			
			return $buffer;
		}
		
		public function SendHeaders()
		{
			//die(var_dump($this->Headers));
			foreach($this->Headers as $offset => $value)
			{
				if(is_string($offset))
					header($offset . ':' . $value, false);
				else if(is_numeric($offset))
					header($value, false);
			}
		}
		
		public function GZip()
		{
			// the browser can handle gzip
			if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip"))
			{
				ini_set("zlib.output_compression", "Off");

				$this->Page->Headers['Content-Encoding'] = "gzip";
				
				$this->Content = gzencode($this->Content, 9, FORCE_GZIP);
			}
			// the browser cant handle gzip
			else
			{
				
			}

			$this->Page->Headers['Content-Length'] = strlen($this->Content);
		}
		
		public function __toString()
		{
			return $this->Buffer;
		}
		
		public function ChangeTheme($theme)
		{
			$path = G::$Engine->Site->Path . "/themes/" . $theme . "/";

			if(is_dir($path))
			{
				$this->Theme = new Theme($theme);
			}
		}
	}

?>