<?php

	class Sitemap extends Module
	{
		public function __CreatePage()
		{
			header("Content-Type: text/xml; charset=" . G::$Engine->Site['charset']);
			
			echo '<?xml version="1.0" encoding="' . G::$Engine->Site['charset'] . '"?>';
			
			echo <<< EOH
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

EOH;

			$query = 
				"SELECT `page_path`, `page_id` 
				FROM `{$this->DB->Prefix}pages`";
			
			foreach($this->DB->FetchRows($query, "slave") as $row)
			{
				$query = 
					"SELECT `permission_view`
					FROM `" . $this->DB->Prefix . "page_permissions` 
					WHERE `page_id` = " . $row['page_id'] . " 
					AND `group_id` = " . $this->User->Group['id'] . " 
					LIMIT 1";

				$row2 = $this->DB->FetchRow($query, "slave");
				
				if($row2['permission_view'] === "1")
				{
					$loc = $this->Site->URL . fix_path("/" . $row['page_path']);
				
					echo <<< EOH
	<url>
	  <loc>{$loc}</loc>
	  <priority>0.5</priority>
	  <changefreq>daily</changefreq>
	</url>

EOH;
				}
			}
			
			echo <<< EOH

</urlset>		
EOH;
		}
	}

	switch($this->Request['page'])
	{
		case "sitemap.xml":
			$this->AddModule(new Sitemap());
			
		case "sitemap.xml.gz":
			
	}
	
?>