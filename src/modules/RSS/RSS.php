<?php

	class RSS extends Module
	{
		public function __CreatePage($__page)
		{
			$this->Page->Header['Content-Type'] = "text/xml; charset=UTF-8";
		
			$__filename = $this->Site->Path . "/cache/" . md5($__page) . ".tpl";

			// if this file doesn't exist, or this cache is older than 6 hours
			if(!file_exists($__filename) || (file_exists($__filename) && filemtime($__filename) < (time() - $this->Page['cache_time'])))
			{
				$__page = $this->GrabPage($__page);
				
				// set page url and remove extra slashes
				$this->Page->URL = fix_path($this->Site->URL . '/' . $__page);

				// combine all titles
				$this->Page->Title = implode(" | ", $this->Page->Title);
				
				// create the page theme - may default back to site theme
				$this->Page->Theme = new Theme($this->Page['theme_name']);
				
				$this->Page->Theme->URL = $this->Site['url'] . "/themes/" . $this->Page['theme_name'];

				$this->SaveFile($__filename, $this->TemplateEngine->Process($this->Page->Content));

				$this->Page->Content = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>";

				$this->Page->Content .= <<< EOH
				
<rss version="2.0">
<channel>
	<title>{$this->Site->Title}</title>
	<link>{$this->Site->URL}</link>
	<description>{$this->Site->Description}</description>
	<generator>{$this->Name} {$this->Version}</generator>
	

	<item>
		<title>{$this->Page->Title}</title>
		<link>{$this->Page->URL}</link>
		<description>
		<![CDATA[
				
EOH;
				
				// include the cache file that sets up the new content
				$this->Page->Content .= stripslashes($this->LoadFile($__filename, SAVE_BUFFER));
				
				$this->Page->Content .= <<< EOH

		]]>
		</description>
	</item>

</channel>
</rss>
		
EOH;
				
				$this->Page->Input($this->Page->Content);
				
				// write the php content to the file
				$this->SaveFile($__filename, $this->Page);
			}
			// else include the cache file
			else
			{
				$this->Page->Input(file_get_contents($__filename));
			}
			
			$this->Page->GZip();
			
			return $this->Page;
		}
	}

	if($this->Request['rss'])
		$this->AddModule(new RSS());
	
?>