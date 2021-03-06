<?php
final class Profile_Places extends GWF_Method
{
	public function getHTAccess()
	{
		return 'RewriteRule ^places$ index.php?mo=Profile&me=Places'.PHP_EOL;
	}
	
	public function execute()
	{
		if (!$this->module->canReadPOIs())
		{
			return $this->module->error('err_poi_read_perm');
		}
		if (isset($_POST['delete']))
		{
			return $this->onDelete().$this->templatePlaces();
		}
		return $this->templatePlaces();
	}
	
	private function googleAPIKey()
	{
		$api_key = $this->module->cfgMapsApiKey();
		return $api_key === '' ? '' : '&key='.$api_key;
	}
	
	private function googleMapsPath()
	{
		return Common::getProtocol().'://maps.googleapis.com/maps/api/js?sensor=false'.$this->googleAPIKey();
	}
	
	private function templatePlaces()
	{
		GWF_Website::addJavascript($this->googleMapsPath());
		GWF_Website::addJavascript(GWF_WEB_ROOT_NO_LANG.'js/module/Profile/profile.js?v=57');
// 		GWF_Website::addJavascript(GWF_WEB_ROOT_NO_LANG.'js/3p/fancybox/jquery.fancybox.pack.js');
// 		GWF_Website::addCSS(GWF_WEB_ROOT_NO_LANG.'js/3p/fancybox/jquery.fancybox.css');
// 		GWF_Website::addCSS(GWF_WEB_ROOT_NO_LANG.'css/profile_poi.css');
		
		$user = GWF_User::getStaticOrGuest();
		$userid = $user->getID();
		$table = GDO::table('GWF_ProfilePOI');
		$tVars = array(
			'user_id' => $userid,
			'is_admin' => $user->isAdmin() ? 'true' : 'false',
			'total' => $table->countRows(),
			'visible' => $table->countRows(GWF_ProfilePOI::wherePermissions(), array('users', 'profiles', 'whitelist')),
			'js_trans' => $this->jsTrans(),
			'form_delete' => $this->formDelete(),
			'pois' => GWF_ProfilePOI::getPOICount($userid),
			'maxp' => $this->module->cfgAllowedPOIs(),
			'api_key' => $this->module->cfgMapsApiKey(),
			'protocol' => Common::getProtocol(),
			'init_lat' => 0,
			'init_lon' => 0,
		);
		
		return $this->module->templatePHP('places.php', $tVars);
	}

	private function jsTrans()
	{
		$data = array(
			'guest' => GWF_HTML::lang('guest'),
			'remove' => $this->l('prompt_delete'),
			'rename' => $this->l('prompt_rename'),
			'err_jump' => $this->l('err_poi_jump'),
		);
		return json_encode($data);
	}
	
	private function formDelete()
	{
		$data = array(
			
		);
		return new GWF_Form($this, $data);
	}
}
