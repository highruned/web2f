<?php

	define("MODULE_NO_CONFLICT", false);

	class ModuleManager
	{
		protected $Modules;
		protected $Controllers;
		
		protected $Data;
		
		protected $Prefixes;
		protected $ReservedMethods;

		public function __construct($cb = "_callbefore", $ca = "_callafter", $c = "_call")
		{
			$this->Modules = array();
			$this->Modules['loaded'] = array();
			
			$this->Controllers = array();

			$this->Data = array();
			
			$this->Prefixes = array('cb' => $cb, 'ca' => $ca, 'c' => $c);
			
			$this->ReservedMethods = array("Setup", "Options");
		}
		
		public function AddModule(Module &$module)
		{
			if(!$this->FindModule($module))
			{
				$mod = $this->SetupModule($module);
				$this->Modules[] = $mod;
				
				if(array_key_exists("initialize", $mod[$this->Prefixes['c']]))
					$mod[0]->Initialize();
			}
			else 
				return false;
		}
		
		public function SetupModule(Module &$module)
		{
			$module->SetParent($this);
			
			$class = get_class($module);
			
			$callbefore = array();
			$callafter = array();
			$call = array();
			
			$methods = get_class_methods($class);

			foreach($methods as $method)
			{
				// check this method name isn't reserved for priority features
				if(!in_array($method, $this->ReservedMethods))
				{
					if(substr($method, strlen($this->Prefixes['cb']), strlen($method)) === $this->Prefixes['cb'])
						$callbefore[strtolower(substr($method, 0, strlen($this->Prefixes['cb'])))] = $method;
					else if(substr($method, strlen($this->Prefixes['ca']), strlen($method)) === $this->Prefixes['ca'])
						$callafter[strtolower(substr($method, 0, strlen($this->Prefixes['ca'])))] = $method;
					else if(substr($method, 0, 2) === "__")
						$call[strtolower(substr($method, 2, strlen($method)))] = $method;
					else 
						$call[strtolower($method)] = $method;
				}
			}
			
			foreach(get_class_vars($class) as $offset => $value)
			{
				$offset_lc = strtolower($offset);
				
				$this->Data[$offset_lc] = &$module->$offset;
			}

			foreach($module->Data as $offset => &$value)
			{
				$offset_lc = strtolower($offset);
				
				if(array_key_exists($offset_lc, $this->Data))
					unset($this->Data[$offset_lc]);
					
				$this->Data[$offset_lc] = &$value;
			}
			
			return array(&$module, "name" => $class, $this->Prefixes['cb'] => $callbefore, $this->Prefixes['ca'] => $callafter, $this->Prefixes['c'] => $call);
		}
		
		public function RemoveModule(Module &$module)
		{
			foreach($this->Modules as $offset => &$value)
			{
				if($value[0] === $module)
				{
					array_splice($this->Modules, $offset, 1);
					
					return true;
				}
			}
			
			return false;
		}
		
		public function &FindModule(&$module)
		{
			if(is_string($module))
			{
				foreach($this->Modules as &$value)
					if($module === get_class($value[0]))
						return $value[0];
			}
			else
			{
				foreach($this->Modules as &$value)
					if($module === $value[0])
						return true;
			}
			
			return false;
		}
		
		public function LoadModule($module)
		{
			if(!array_key_exists($module, $this->Modules['loaded']))
			{
				$path = PATH . "/modules/" . $module . "/" . $module . ".php";
				
				if(file_exists($path))
				{
					$this->Modules['loaded'][$module] = true;
					
					$this->LoadFile($path, SAVE_BUFFER);
					
					return true;
				}
				else 
				{
					return false;
				}
			}
			else 
			{
				return true;
			}
		}
		
		public function AddController(Module &$module, $overload = true)
		{
			$controller = $this->SetupModule($module, $overload);

			$this->Modules[] = $controller;
			
			// retrieve argument list
			$arguments = func_get_args();
			
			// remove the module from the list
			array_shift($arguments);

			// loop through arguments
			foreach($arguments as $value)
			{
				// initialize controller for this index
				$this->Controllers[$value][] = $controller;
			}
		}
		
		public function &FindController($controller, $method)
		{
			$method = strtolower($method);
			
			// reverse modules, to simulate overloading
			for($i = count($this->Controllers[$controller]) - 1; $i >= 0; --$i)
			{
				$module = &$this->Controllers[$controller][$i];
				
				if(array_key_exists($method, $module[$this->Prefixes['c']]))
					return $module;
			}
			
			return false;
		}
		
		public function CallController(&$module, $method, $arguments = null)
		{
			$method = strtolower($method);
			
			if(is_string($module))
				$module = $this->FindController($module, $method);

			return ($arguments !== null) && (count($arguments) > 0) ? call_user_func_array(array(&$module[0], $module[$this->Prefixes['c']][$method]), $arguments) : $module[0]->$module[$this->Prefixes['c']][$method]();
		}
		
		//--------------------------------
		// Magic methods
		//--------------------------------
		
		public function __call($method, array $arguments)
		{
			// the number of arguments
			$num = count($arguments);
			
			if(method_exists($this, $method))
				// if there's no arguments, there's no need for call_user_func()
				return $num > 0 ? call_user_func_array(array(&$this, $method), $arguments) : $this->$method();
			
			$method = strtolower($method);
			
			// only call this method once, as all other methods are overloaded
			$overlord = false;

			// reverse modules, to simulate overloading
			for($i = count($this->Modules) - 1; $i >= 0; --$i)
			{
				$module = &$this->Modules[$i];
				
				// call before functions for this method
				if(array_key_exists($method, $module[$this->Prefixes['cb']]))
					$module[0]->$module[$this->Prefixes['cb']][$method]();

				// if this method hasn't been called yet
				if(!$overlord)
					if(array_key_exists($method, $module[$this->Prefixes['c']]))
						$overlord = &$module;
			}

			$method = $overlord[$this->Prefixes['c']][$method];
			$module = &$overlord[0];
			
			if(is_string($method))
			// if there's no arguments, there's no need for call_user_func()
				return $num > 0 ? call_user_func_array(array(&$module, $method), $arguments) : $module->$method();
			else 
				throw new Exception(printf($this->Lang['undefined_method'], $method), 91);
		}
		
		public function &FindMethod($method)
		{
			if(method_exists($this, $method))
				// if there's no arguments, there's no need for call_user_func()
				return $this;
			
			$method = strtolower($method);
			
			// only call this method once, as all other methods are overloaded
			$overlord = false;

			// reverse modules, to simulate overloading
			for($i = count($this->Modules) - 1; $i >= 0; --$i)
			{
				$module = &$this->Modules[$i];
				
				// if this method hasn't been called yet
				if(!$overlord)
					if(array_key_exists($method, $module[$this->Prefixes['c']]))
						$overlord = &$module;
			}

			$method = $overlord[$this->Prefixes['c']][$method];
			$module = &$overlord[0];
			
			if(is_string($method))
			// if there's no arguments, there's no need for call_user_func()
				return $module;
			else 
				return false;
		}
		
		public function &__get($offset)
		{
			$offset_lc = strtolower($offset);
			// use our own class if the variable exists
			//if(isset($this->$offset)) return $this->$offset;
			// fix for creating arrays with nonexistant vars
			if(array_key_exists($offset_lc, $this->Data))
				$value = &$this->Data[$offset_lc];
			else 
				$value = null;
			
			return $value;
		}
		
		public function __set($offset, $value)
		{
			// use our own class if the variable exists
			//if(isset($this->$offset)) return ($this->$offset = $value);
			
			$offset_lc = strtolower($offset);
			
			return ($this->Data[$offset_lc] = $value);
		}
		
		public function __isset($offset)
		{
			$offset_lc = strtolower($offset);
			
			return isset($this->Data[$offset_lc]);
		}
		
		public function __unset($offset)
		{
			$offset_lc = strtolower($offset);
			
			if(isset($this->Data[$offset_lc]))
				unset($this->Data[$offset_lc]);
		}
	}
	
?>