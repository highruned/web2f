<?php
	
	class DB
	{
		private $Settings;
		
		public $Prefix;
		
		public function __construct()
		{
			$this->Settings = array();
		}
		
		public function Close()
		{
			foreach($this->Settings as &$type)
				foreach($type as &$DB)
					if(is_resource($DB['link']))
						mysql_close($DB['link']);
		}
		
		public function Add($type, $name, $data)
		{
			if(!array_key_exists($type, $this->Settings))
				$this->Settings[$type] = array();
			
			$this->Settings[$type][$name] = $data;
			
			$this->SetActive($type, $name);
		}
		
		public function Remove($type)
		{
			if(isset($this->Settings[$type]))
				unset($this->Settings[$type]);
		}
		
		public function SetActive($type, $name)
		{
			$this->Settings[$type]['active'] = &$this->Settings[$type][$name];

			// update the active db's table prefix
			$this->Prefix = $this->Settings[$type][$name]['prefix'];
		}
		
		public function FetchRows($query, $type = "master", $num = MYSQL_ASSOC)
		{
			$DB = $this->Connect($type);
			
	  		$rows = array();
	  		
	  		if($result = mysql_query($query, $DB['link']) or die(mysql_error()))
	  		{
				while($row = mysql_fetch_array($result, $num))
				{
					$rows[] = $row;
				}
	  		}
	  		
	  		mysql_free_result($result);
	  		
	  		return $rows;
		}
		
		public function FetchRow($query, $type = "master", $num = MYSQL_ASSOC)
		{
			$DB = $this->Connect($type);
			
			$result = mysql_query($query, $DB['link']) or die(mysql_error());
			
	  		$row = mysql_fetch_array($result, $num);
	  		
	  		mysql_free_result($result);
	    	
	  		return $row;
		}
		
		public function FetchArray($result, $type = "master", $num = MYSQL_ASSOC)
		{
			$DB = $this->Connect($type);
			
	  		$row = mysql_fetch_array($result, $num);
	    	
	  		return $row;
		}
		
		public function Query($query, $type = "master", $errors = true)
		{
			$DB = $this->Connect($type);
			
			if($errors)
				$result = mysql_query($query, $DB['link']) or die(mysql_error());
			else 
				$result = @mysql_query($query, $DB['link']);
			
			return $result;
		}
		
		public function Affected($type = "master")
		{
			$DB = $this->Connect($type);
			
			return mysql_affected_rows($DB['link']);
		}
		
		public function Connect($type = "master")
		{
			// grab the current "active" DB for this type
			$DB = &$this->Settings[$type]['active'];
			
			// check if the DB is a resource and we're able to connect
			if($this->Active($DB))
			{
				return $DB;
			}
			else
			{
				// if the DB is still a resource, close it to before overwriting it with a new connection
				if(is_resource($DB['link']))
					mysql_close($DB['link']);
				
				// attempt connecting to the DB currently set to active
				$DB['link'] = @mysql_pconnect($DB['host'], $DB['user'], $DB['pass']);
				
				if($DB['link'])
				{
					mysql_select_db($DB['db'], $DB['link']) or die("Cannot select: " . mysql_error());
					
					return $DB;
				}
				else
				{
					// loop through every DB in the settings for a connection
					foreach($this->Settings[$type] as $name => &$DB)
					{
						$DB['link'] = @mysql_pconnect($DB['host'], $DB['user'], $DB['pass']);
						
						if($DB['link'])
						{
							mysql_select_db($DB['db'], $DB['link']) or die("Cannot select: " . mysql_error());
							
							// set the active DB for this type to the newly connected DB
							$this->SetActive($type, $name);
							
							return $DB;
						}
						else
						{
							// if the DB is still a resource, close it to avoid too many connection errors
							if(is_resource($DB['link']))
								mysql_close($DB['link']);
						}
						
						sleep(5);
					}
				}
			}
			
			// check if the DB is a resource and we're able to connect
			if($this->Active($DB))
				return $DB;
			else 
				// serious error 1) Incorrect database information. 2) Server down. 3) Too many connections, try flushing MySQL.
				throw new Exception("Could not connect to any database. Type: {$type}", 69);
		}
		
		public function Active(&$DB)
		{
			if(!is_resource($DB['link']) || !mysql_ping($DB['link']))
				return false;
			else
				return true;
		}
	}
	
?>