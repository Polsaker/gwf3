<?php
final class Konzert_Presse extends GWF_Method
{
	public function getHTAccess()
	{
		return 'RewriteRule ^presseberichte.html$ index.php?mo=Konzert&me=Presse'.PHP_EOL;
	}

	public function execute()
	{
		GWF_Website::addJavascript(GWF_WEB_ROOT.'js/jq/thumbnails.js');
		GWF_Website::addJavascriptOnload('konzInitPresse();');
		$this->module->setNextHREF(GWF_WEB_ROOT.'hoehrproben.html');
		return $this->templatePresse();
	}
	
	private function templatePresse()
	{
		$l = new GWF_LangTrans($this->module->getModuleFilePath('lang/presse'));
		
		GWF_Website::setPageTitle($l->lang('page_title'));
		
		$tVars = array(
			'title' => $l->lang('title'),
			'subtitle' => $l->lang('subtitle'),
			'text' => $l->lang('text'),
			'altimg' => $l->lang('altimg'),
			't1' => $l->lang('t1'),
			't2' => $l->lang('t2'),
			't3' => $l->lang('t3'),
			't4' => $l->lang('t4'),
			't5' => $l->lang('t5'),
		);
		return $this->module->template('presse.tpl', $tVars);
	}
}
?>