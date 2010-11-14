<?php

	function fix_files_superglobal()
	{
		function rearrange($group)
		{
			foreach($group as $property => $arr)
			{
				foreach($arr as $item => $value)
				{
					$result[$item][$property] = $value;
				}
			}
			
			return $result;
		}
		
		$_FILES = array_map("rearrange", $_FILES);
	}
	
	function object2array($object)
	{
		$array = array();
		
		if(is_object($object))
		{
			if($var = get_object_vars($object))
				foreach($var as $key => $value)
					$array[$key] = ($key && !$value) ? strval($value) : object2array($value);
		}
		else if(is_array($object))
		{
			foreach($object as $key => $value)
				$array[$key] = object2array($value);
		}
		else
			$array = strval($object); // strval and everything is fine
		
		return $array;
	}
	
	//--------------------------------
	// EZ Functions
	//--------------------------------
	
	function redirect($url, $delay = 0, $redirect = true)
	{
		// if our CMS is defined
		if(isset(G::$Engine))
		{
			// if we want a redirection setup
			if($redirect)
				// setup redirection to the current url
				G::$Engine->Session['redirect'] = G::$Engine->Page->RequestURL;
	
			// save and close the session
			G::$Engine->Session->Close();
		}
		
		// custom delay
		sleep($delay);
		
		// delay for saving since we're redirecting
		usleep(1000000); // 1s
		
		// exit the script with a redirect
		exit(header("Location: {$url}"));
	}
	
	function refresh($url, $delay = 0, $exit = true)
	{
		if($exit)
			exit("<meta http-equiv=\"Refresh\" content=\"{$delay};url={$url}\">");
		else
			return "<meta http-equiv=\"Refresh\" content=\"{$delay};url={$url}\">";
	}

	function get_file($filename)
	{
		if(file_exists($filename))
		{
			$file = fopen($filename, 'r');
			
			$content = fread($file, filesize($filename));
			
			fclose($file);
			
			return $content;
		}
		else 
		{
			return false;
		}
	}
	
	function encode($data)
	{
		return base64_encode(serialize($data));
	}
	
	function decode($data)
	{
		return unserialize(base64_decode($data));
	}
	
	// remove extra slashes, and remove all slashes from the end of the path/url
	function fix_path($path)
	{
		return str_replace(':/', '://', str_replace('\\', '/', preg_replace("@([/]+)$@", '', preg_replace("@([/]+)@", '/', $path))));
	}
	
	// remove extra commas
	function fix_query($query)
	{
		return preg_replace("@([,]+)@", ',', $query);
	}
	
	//--------------------------------
	// File/Folder Functions
	//--------------------------------
	
	function list_children_folders($path)
	{
		$files = array();
		
		if(is_dir($path) && is_readable($path))
		{
			if($handle = opendir($path))
			{
				while(($name = readdir($handle)) !== false)
				{
					if(!preg_match("#^\.#", $name))
					if(is_dir($path . "/" . $name))
					{
						$files[] = $name;
					}
				}
			
				closedir($handle);
			}
		}

		return $files;
	}

	function list_children_files($path)
	{
		$files = array();
		
		if(is_dir($path) && is_readable($path))
		{
			if($handle = opendir($path))
			{
				while(($name = readdir($handle)) !== false)
				{
					if(!preg_match("#^\.#", $name))
					if(is_file($path . "/" . $name))
					{
						$files[] = $name;
					}
				}
			    
				closedir($handle);
			}
		}

		return $files;
	}
	
	function list_files_folders($path)
	{
		$files = array();
		
		if(is_dir($path) && is_readable($path))
		{
			if($handle = opendir($path))
			{
				while(($name = readdir($handle)) !== false)
				{
					if(!preg_match("#^\.#", $name))
					if(is_dir($path . "/" . $name))
					{
						$files[$name] = list_files_folders($path . "/" . $name);
					}
					else if(is_file($path . "/" . $name))
					{
						$files[] = $name;
					}
				}
			    
				closedir($handle);
			}
		}

		return $files;
	}
	
	function object()
	{
		$o = (object) NULL;
		$n = func_num_args();
		
		for($i = 0; $i < $n; $i += 2)
		{
			$o->{func_get_arg($i)} = func_get_arg(++$i);
		}
		
		return $o;
	}
	
	function enum()
	{
		$i = 0;
		$args = func_get_args();
		
		if(is_array($args))
		{
			foreach($args as $constant)
			{
				define($constant, ++$i);
			}
		}
	}
	
	function preg_replace_array(mixed $pattern, mixed $replacement, mixed &$subject)
	{
		$arr = array();
		
		foreach($subject as $offset => $value)
		{
			$var = preg_replace($pattern, $replacement, $offset);
			
			$arr[$var] = $value;
		}
		
		return $arr;
	}
	
	if(!function_exists("stripslashes_deep"))
	{
	    function stripslashes_deep($value)
	    {
	        $value = is_array($value) ?
	                    array_map('stripslashes_deep', $value) :
	                    stripslashes($value);
	
	        return $value;
	    }
	}
	
?>