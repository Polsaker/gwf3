<?php # Usage: %CMD% <password>. Registers you with Lamb and logs you in. Use %CMD% <oldpass> <newpass> to change your password. Features NickServ auto-auth-avec Lamb. 
$split = explode(' ', $message);
if (count($split) === 2)
{
	if (md5($split[0]) !== $user->getVar('lusr_password'))
	{
		$bot->reply(sprintf('You have to submit your old password. Usage: %sregister <oldpass> <newpass>', LAMB_TRIGGER));
	}
	else
	{
		$user->saveVar('lusr_password', md5($split[1]));
		$user->setLoggedIn(true);
		$bot->reply(sprintf('Your password has been changed. You are now logged in.'));
	}
}
elseif ($user->isRegistered())
{
	$bot->reply('You are already registered.');
}
elseif ( (count($split) !== 1) || (strlen($split[0])<4) )
{
	$bot->reply(sprintf('Please submit a valid password.'));
}
else
{
	$user->saveVar('lusr_password', md5($split[0]));
	$user->setLoggedIn(true);
	$bot->reply(sprintf('You have successfully registered with Lamb. You are now logged in.'));
}
?>