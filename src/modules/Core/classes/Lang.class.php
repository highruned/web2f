<?php

	class Lang extends Registry
	{
		public function __construct()
		{
			// initialize registry
			parent::__construct();
		}
		
		//--------------------------------
		// Magic methods
		//--------------------------------

		public function &__get($offset)
		{
			$langs = &G::$Engine->Languages->Data;
			
			$value = false;
			
			// reverse langs, to simulate overloading
			//for($i = count($langs) - 1; $i >= 0; --$i)
			foreach($langs as $lang)
			{
				//$lang = &$langs[$i]->Data;

				if(array_key_exists($offset, $lang->Data))
					$value = &$lang->Data[$offset];
			}
			
			return $value;
		}
	}

?>