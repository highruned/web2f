<?php

	class Module
	{
		protected $Parent;
		
		public $Data = array();
		
		public function __construct()
		{
			G::$Engine->AddModule($this);
		}
		
		public function SetParent(ModuleManager &$parent)
		{
			$this->Parent = &$parent;
		}
		
		//--------------------------------
		// Magic methods / redirect to parent manager
		//--------------------------------
		
		public function &__call($method, $arguments)
		{
			if(isset($this->Parent))
				return $this->Parent->__call($method, $arguments);
			else 
				return false;
		}
		
		public function &__get($offset)
		{
			if(isset($this->Parent))
				$value = &$this->Parent->__get($offset);
			else 
				if(array_key_exists($offset, $this->Data)) 
					$value = &$this->Data[$offset];
				else
					$value = null;
		
			return $value;
		}
		
		public function __set($offset, $value)
		{
			
			if(isset($this->Parent))
				return $this->Parent->__set($offset, $value);
			else 
				return ($this->Data[$offset] = $value);
		}
		
		public function __isset($offset)
		{
			if(isset($this->Parent))
				return $this->Parent->__isset($offset);
			else
				return isset($this->Data[$offset]);
		}
		
		public function __unset($offset)
		{
			if(isset($this->Parent))
				$this->Parent->__unset($offset);
			else
				unset($this->Data[$offset]);
		}
	}
	
?>