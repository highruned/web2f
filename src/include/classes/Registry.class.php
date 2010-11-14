<?php

	class Registry extends MagicMethods implements ArrayAccess
	{
		protected function __construct(&$vars = array())
		{
			parent::__construct($vars);
		}
		
		public function Validate($args)
		{
			$args = is_array($args) ? $args : func_get_args();
			
			foreach($args as $offset)
			{
				if(!$this[$offset] || $this[$offset] === '' || $this[$offset] === ' ')
				{
					unset($this[$offset]);
					
					return false;
				}
			}
			
			return true;
		}
		
		//--------------------------------
		// ArrayAccess methods
		//--------------------------------

		public function offsetExists($offset)
		{
			return isset($this->Data[$offset]);
		}
		
		public function offsetGet($offset)
		{
			return $this->__get($offset);
		}
		
		public function offsetSet($offset, $value)
		{
			$this->__set($offset, $value);
		}
		
		public function offsetUnset($offset)
		{
			unset($this->Data[$offset]);
		}
	}

?>