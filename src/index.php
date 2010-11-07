<?php

	//--------------------------------
	// Definitions
	//--------------------------------

	define("PATH", dirname(__FILE__));

	//--------------------------------
	// Start timer
	//--------------------------------
	
	//require_once(PATH . "/include/classes/Timer.class.php");
	
	//$timer = new Timer();
	
	//--------------------------------
	// Initialize
	//--------------------------------

	require_once(PATH . "/include/init.php");

//===========================================================================
// MAIN PROGRAM
//===========================================================================
	
	//--------------------------------
	// Process requests
	//--------------------------------
	
	if(G::$Engine->FindMethod("Main"))
		G::$Engine->Main(G::$Engine->Request['page']);
	else 
		die("");
	//--------------------------------
	// Stop timer
	//--------------------------------
	
	//echo "<br /><br />Page generated in " . $timer->Stop(10) . " seconds.";

?>
