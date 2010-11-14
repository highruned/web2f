<?php

	//--------------------------------
	// Add master database/s
	//--------------------------------
	
	$DB['master']['main'] = array(
	"host" => "localhost",
	"db" => "cms",
	"user" => "root",
	"pass" => "",
	"prefix" => "cms_");

	//--------------------------------
	// Add slave database/s
	//--------------------------------
	
	$DB['slave']['main'] = array(
	"host" => "localhost",
	"db" => "cms",
	"user" => "root",
	"pass" => "",
	"prefix" => "cms_");
	
?>
