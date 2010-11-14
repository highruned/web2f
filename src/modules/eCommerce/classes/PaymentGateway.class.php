<?php

	abstract class PaymentGateway
	{
		public $Status;
		
		abstract public function ValidatePayment();
		abstract public function GetStatus();
	}

?>