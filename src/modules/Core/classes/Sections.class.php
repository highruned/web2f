<?php

	class Sections extends Registry
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
					else if(get_class($value) === "Section")
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