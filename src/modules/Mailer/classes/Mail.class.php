<?php

	class Mail
	{
		protected $Method;
		protected $Data;
		public $Templates;
		
		public function __construct($method = "php", $data = array())
		{
			if($method === "smtp")
			{
				$data['port'] = $data['port'] ? intval($data['port']) : 25;
				$data['host'] = $data['host'] ? $data['host'] : "localhost";
				
				$this->Data = $data;
			}
			else if($method === "php")
			{
				
			}
			
			$this->Method = $method;
			$this->Templates = array();
		}
		
		public function Send($to, $from, $subject, $message)
		{

			switch($this->Method)
			{
				case "smtp":
					
				break;
				
				case "other":
					
				break;
				
				case "php":
				default:
					
					$html_message = str_replace("<br />", "<br>", $message);
					$text_message = strip_tags(str_replace("<br />", "\r\n", $message));
					
					$unique_id = md5(uniqid(time()));

					$headers = array();
					
					$headers[] = "From: " . $from;

					$headers[] = "To: " . $to;
					
					$headers[] = "Reply-To: " . $from;

					$headers[] = "Return-Path: " . $from;

					$headers[] = "MIME-Version: 1.0";
					
					$headers[] = "X-Mailer: PHP/" . phpversion();
					
					$headers[] = 'Content-Type: multipart/alternative; boundary="' . $unique_id . '"'; 
					
					$headers[] = "";
					
					$headers[] = "--" . $unique_id;

					$headers[] = 'Content-Type: text/plain; charset = "' . G::$Engine->Site['charset'] . '"';
					
					$headers[] = "Content-Transfer-Encoding: 8bit";

					$headers[] = "";
					
					$headers[] = $text_message;

					$headers[] = "";
					
					$headers[] = "--" . $unique_id;
					
					$headers[] = 'Content-Type: text/html; charset = "' . G::$Engine->Site['charset'] . '"';
					
					$headers[] = "Content-Transfer-Encoding: 8bit";

					$headers[] = "";
					
					$headers[] = $html_message;
					
					$headers[] = "";

					$headers[] = "--" . $unique_id . "--";

					if(!@mail($to, $subject, '', implode("\r\n", $headers)))
						// try without args for safe mode servers
						if(!@mail($to, $subject, $text_message))
							throw new Exception(G::$Engine->Lang['php_mail_failed'], 79);

				break;
			}
		}
		
		public function &LoadTemplate($name, $extras = null)
		{
			if(isset($this->Templates[$name]))
			{
				$this->ParseTemplate($name, $extras);
				
				return $this->Templates[$name];
			}
			else
			{
				$query = 
					"SELECT `template_title`, `template_content` 
					FROM `" . G::$Engine->DB->Prefix . "mailer_templates` 
					WHERE `template_name` = '" . $name . "'";
				
				if($data = G::$Engine->DB->FetchRow($query, "slave")) 
				{
					$this->Templates[$name] = array();
					$this->Templates[$name]['title'] = $data['template_title'];
					$this->Templates[$name]['content'] = $data['template_content'];
					
					$this->ParseTemplate($name, $extras);
					
					return $this->Templates[$name];
				}
				else 
					return false;
			}
		}
		
		public function SaveTemplate($name)
		{
			if(isset($this->Templates[$name]))
			{
				$title = $this->Templates[$name]['title'];
				$content = $this->Templates[$name]['content'];
				
				$query = 
					"INSERT INTO `" . G::$Engine->DB->Prefix . "mailer_templates` 
					SET `template_name` = '" . $name . "', `template_title` = '" . $title . "', `template_content` = '" . $content . "'
					ON DUPLICATE KEY UPDATE `template_content` = '" . $content . "'";
	
				G::$Engine->DB->Query($query);
			}
			else 
				return false;
		}
		
		public function ParseTemplate($name, $extras = null)
		{
			if(isset($this->Templates[$name]))
			{
				$this->Templates[$name]['content'] = G::$Engine->ParseVariables($this->Templates[$name]['content'], $extras);
				
				$this->Templates[$name]['title'] = G::$Engine->ParseVariables($this->Templates[$name]['title'], $extras);
			}
			else 
				return false;
		}
	}

?>