<?php

//===========================================================================
// INITIALIZATION
//===========================================================================

	define("SAVE_BUFFER", true);

	//--------------------------------
	// Autoload required classes
	//--------------------------------

	function __autoload($class)
	{
		if(!class_exists($class))
		{
			$path = PATH . "/include/classes/" . $class . ".class.php";
			
			if(file_exists($path))
				require_once($path);
			else if(class_exists("Engine"))
			{
				$self = &G::$Engine;
				
				if($path = G::$Engine->FindClass($class))
					if(file_exists($path))
						require_once($path);
				else 
					throw new Exception("Could not find the '{$class}' class.", 83);
			}
			else 
				throw new Exception("Could not find the '{$class}' class.", 82);
		}
	}

	//--------------------------------
	// Include library functions
	//--------------------------------
	
	require_once(PATH . "/include/library.php");
	
	//--------------------------------
	// Global class definition
	//--------------------------------

	final class G
	{
		public static $Engine;
	}

	//--------------------------------
	// Initialize super-class
	//--------------------------------

	G::$Engine = new Engine();
	
	//--------------------------------
	// Include database info
	//--------------------------------
	
	require_once(PATH . "/include/config.php");
	
	//--------------------------------
	// First load database module
	//--------------------------------
	
	G::$Engine->LoadModule("Database");
	
	G::$Engine->AddModule(new Database($DB));
	
	//--------------------------------
	// Load & setup rewrites
	//--------------------------------
	

	//die(G::$Engine->Request['page']);
	//--------------------------------
	// Load modules
	//--------------------------------

	G::$Engine->LoadModules();
	
?>