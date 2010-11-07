<?php

	class Section extends Registry
	{
		public $Path;
		public $Title;
		
		public function __construct($title)
		{
			// construct registry
			parent::__construct();
			
			$this->Title = $title;
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
					else if(get_class($value) === "Item")
					{
						$value->Path = $offset_lc;
						
						return ($this->Data[$offset_lc] = &$value);
					}
				}
			}
			else 
				throw new Exception(G::$Engine->Lang['null_value'], 85);
		}
	}
	
?>