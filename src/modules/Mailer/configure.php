<?php

	class Configure extends Module
	{
		public function __construct()
		{
			parent::__construct();

			$settings = &$this->LoadSetting("mailer");

			if($this->Request['submit'])
			{
				$settings['sender_email'] = $this->Request['sender_email'];
				
				$this->SaveSetting("mailer", $settings);

				$this->Redirect($this->Page->RequestURL);
			}
?>

<div class="box-2"><a href="templates/" title="Templates"><h3>Templates</h3></a></div>
<br /><br />
<form action="" method="post">
	<fieldset>
		<legend>
			<strong>General</strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 150px">
					<strong>Sender E-mail</strong>
				</td>
				<td>
					<input class="special" type="text" name="sender_email" maxlength="2048" size="40" value="<?=$settings['sender_email']?>" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<div style="float: right">
		<input class="reset submit" type="submit" name="submit" value="Save" />
	</div>
</form>
<br /><br /><br /><br /><br />

<?php
		}
	}

	$this->AddModule(new Configure());
	
?>