<?php

	class Session extends Registry
	{
		public function __construct()
		{
			// must come before registry construction
			session_start();
			session_regenerate_id();

			// initialize registry
			parent::__construct($_SESSION);

			// session exists
			if($this['generated']) 
			{
				// session expired (x minutes) - start from scratch
				if((time() - $this['generated']) > 30 * 60)
				{
					unset($this['username']);
					unset($this['password']);
					
					$this['status'] = 2;
				}
			}
			else 
			{
				// if no session is found
				if($this['status'] === false)
					$this['status'] = 6;
			}
			
			$this['status_old'] = $this['status'];
			
			// reset session timeout
			$this['generated'] = time();
		}
		
		public function __destruct()
		{
			$this->Close();
		}
		
		public function Logout()
		{
			unset($this['username']);
			unset($this['password']);
			
			$this['status'] = 5;
			
			$this->Close();
		}
		
		public function Save()
		{
			session_write_close(); // save the changes
		}
		
		public function Close()
		{
			foreach($this->Data as $offset => $value)
				$_SESSION[$offset] = $value;
			
			session_write_close(); // save the changes
		}
		
		public function Destroy()
		{
			session_destroy(); // wipe the data
		}
	}

?>