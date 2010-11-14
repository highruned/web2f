<?php

	class Database extends Module
	{
		public function __construct($DB)
		{
			$this->DB = new DB();
			
			//--------------------------------
			// Initialize database
			//--------------------------------
			
			foreach($DB as $type => $value)
				foreach($value as $name => $info)
					$this->DB->Add($type, $name, $info);
		}
	}
	
?>