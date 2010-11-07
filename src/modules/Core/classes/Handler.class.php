<?php

	class Handler
	{
		public function Error($errno, $errstr, $errfile, $errline)
		{
			switch($errno)
			{
				case E_USER_WARNING:
				case E_USER_NOTICE:
				case E_WARNING:
				case E_NOTICE:
				case E_CORE_WARNING:
				case E_COMPILE_WARNING:
				break;
				
				case E_USER_ERROR:
				case E_ERROR:
				case E_PARSE:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
					throw new Exception("{$errstr} ({$errno}) in file {$errfile} on line {$errline}", true);
				break;
			}
		}
		
		public function Exception($exception)
		{
			$message = $exception->getMessage() . "\r\n\r\n" . "Code: " . $exception->getCode() . "\r\n\r\n" . $_SERVER['REQUEST_URI'] . "\r\n\r\n" . $exception->getTraceAsString();
			
			$this->Log($message);
		}
		
		public function Log($message)
		{
			$filename = PATH . "/logs/" . date("d-m-y-h-i-s") . ".log";
			
			$fp = fopen($filename, "w");
			
			fwrite($fp, $message);
			
			fclose($fp);
			
			echo $message;
		}
	}
	
?>