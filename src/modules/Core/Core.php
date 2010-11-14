<?php

	class Core extends Module
	{
		public function __construct($DB)
		{
			parent::__construct();
			
			//--------------------------------
			// Create primary classes
			//--------------------------------
			
			$this->Handler = new Handler();
			
			set_error_handler(array($this->Handler, "Error"));
			set_exception_handler(array($this->Handler, "Exception"));
			
			$this->Request = new Request();
			$this->Session = new Session();
			$this->Languages = new Languages();
			$this->Macros = new Macros();
			$this->TemplateEngine = new TemplateEngine();
			$this->Lang = new Lang();
			$this->Sections = new Sections();

			//--------------------------------
			// Load rewrites
			//--------------------------------
			
			$query = 
				"SELECT * 
				FROM `" . $this->DB->Prefix . "rewrites`";

			$this->Rewrites = $this->DB->FetchRows($query, "slave");
			
			//--------------------------------
			// Load subclass settings
			//--------------------------------

			$settings = &G::$Engine->LoadSetting("site");
			
			$settings['path'] = PATH;
			
			// figure out the protocol type
			$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https://" : "http://";
			
			// figure out the server path
			$server = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
			
			// set the cms url
			$settings['url'] = fix_path($protocol .  $server . "/" . str_replace(realpath($_SERVER['DOCUMENT_ROOT']), "", $settings['path']));

			if(!$settings['copyright'])
				$settings['copyright'] = "All Rights Reserved © " . date("Y", time()) . " " . $settings['title'];
			
			// if a default theme doesn't exist
			if(!$settings['theme_name'] || !is_dir($settings['path'] . "/themes/" . $settings['theme_name']))
				$settings['theme_name'] = "default";
				
			if(!$settings['language'])
				$settings['language'] = "en";
			
			if(!$settings['charset'])
				$settings['charset'] = "utf-8";
				
			if(!$settings['mime_type'])
				$settings['mime_type'] = "text/html; charset=" . $settings['charset']; //iso-8859-1
			
			if(!$settings['language'])
				$settings['language'] = "en";
			
			//--------------------------------
			// Setup subclasses
			//--------------------------------

			$this->Site = new Site($settings);
			$this->Site->HTTPS = false;
			
			$this->Page = new Page($settings);
			$this->Page->HTTPS = false;
			
			//--------------------------------
			// Check HTTPS
			//--------------------------------
			
			if($settings['https_enabled'] === "1")
				$this->Rewrites[] = array("rewrite_rule" => "^(.+)$", "rewrite_replacement" => "$1", "rewrite_https" => "1");

			//--------------------------------
			// Validate visitor
			//--------------------------------

			$this->User = new User();
			$this->User->Validated = false;
			
			if($this->Session->Validate("username", "password"))
			{
				$query =
					"SELECT * FROM `{$this->DB->Prefix}users` 
					WHERE `user_username` = '{$this->Session['username']}' 
					AND `user_password` = '{$this->Session['password']}'
					LIMIT 1";
				
				// username/password is correct
				if($data = $this->DB->FetchRow($query, "slave"))
				{
					//--------------------------------
					// Lets make this easier to manage
					//--------------------------------

					$user = preg_replace_array("/^user_/i", '', $data);
					
					$this->User->Merge($user);

					$this->User->Validated = true;
				}
				// username/password is incorrect
				else
				{
					// remove session vars
					foreach($args as $value)
						unset($this->Session[$value]);
				}
			}
			
			if($this->User['group_id'])
				$group = $this->User['group_id'];
			else 
				$group = $this->Page['group_id'];
			
			$query = 
				"SELECT * FROM `{$this->DB->Prefix}groups` 
				WHERE `group_id` = '{$group}'
				LIMIT 1";
			
			if($data = $this->DB->FetchRow($query, "slave"))
			{
				$group = preg_replace_array("/^group_/i", '', $data);
		
				$this->User->Group->Merge($group);
			}
		}
		
		public function __DirectoryListing()
		{
$_POST['dir'] = urldecode($_POST['dir']);

if( file_exists(PATH . $_POST['dir']) ) {
	$files = scandir(PATH . $_POST['dir']);
	natcasesort($files);
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
		// All dirs
		foreach( $files as $file ) {
			if( file_exists(PATH . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir(PATH . $_POST['dir'] . $file) ) {
				echo "<li class=\"dropDown directory collapsed\"><a style=\"position: relative\" class=\"dropDown\" href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\"><span>" . htmlentities($file) . "</span><span style=\"position: absolute; right: 0px;\"></span></a></li>";
			}
		}
		// All files
		foreach( $files as $file ) {
			if( file_exists(PATH . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir(PATH . $_POST['dir'] . $file) ) {
				$ext = preg_replace('/^.*\./', '', $file);
				echo "<li class=\"dropDown file ext_$ext\"><a style=\"position: relative\" class=\"dropDown\" href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\"><span>" . htmlentities($file) . "</span><span style=\"position: absolute; right: 0px;\"></span></a></li>";
			}
		}
		echo "</ul>";	
	}
}

die();
		}
		
		//--------------------------------
		// EZ Functions
		//--------------------------------
		
		public function __Redirect($url, $delay = 0, $redirect = false)
		{
			// if we want a redirection setup
			if($redirect)
				// setup redirection to the current url
				$this->Session['redirect'] = $this->Page->RequestURL;
	
			// save and close the session
			$this->Session->Close();
			
			// custom delay
			sleep($delay);
			
			// delay for saving since we're redirecting
			usleep(1000000); // 1s

			// exit the script with a redirect
			exit(header("Location: " . $url));
		}
		
		public function __AddSection($section)
		{
			$this->Sections[$section->Path] = $section;
		}
		
		public function __ApplyRewrites()
		{
			$page = rawurldecode($this->Request['page']);
			
			$this->Page->FullURL = fix_path($this->Site->URL . '/' . $page);

			$page = $this->RewriteHandler($page);

			// decode and remove extra slashes at end of request
			$this->Request['page'] = preg_replace("#[/]+$#", '', $page);
		}
		
		public function __RewriteHandler($var)
		{
			foreach($this->Rewrites as $row)
			{
				$rule = $row['rewrite_rule'];

				if(preg_match("@" . $rule . "@smU", $var, $matches))
				{
					if($row['rewrite_https'] === "1")
						$this->Site->EnableHTTPS();

					if(isset($row['rewrite_password']) && $row['rewrite_password'] !== '')
					{
						if(md5($this->Session['protected_password']) === $row['rewrite_password'])
						{
							if(isset($row['rewrite_replacement']) && $row['rewrite_replacement'] !== '')
							{
								$replacement = $row['rewrite_replacement'];
								
								for($i = 1, $l = count($matches) + 1; $i < $l; ++$i)
								{
									$replacement = str_replace("$" . $i, $matches[$i], $replacement);
								}
								
								if($url = parse_url($replacement))
								{
									if($query = $url['query'])
									{
										foreach(explode("&", $query) as $amps)
										{
											$pieces = explode("=", $amps);
											
											$this->Request[$pieces[0]] = $pieces[1];
										}
									}
								}
								
								$var = preg_replace("@^(/)+@", '', fix_path(preg_replace("@\?(.+)$@", '', $replacement)));
							}
						}
						else 
						{
							$var = "authorize";
						}
					}
					else 
					{
						$rule = $row['rewrite_rule'];
	
						if(preg_match("@" . $rule . "@smU", $var, $matches))
						{
							if(isset($row['rewrite_replacement']) && $row['rewrite_replacement'] !== '')
							{
								$replacement = $row['rewrite_replacement'];
		
								for($i = 1, $l = count($matches) + 1; $i < $l; ++$i)
								{
									$replacement = str_replace("$" . $i, $matches[$i], $replacement);
								}
								
								if($url = parse_url($replacement))
								{
									if($query = $url['query'])
									{
										foreach(explode("&", $query) as $amps)
										{
											$pieces = explode("=", $amps);
											
											$this->Request[$pieces[0]] = $pieces[1];
										}
									}
								}
								
								$var = preg_replace("@^(/)+@", '', fix_path(preg_replace("@\?(.+)$@", '', $replacement)));
							}
						}
					}
				}
			}

			return $var;
		}
		
		public function __BuildMenu($name, $levels = 10)
		{
			if($levels > 0)
			{
				$menu = array();
				
				$query = 
					"SELECT * 
					FROM `" . $this->DB->Prefix . "menus` 
					WHERE `menu_name` = '" . $name . "'";
	
				if($data = $this->DB->FetchRow($query, "slave")) 
				{
					$menu['qqq'] = $data;
				
					$query = 
						"SELECT * 
						FROM `" . $this->DB->Prefix . "menus` 
						WHERE `parent_menu_id` = " . $data['menu_id'] . " 
						AND `menu_id` != " . $data['menu_id'];
					
					if($rows = $this->DB->FetchRows($query, "slave"))
					{
						--$levels;

						foreach($rows as $row) 
						{
							$query = 
								"SELECT `group_id`, `permission_view` 
								FROM `{$this->DB->Prefix}menu_permissions`
								WHERE `menu_id` = " . $row['menu_id'];
						
							$permissions = array();
						
							foreach($this->DB->FetchRows($query, "slave") as $permission)
							{
								$permissions[$permission['group_id']] = $permission;
							}

							if($permissions[$this->User->Group->ID]['permission_view'] === "1")
							{
								$menu[] = $this->BuildMenu($row['menu_name'], $levels);
							}
							else
							{
								
							}
						}
					}
					else 
					{
						switch($data['menu_state'])
						{
							case "enabled":
								$data['menu_link'] = $this->ParseVariables($data['menu_link']);
								$data['menu_image'] = $this->ParseVariables($data['menu_image']);
								$data['menu_title'] = $this->ParseVariables($data['menu_title']);
							
								$menu['ppp'] = $data;
							break;
							case "disabled":
								unset($data['menu_link']);
								
								$menu['ppp'] = $data;
							break;
							case "hidden":
								unset($menu['qqq']);
							break;
						}
					}
				}
			}
			
			return $menu;
		}
		
		public function __DisplayMenu($menu, $ul_class = '', $li_class = '', $levels = 10)
		{
			$html = '';
			$levels2 = $levels;

			if(array_key_exists("qqq", $menu))
			{
				if($levels === 10)
				{
					$html .= <<< EOH

<ul class="{$ul_class}">

EOH;
				}
				else 
				{
					$html .= <<< EOH
<li class="{$li_class}">

EOH;

					if(isset($menu['qqq']['menu_title']) && $menu['qqq']['menu_title'] != "")
					{
						$html .= <<< EOH
						
<span>{$menu['qqq']['menu_title']}</span>
						
EOH;
					}

					$html .= <<< EOH
<ul class="{$ul_class}">

EOH;
				}


				unset($menu['qqq']);
				
				$gogo = true;
			}
			
			--$levels;

			foreach($menu as $offset => $value)
			{
				if(is_array($value) && array_key_exists("ppp", $value))//&& $offset === "ppp")//&& array_key_exists("ppp", $value))
				{
					if(isset($value['ppp']['menu_link']) && $value['ppp']['menu_link'] !== '')
						$link = "<a href='{$value['ppp']['menu_link']}' title='{$value['ppp']['menu_title']}'>{$value['ppp']['menu_title']}</a>";
					else
						$link = "{$value['ppp']['menu_title']}";
						
						$html .= <<< EOH
						
<li class="{$li_class}">{$link}</li>

EOH;
				}
				else if(is_array($value))//array_key_exists("qqq", $value))
				{
					$html .= $this->DisplayMenu($value, $ul_class, $li_class, $levels);
				}
				else 
				{

				}
			}

			if($gogo)
			{
				if($levels2 === 10)
				{
						$html .= <<< EOH

</ul>
							
EOH;
				}
				else 
				{
						$html .= <<< EOH

	</ul>		
</li>
							
EOH;
				}
			}
				
			return $html;
		}
		
		public function __ParseVariables($data, $vars = array())
		{
			if(preg_match_all("/\[(.+)_(.+)\]/smU", $data, $matches))
			{
				for($i = 0, $l = count($matches[0]); $i < $l; ++$i)
				{
					$var = $matches[1][$i];
					$var2 = $matches[2][$i];

					if(isset($this->$var->$var2))
						$val = $this->$var->$var2;
					else if(isset($vars[$var . "_" . $var2]))
						$val = $vars[$var . "_" . $var2];

					if(isset($val))
						$data = str_replace($matches[0][$i], $val, $data);
				}
			}
			
			return $data;
		}
		
		public function __SendHeaders_callbefore()
		{
			if($this->Site['gzip_enabled'])
				$this->Page->GZip();
		}
	}
	
	//--------------------------------
	// Setup module
	//--------------------------------
	
	$this->AddModule(new Core($DB));

	if($this->Request['page'] === "jFileManager/")
			$this->DirectoryListing();
	
	
	//--------------------------------
	// Load & setup our rewrites
	//--------------------------------
	
	$this->ApplyRewrites();
	
	//--------------------------------
	// Give our module a language
	//--------------------------------

	$this->Languages->Core = new Language("Errors");
	
?>
