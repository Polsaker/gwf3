<?php
final class VersionServer_Zipper extends GWF_Method
{
	public function getUserGroups() { return GWF_Group::ADMIN; }
	public function getHTAccess()
	{
		return 
			'RewriteRule ^zipper/?$ index.php?mo=VersionServer&me=Zipper'.PHP_EOL;
	}
	
	private $style = array('');
	private $num_files = 0;
	
	private $has_error = true;
	public function hasError() { return $this->has_error; }
	
	public static $rootfiles = array(
		'gwf3.class.php',
		'GWF3.php',
// 		'www/gwf_cronjob.example.php',
// 		'www/index.example.php',
// 		'www/robots.txt',
	);
	
// 	public static $protected_dirs = array(
// 	);
	
	public static $protected_files = array(
// 		'www/protected/config.example.php',
// 		'www/protected/db_backup.example.sh',
		'www/protected/index.php',
// 		'www/protected/temp_ban.lst.txt',
// 		'www/protected/temp_ban.php',
// 		'www/protected/temp_down.php',
	);
	
	public static $tempdirs = array(
		'www/applet',
		'www/dbimg/avatar', 'www/dbimg/content', 'www/dbimg/forum_attach', 'www/dbimg/gpg', 
		'www/temp',
		'www/protected/logs', 'www/protected/db_backups', 'www/protected/db_backups_old', 'www/protected/rawlog', 'www/protected/zipped',
		'extra/temp/offer', 'extra/temp/upload', 'extra/temp/gpg',
		'extra/temp/smarty_cache', 'extra/temp/smarty_cache/cache', 'extra/temp/smarty_cache/cfg', 'extra/temp/smarty_cache/tpl', 'extra/temp/smarty_cache/tplc',
	);
	
	public function execute()
	{
		if (false !== (Common::getPost('zipper'))) {
			return $this->onZipB();
		}
		return $this->templateZipper();
	}
	
	public function getForm()
	{
		$data = array();
		
		$data['style'] = array(GWF_Form::STRING, 'default', $this->module->lang('style'));
		
		$data['div'] = array(GWF_Form::HEADLINE, '', $this->module->lang('ft_zipper'));
		
		$modules = GWF_ModuleLoader::loadModulesFS();
		
		GWF_ModuleLoader::sortModules($modules, 'module_name', 'ASC');
		
		foreach ($modules as $m)
		{
			#$m instanceof GWF_Module;
			$name = $m->getName();
			$key = sprintf('mod_%s', $name);
			$data[$key] = array(GWF_Form::CHECKBOX, $m->isEnabled(), $name);
		}
		$data['zipper'] = array(GWF_Form::SUBMIT, $this->module->lang('btn_zip'));
		
		return new GWF_Form($this, $data);
	}
	
	private function templateZipper()
	{
		$form = $this->getForm();
		
		$tVars = array(
			'form' => $form->templateY($this->module->lang('ft_zipper')),
		);
		return $this->module->template('zipper.tpl', $tVars);
	}
	
	private $archiveName = false;
	public function setArchiveName($fullpath)
	{
		$this->archiveName = $fullpath;
	}
	
	private function getArchiveName()
	{
		if ($this->archiveName === false) {
			return sprintf('www/protected/zipped/%s_%s.zip', GWF_Time::getDate(GWF_Date::LEN_SECOND), implode(',', $this->style));
		}
		else {
			return $this->archiveName;
		}
	}
	
	public function onZip($modules, $design)
	{
		$_POST = array();
		foreach ($modules as $modulename)
		{
			$_POST['mod_'.$modulename] = 'yes';
		}
		$_POST['style'] = $design;
		return $this->onZipB();
	}
	
	public function onZipB()
	{
		# No ZIP extension?
		if (!class_exists('ZipArchive', false)) {
			return $this->module->error('err_no_zip');
		}
//		require_once 'core/inc/util/GWF_ZipArchive.php';
		
		# Post Vars
		if ('' === ($styles = Common::getPostString('style', '')))
		{
			return $this->module->error('err_no_design');
		}
		
		$this->style = explode(',', $styles);
		$this->style[] = 'default';
		$this->style[] = 'install';
		unset($_POST['style']);
		unset($_POST['zipper']);
		$back = $this->onZipC();
		chdir(GWF_WWW_PATH);
		return $back;
	}
	
	public function onZipC()
	{
		# Create ZIP
		$archive = new GWF_ZipArchive();
		
		chdir(GWF_PATH);
		
		$archivename = $this->getArchiveName();
		if (false === ($archive->open($archivename, ZipArchive::CREATE|ZipArchive::CM_REDUCE_4))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		
		# ZIP STUFF
		# Core
		if (false === ($this->zipDir($archive, 'core/inc'))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		# ZIP Module(Groups)
		foreach ($_POST as $group => $checked)
		{
			if (!Common::startsWith($group, 'mod_')) {
				continue;
			}
			# zip dir recursive, do not ignore style
			if (false === ($this->zipDir($archive, 'core/module/'.substr($group, 4), true, false))) {
				return $this->module->error('err_zip', array(__FILE__, __LINE__));
			}
		}
		
		# 3rd Party Core
//		if (false === ($this->zipDir($archive, 'inc3p'))) {
//			return $this->module->error('err_zip', array(__FILE__, __LINE__));
//		}
		
		# Smarty
//		if (false === ($this->zipDir($archive, 'smarty_lib'))) {
//			return $this->module->error('err_zip', array(__FILE__, __LINE__));
//		}
		
		
		# JS
		if (false === ($this->zipDir($archive, 'www/js')))
		{
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		# Installer
		if (false === ($this->zipDir($archive, 'www/install')))
		{
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		# Base Lang
		if (false === ($this->zipDir($archive, 'core/lang'))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		# Images
		if (false === ($this->zipDir($archive, 'www/img', false))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
//		if (false === ($this->zipDir($archive, 'img/default/country', false))) {
//			return $this->module->error('err_zip', array(__FILE__, __LINE__));
//		}
//		if (false === ($this->zipDir($archive, 'img/default/smile', false))) {
//			return $this->module->error('err_zip', array(__FILE__, __LINE__));
//		}

		# Temp
		if (false === $this->addEmptyDirs($archive, self::$tempdirs)) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		# Fonts
		if (false === ($this->zipDir($archive, 'extra/font'))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		# Templates
		if (false === ($this->zipDir($archive, 'www/tpl', true, false))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		# Root Files
		if (false === ($this->addFiles($archive, self::$rootfiles))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		# Protected Dirs
//		if (false === $this->zipDirs($archive, self::$protected_dirs)) {
//			return $this->module->error('err_zip', array(__FILE__, __LINE__));
//		}
		
		# Protected Files
		if (false === ($this->addFiles($archive, self::$protected_files))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		# Module Extra Files and Dirs
		if (false === $this->zipDirs($archive, $this->getModuleExtraDirs())) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		if (false === ($this->addFiles($archive, $this->getModuleExtraFiles()))) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
//		chdir(GWF_WWW_PATH);
		
		$total_files = $archive->getTotalFilesCounter();

		if (false === $archive->close()) {
			return $this->module->error('err_zip', array(__FILE__, __LINE__));
		}
		
		$this->has_error = false;
		
		return $this->module->message('msg_zipped', array($archivename, GWF_Upload::humanFilesize(filesize($archivename)), $total_files));
	}
	
	private function getModuleExtraDirs()
	{
		$back = array();
//		foreach (GWF_Module::getModules() as $name => $module)
//		{
//			if (false !== ($mod = GWF_Module::getModule($name)))
//			{
//				$back = array_merge($back, $mod->getExtraDirs());
//			}
//		}
		return $back;
	}
	
	private function getModuleExtraFiles()
	{
		$back = array();
//		foreach (GWF_Module::getModules() as $name => $module)
//		{
//			if (false !== ($mod = GWF_Module::getModule($name)))
//			{
//				$back = array_merge($back, $mod->getExtraFiles());
//			}
//		}
		return $back;
	}
	
	private function addEmptyDirs(GWF_ZipArchive $archive, array $dirs)
	{
		foreach ($dirs as $dir)
		{
			if (false === $archive->addEmptyDir($dir)) {
				return false;
			}
			
			# Add htaccess and index.php if they exist for empty/temp dir
			$hta = $dir.'/'.'.htaccess';
			if (Common::isFile($hta)) {
				$archive->addFile($hta);
			}
			
			$index = $dir.'/'.'index.php';
			if (Common::isFile($index)) {
				$archive->addFile($index);
			}
			
		}
		return true;
	}
	
	private function addFiles(GWF_ZipArchive $archive, array $files)
	{
		foreach ($files as $file)
		{
			if (!$this->isFileWanted($file)) {
				continue;
			}

			if (!Common::isFile($file)) {
				echo GWF_HTML::err('ERR_FILE_NOT_FOUND', array( GWF_HTML::display($file)));
				return false;
			}
			
			
			
			if (false === $archive->addFile($file))
			{
				echo GWF_HTML::err('ERR_FILE_NOT_FOUND', array( GWF_HTML::display($file)));
				return false;
			}
		}
		return true;
	}
	
	private function zipDirs(GWF_ZipArchive $archive, array $paths, $recursive=true, $ignoreTemplates=true)
	{
		foreach ($paths as $path)
		{
			if (false === $this->zipDir($archive, $path, $recursive, $ignoreTemplates))
			{
				return false;
			}
		}
		return true;
	}
	
	private function zipDir(GWF_ZipArchive $archive, $path, $recursive=true, $ignoreTemplates=true)
	{
		if (!is_dir($path)) {
			GWF_Log::logCritical('Is not Dir: '.$path);
			return false;
		}
		
		if (false === ($dir = @dir($path))) {
			GWF_Log::logCritical('Can not read Dir: '.$path);
			return false;
		}
		
		while(false !== ($entry = $dir->read()))
		{
			if ($entry[0] === '.')
			{
				continue;
			}
			
			$fullpath = sprintf('%s/%s', $path, $entry);
			
			
			if (is_dir($fullpath))
			{
				# ignore some designs...
				if (!$ignoreTemplates && $this->isIgnored($fullpath)) {
					continue;
				}
				# recursion?
				if ($recursive) {
					if (false === ($this->zipDir($archive, $fullpath, $recursive, $ignoreTemplates))) {
						$dir->close();
						return false;
					}
				} else { # just skip dir
					continue;
				}
			}
			
			## TODO: REMOVE ME (Skip sensitive file)
			else if ($entry==='Convert.php') {
				continue;
			}
			
			else if ($this->isFileWanted($entry)) { # Add a file.
				
				if (false === $archive->addFile($fullpath)) {
					GWF_Log::logCritical('Can not add file: '.$fullpath);
					$dir->close();
					return false;
				}
			}
		}
		
		$dir->close();
		return true;
	}
	
	private function isFileWanted($entry)
	{
		if (Common::endsWith($entry, '.zip')) {
			return false;
		}
		if (Common::endsWith($entry, '.jar')) {
			return false;
		}
		return true;
	}
	
	private function isIgnored($fullpath)
	{
//		# ignored Modules
//		if ($this->isIgnoredModule($fullpath)) {
//			return true;
//		}
		
		# no template dir
		if (0 === preg_match('#tpl/([^/]+)$#', $fullpath, $matches)) {
			return false;
		}
		
		# wanted dir
		$t = $matches[1];
		if ($t === 'default' || in_array($t, $this->style, true) ) {
			return false;
		}
		
		# ignore this style
		return true;
	}
	
	private function isIgnoredModule($fullpath)
	{
//		var_dump($fullpath);
		return false;
	}
}

?>
