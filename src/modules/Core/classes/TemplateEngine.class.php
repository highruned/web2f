<?php

	class TemplateEngine
	{
		private $Safe;
		
		public function __construct($safe = false)
		{
			$this->Safe = $safe;
			
			
		}
		
		public function Process($input)
		{
			$content = <<< ZZZ
			
EOH;

$1

\$this->Page->Content .= <<< EOH

ZZZ;
			
			return preg_replace("/{{(.+)}}/smi", $content, $input);
		}
	}

?>