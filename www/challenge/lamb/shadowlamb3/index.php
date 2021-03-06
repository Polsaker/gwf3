<?php
chdir('../../../');
define('GWF_PAGE_TITLE', 'Shadowlamb - Chapter III');
require_once('challenge/html_head.php');
require_once GWF_CORE_PATH.'module/WeChall/solutionbox.php';
if (false === ($chall = WC_Challenge::getByTitle(GWF_PAGE_TITLE))) {
	$chall = WC_Challenge::dummyChallenge(GWF_PAGE_TITLE, 4, 'challenge/lamb/shadowlamb3/index.php');
}
$chall->showHeader();
if (false !== ($answer = Common::getPostString('answer', false)))
{
	require_once 'challenge/lamb/shadowlamb1/WC5Lamb_Solution.php';
	shadowlamb3solver($chall, $answer);
}
echo GWF_Box::box($chall->lang('info', array('client.php')), $chall->lang('title'));
echo formSolutionbox($chall);
echo $chall->copyrightFooter();
require_once('challenge/html_foot.php');
?>
<?php
function shadowlamb3solver(WC_Challenge $chall, $answer)
{
	if (!GWF_Session::isLoggedIn())
	{
		echo GWF_HTML::error('Shadowlamb', 'Better login first!');
		return;
	}
	$code = WC5Lamb_Solution::validateSolution3($answer, GWF_Session::getUserID());
	switch ($code)
	{
		case 1:
			echo GWF_HTML::message('Shadowlamb', $chall->lang('msg_right'));
			$chall->onChallengeSolved(GWF_Session::getUserID());
			break;
		default:
			echo GWF_HTML::error('Shadowlamb', $chall->lang('err_wrong_'.$code));
			break;
	}
}
?>