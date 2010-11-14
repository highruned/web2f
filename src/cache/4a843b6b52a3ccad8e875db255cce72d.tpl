<?php

	if($this->Request['submit'])
	{
		// the variables to authorize have been requested
		if($this->Request['login_username'] && $this->Request['login_password'])
		{
			if($this->Request->ValidateSQL("login_username", "login_password"))
			{
				$query = 
					"SELECT * FROM `{$this->DB->Prefix}users` 
					WHERE `user_username` = '" . $this->Request['login_username'] . "' 
					AND `user_password` = '" . md5($this->Request['login_password']) ."' 
					LIMIT 1";
				
				// username/password is correct
				if($data = $this->DB->FetchRow($query, "slave"))
				{
					$this->Session['status'] = 0;

					//--------------------------------
					// Lets make this easier to manage
					//--------------------------------
					
					$user = preg_replace_array("/^user_/i", '', $data);
					
					$this->User->Merge($user);

					// save the session args
					$this->Session['username'] = $user['username'];
					$this->Session['password'] = $user['password'];
					
					if($redirect)
					{
						// redirection url is setup
						if($this->Session['redirect'])
						{
							// var to temporarily hold redirect url
							$redirect = $this->Session['redirect'];
							
							// remove the redirect
							unset($this->Session['redirect']);
							
							// carry through the redirect with temporary var
							$this->Redirect($redirect);
						}
					}
					else
					{
						$this->Redirect($this->Site->URL . "/admin/");
					}
				}
				else
				// username/password is incorrect
				{
					$this->Session['status'] = 3;
				}
			}
			else
			{
				$this->Session['status'] = 4;
			}
		}

		// instead redirect to the current url (postback)
		$this->Redirect($this->Page->RequestURL);
	}

?>

<?php

	$this->Page->Theme->Location = false;
	$this->Page->Theme->Menu = false;

?>

<center>
<form action="" method="post" style="width: 300px">
<?php

	switch($this->Session->Status)
	{
		case 1: echo "<div class='message-orange'>{$this->Lang['enter_user_pass']}</div><br />"; break;
		case 2: echo "<div class='message-blue'>{$this->Lang['sess_expired']}</div><br />"; break;
		case 3: echo "<div class='message-red'>{$this->Lang['cant_find_user_pass']}</div><br />"; break;
		case 4: echo "<div class='message-red'>{$this->Lang['invalid_user_pass']}</div><br />"; break;
		case 5: echo "<div class='message-yellow'>{$this->Lang['now_logged_out']}</div><br />"; break;
		case 6: echo "<div class='message-blue'>{$this->Lang['no_sess_found']}</div><br />"; break;
	}

?>

	<fieldset>
		<legend>
			<strong><?=$this->Lang['title_login']?></strong>
		</legend>

		<table cellpadding="0" cellspacing="5" border="0">
			<tr>
				<td style="width: 75px;">
					<strong><?=$this->Lang['title_username']?></strong>
				</td>
				<td>
					<input class="special" type="text" name="login_username" size="20" maxlength="100" />
				</td>
			</tr>
			<tr>
				<td style="width: 75px;">
					<strong><?=$this->Lang['title_password']?></strong>
				</td>
				<td>
					<input class="special" type="password" name="login_password" size="20" maxlength="100" />
				</td>
			</tr>
		</table>
	</fieldset>
	<br />
	<div style="float: right">
		<input class="reset submit" type="submit" name="submit" value="<?=$this->Lang['title_login']?>" />
	</div>
</form>
</center>

<?php

	$this->Session->Status = 1;

?>