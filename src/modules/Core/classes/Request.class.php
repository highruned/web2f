<?php

	class Request extends Registry
	{
		public function __construct()
		{
			if(get_magic_quotes_gpc())
			    $_REQUEST = array_map("stripslashes_deep", $_REQUEST);

			// initialize registry
			parent::__construct($_REQUEST);
		}
		
		public function __destruct()
		{

		}
		
		public function ValidateSQL($args)
		{
			$args = is_array($args) ? $args : func_get_args();
			
			foreach($args as $var)
			{
				if(eregi("^([A-Za-z0-9._-])*$", $this[$var]) === false)
				{
					unset($this[$var]);
					
					return false;
				}
			}
			
			return true;
		}
		
		public function ValidateEmail($args)
		{
			$args = is_array($args) ? $args : func_get_args();
			
			foreach($args as $var)
			{
				if(!preg_match("/^[^0-9][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[@][a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*[.][a-zA-Z]{2,4}$/", $this[$var]))
				{
					unset($this[$var]);
					
					return false;
				}
			}
			
			return true;
		}
		
		public function ValidateZip($args)
		{
			$args = is_array($args) ? $args : func_get_args();
			
			foreach($args as $var)
			{
				if(!preg_match("/^[0-9]{5,5}([- ]?[0-9]{4,4})?$/", $this[$var]))
				{
					unset($this[$var]);
					
					return false;
				}
			}
			
			return true;
		}
		
		public function ValidateIP($args)
		{
			$args = is_array($args) ? $args : func_get_args();
			
			foreach($args as $var)
			{
				if(!preg_match("/^[0-9]{5,5}([- ]?[0-9]{4,4})?$/", $this[$var]))
				{
					unset($this[$var]);
					
					return false;
				}
			}
			
			return true;
		}
		
		public function ValidatePhone($args)
		{
			$args = is_array($args) ? $args : func_get_args();
			
			foreach($args as $var)
			{
				if(!preg_match("/^(\(?[2-9]{1}[0-9]{2}\)?|[0-9]{3,3}[-. ]?)[ ][0-9]{3,3}[-. ]?[0-9]{4,4}$/", $this[$var]))
				{
					unset($this[$var]);
					
					return false;
				}
			}
			
			return true;
		}
	}

?>