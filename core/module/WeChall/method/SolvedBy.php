<?php
final class WeChall_SolvedBy extends GWF_Method
{
	public function getHTAccess()
	{
		return
			'RewriteRule ^challenge_solvers_for/(\d+)/[^/]+$ index.php?mo=WeChall&me=SolvedBy&cid=$1'.PHP_EOL.
			'RewriteRule ^challenge_solvers_for/(\d+)/[^/]+/page-(\d+)$ index.php?mo=WeChall&me=SolvedBy&cid=$1&page=$2'.PHP_EOL;
		
	}

	public function execute()
	{
		if (false === ($chall = WC_Challenge::getByID(Common::getGet('cid')))) {
			return $this->module->error('err_challenge');
		}
		
		$ipp = 50;
		$nItems = $chall->getVar('chall_solvecount');
		$nPages = GWF_PageMenu::getPagecount($ipp, $nItems);
		$page = Common::clamp(intval(Common::getGet('page', 1)), 1, $nPages);
		$from = GWF_PageMenu::getFrom($page, $ipp);
		$tVars = array(
			'users' => $this->getSolvers($chall, $from, $ipp),
			'chall' => $chall,
			'sort_url' => '',
			'pagemenu' => GWF_PageMenu::display($page, $nPages, GWF_WEB_ROOT.'challenge_solvers_for/'.$chall->getVar('chall_id').'/'.$chall->urlencode('chall_title').'/page-%PAGE%'),
		);
		return $this->module->templatePHP('chall_solvers.php', $tVars);
	}

	public function getSolvers(WC_Challenge $chall, $from, $ipp)
	{
		$db = gdo_db();
		$cid = $chall->getID();
		$users = GWF_TABLE_PREFIX.'user';
		$solved = GWF_TABLE_PREFIX.'wc_chall_solved';
		$limit = GDO::getLimit($ipp, $from);
		$query = "SELECT user_name, user_level, user_countryid, csolve_date, csolve_1st_look, csolve_time_taken FROM $solved JOIN $users ON user_id=csolve_uid WHERE csolve_cid=$cid AND csolve_date!='' ORDER BY csolve_date ASC $limit";
		$back = array();
		if (false === ($result = $db->queryRead($query))) {
			return $back;
		}
		while (false !== ($row = $db->fetchAssoc($result)))
		{
			$back[] = new GWF_User($row);
		}
		$db->free($result);
		return $back;
	}
}
?>
