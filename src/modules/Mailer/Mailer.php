<?php

	class Mailer extends Module
	{
		public function __construct()
		{
			$this->Mail = new Mail();
		}
	}

	$this->AddModule(new Mailer());
	if($this->Request['page'] === "admin/manage/modules/configure" && $this->Request['name'] === "Mailer")
	{
		switch($this->Request['act'])
		{
			case "templates":
				$this->Main("admin", $this->Site->Path . "/modules/Mailer/view_templates.php");
			break;
			
			case "templates/create":
				$this->Main("admin", $this->Site->Path . "/modules/Mailer/create_template.php");
			break;
		}
	}
	
	if($this->Request['page'] === "admin/manage/modules/configure/Mailer/templates/edit")
		$this->Main("admin", $this->Site->Path . "/modules/Mailer/edit_template.php");
?>