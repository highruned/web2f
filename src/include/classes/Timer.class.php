<?php

	class Timer
	{
		private $Start;
		
		public function __construct()
		{
			$this->Start = $this->Microtime();
		}
		
		public function Microtime()
		{
			list($usec, $sec) = explode(" ", microtime());
			
			return (float) $usec + (float) $sec;
		}
		
		public function Stop($precision = 2)
		{
			return sprintf("%01.{$precision}f", $this->Microtime() - (float) $this->Start);
		}
	}

?>