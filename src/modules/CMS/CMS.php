<?php
	
	class CMS extends Module
	{
		public function Initialize()
		{
			//--------------------------------
			// Initialize settings
			//--------------------------------
			
			$this->Name = "";
			$this->Version = "";
			$this->URL = "";
			$this->Copyright = '' . $this->Version;
		}
		
		public function __Authorize($__page)
		{
			$query = 
				"SELECT `page_id`, `theme_name` 
				FROM `{$this->DB->Prefix}pages` 
				WHERE `page_path` = '{$__page}' 
				OR `page_path` = '/{$__page}' 
				OR `page_path` = '{$__page}/' 
				OR `page_path` = '/{$__page}/' 
				LIMIT 1";
		
			$row = $this->DB->FetchRow($query, "slave");

			if(isset($row['page_id']) && $row['page_id'] != '')
			{
				$query = 
					"SELECT `permission_view`
					FROM `" . $this->DB->Prefix . "page_permissions` 
					WHERE `page_id` = " . $row['page_id'] . " 
					AND `group_id` = " . $this->User->Group['id'] . " 
					LIMIT 1";

				$row = $this->DB->FetchRow($query, "slave");
				
				if($row['permission_view'] === "1")
				{
					return true;
				}
				else
				{
					//$this->User->Authorized = false;
					
					return false;
				}
			}
			else 
			{
				return true;
			}
		}
		
		public function __GrabPage($page)
		{
			$found = false;
			
			$settings = &$this->LoadSetting("modules");
			
			foreach($settings as $module => $value)
			{
				if($value['enabled'])
				{
					$filename = "/" . $page;
					
					// check if request page is a directory
					if(!preg_match("@/.+([\.]+).+$@smU", $page, $matches))
					{
						$filename = $filename . "/index.php";
					}
					
					$path = PATH . "/modules/" . $module . "/www" . $filename;
	
					if(file_exists($path))
					{
						$found = true;
						
						$this->Page->Content = $this->LoadFile($path, SAVE_BUFFER);
					}
				}
			}
			
			if(!$found)
			{
				$mvc = split('/', $page);
	
				if(count($mvc) > 1)
				{
					$controller = array_shift($mvc);
					$method = array_shift($mvc);
					
					if($module = $this->FindController($controller, $method, $mvc))
						$this->CallController($module, $method, $mvc);
					else 
					{
						$query = "SELECT `page_id`, `theme_name` FROM `{$this->DB->Prefix}pages` WHERE `page_path` = '{$page}' LIMIT 1";
					
						$row = $this->DB->FetchRow($query, "slave");
	
						if(isset($row['theme_name']) && $row['theme_name'] !== '')
							// keep the requested theme and change to it
							$this->Page['theme_name'] = $row['theme_name'];
						
						if(isset($row['page_id']) && $row['page_id'] != '')
						{
							$query = 
								"SELECT `permission_view`
								FROM `" . $this->DB->Prefix . "page_permissions` 
								WHERE `page_id` = " . $row['page_id'] . " 
								AND `group_id` = " . $this->User->Group['id'] . " 
								LIMIT 1";
							
							$row = $this->DB->FetchRow($query, "slave");
							
							if($row['permission_view'] === "1")
							{
								$this->Page->Create($page);
							}
							else
							{
								$page = "restricted";
								
								if(!$this->Page->Create($page))
								{
									$page = "error";
									
									$this->Page->Create($page);
								}
								
								$this->Session['redirect'] = $this->Page->RequestURL;
							}
						}
						else 
						{
							if(file_exists($this->Site->Path . "/themes/" . $this->Site->Theme->Path . "/" . $page))
							{
								$this->Redirect($this->Site->URL . "/themes/" . $this->Site->Theme->Path . "/" . $page);
							}
							else 
							{
								$this->Page->Headers[] = "HTTP/1.1 404 Not Found";
								$this->Page->Headers['Status'] = "404 Not Found";
								
								$page = "error";
								
								$this->Page->Create($page);
							}
						}
					}
				}
				else
				{
					$row = $this->DB->FetchRow("SELECT `page_id`, `theme_name` FROM `{$this->DB->Prefix}pages` WHERE `page_path` = '{$page}' LIMIT 1", "slave");
					
					if(isset($row['theme_name']) && $row['theme_name'] !== '')
						$this->Page['theme_name'] = $row['theme_name'];
					
					if(isset($row['page_id']) && $row['page_id'] != '')
					{
						$query = 
							"SELECT `permission_view`
							FROM `" . $this->DB->Prefix . "page_permissions` 
							WHERE `page_id` = " . $row['page_id'] . " 
							AND `group_id` = " . $this->User->Group['id'] . " 
							LIMIT 1";
						
						$row = $this->DB->FetchRow($query, "slave");
						
						if($row['permission_view'] === "1")
						{
							$this->Page->Create($page);
						}
						else
						{
							$page = "restricted";
							
							if(!$this->Page->Create($page))
							{
								$page = "error";
								
								$this->Page->Create($page);
							}
							
							$this->Session['redirect'] = $this->Page->RequestURL;
						}
					}
					else 
					{
						if(file_exists($this->Site->Path . "/themes/" . $this->Site->Theme->Path . "/" . $page))
						{
							$this->Redirect($this->Site->URL . "/themes/" . $this->Site->Theme->Path . "/" . $page);
						}
						else 
						{
							$this->Page->Headers[] = "HTTP/1.1 404 Not Found";
							$this->Page->Headers['Status'] = "404 Not Found";
							
							$page = "error";
							
							$this->Page->Create($page);
						}
					}
				}
			}
			
			return $page;
		}
		
		public function __CreatePage($__page, $__path = null)
		{
			$__filename = $this->Site->Path . "/cache/" . md5($this->Page->RequestURL) . ".tpl";

			$__page = $this->GrabPage($__page);
			
			$this->Page->Headers['Content-Type'] = $this->Page['mime_type'] . "; charset=" . $this->Page['charset'];
			
			// if this file doesn't exist, or this cache is older than 6 hours
			if(!file_exists($__filename) || (file_exists($__filename) && filemtime($__filename) < (time() - $this->Page['cache_time'])))
			{
				// set page url and remove extra slashes
				$this->Page->URL = fix_path($this->Site->URL . '/' . $__page);

				// combine all titles
				$this->Page->Title = implode(" | ", $this->Page->Title);
				
				if(get_class($this->Page->Theme) !== "Theme")
					// create the page theme - may default back to site theme
					$this->Page->Theme = new Theme($this->Page['theme_name']);
				
				if($this->User->IsSpider())
					$this->Page->ChangeTheme("robots");
				
				$this->Page->Theme->URL = $this->Site['url'] . "/themes/" . $this->Page['theme_name'];

				if($__path === null)
				{
					$this->SaveFile($__filename, $this->TemplateEngine->Process($this->Page->Content));
					
					// include the cache file that sets up the new content
					$this->Page->Content = $this->LoadFile($__filename, SAVE_BUFFER);
				}
				else 
				{
					$this->Page->Content = $this->LoadFile($__path, SAVE_BUFFER);
				}

				// input the entire page content into the buffer
				$this->Page->Input($this->LoadFile($this->Site->Path . "/themes/" . $this->Page->Theme->Path . "/templates/page.tpl", SAVE_BUFFER));
				
				// write the php content to the file
				$this->SaveFile($__filename, $this->Page);
			}
			// else include the cache file
			else
			{
				$this->Page->Input(file_get_contents($__filename));
			}
			
			return $this->Page;
		}
		
		public function __Main($page, $path = null)
		{
			$content = $this->CreatePage($page, $path);
			
			if($this->Site->HTTPS)
				$this->Site->EnableHTTPS();
			else 
				$this->Site->DisableHTTPS();
				
			$this->DB->Close();
			$this->Session->Close();

			//if(headers_sent($file, $line)) { echo "Headers were already sent in $file on line $line..."; }
			
			$this->Page->SendHeaders();

			exit($content);
		}
		
		public function ReplaceMacros($data)
		{
			// template contains macro/s
			if(preg_match_all("#<%(.+)%>#smU", $data, $macros))
			{
				$macros = $macros[1];
				
				foreach($macros as $macro_org)
				{
					$macro = trim($macro_org);
					
					if($content = $this->Page->Theme->Macros[$macro])
					{
						$data = str_replace("<%" . $macro_org . "%>", $content, $data);
					}
				}
			}
			
			return $data;
		}
		
		//--------------------------------
		// EZ Methods
		//--------------------------------
	}
	
	//--------------------------------
	// Setup module
	//--------------------------------
	
	$this->AddModule(new CMS());
	
	//--------------------------------
	// Give our module a language
	//--------------------------------
	
	$this->Languages->CMS = new Language("Main");

?>
