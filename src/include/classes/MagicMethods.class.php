<?php

	class MagicMethods
	{
		public $Data;

		protected function __construct(&$data = array())
		{
			if(is_array($data))
				$this->Data = &$data;
			else
				$this->Data = array();
				
			foreach($data as $offset => $value)
				if(!isset($value) || $value === '' || $value === ' ')
					unset($data[$offset]);
		}

		public function Merge(&$data)
		{
			foreach($data as $offset => &$value)
				if(isset($value) && $value !== '' && $value !== ' ')
					$this->Data[$offset] = &$value;
		}
		
		public function Reset()
		{
			foreach($this->Data as $offset => &$value)
				unset($this->Data[$offset]);
		}
		
		public function &GetData()
		{
			return $this->Data;
		}

		//--------------------------------
		// Magic methods
		//--------------------------------

		public function &__get($offset)
		{
			$offset_lc = strtolower($offset);
			
			if(array_key_exists($offset, $this->Data))
				$value = &$this->Data[$offset];
			// check if lower case exists instead
			else if(array_key_exists($offset_lc, $this->Data))
				$value = &$this->Data[$offset_lc];
			else
				$value = false;

				return $value;
		}

		public function __set($offset, $value)
		{
			$offset_lc = strtolower($offset);
			
			if($value !== null)
			{
				if(array_key_exists($offset, $this->Data))
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
			return isset($this->Data[$offset]);
		}
		
		public function __unset($offset)
		{
			if(isset($this->Data[$offset]))
				unset($this->Data[$offset]);
		}
		
		public function __toString()
		{
			throw new Exception(printf(G::$Engine->Lang['class_tostring'], get_class($this)), 95);
		}
	}

?>