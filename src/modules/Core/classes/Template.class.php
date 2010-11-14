<?php

	class Template
	{
		public $Data;
		
		public function __construct($data)
		{
			$this->Data = $data;
		}
		
		public function __toString()
		{
			// template contains macro/s
			if(preg_match_all("#<%(.+)%>#smU", $this->Data, $macros))
			{
				$macros = $macros[1];
				
				foreach($macros as $macro_org)
				{
					$macro = trim($macro_org);
					
					if($content = G::$Engine->Page->Theme->Macros[$macro])
					{
						$this->Data = str_replace("<%" . $macro_org . "%>", $content, $this->Data);
					}
				}
			}
			
			return $this->Data;
		}
	}

?>