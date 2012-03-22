<?php
/**
 * Error messages
 * @author spaceone
 */
final class GWF_Error
{
	private static $_all = array(
		'messages' => array(),
		'criticals' => array(),
		'errors' => array(),
		'warnings' => array(),
	);

	public static function decode($s) { return htmlspecialchars_decode($s, ENT_QUOTES); }

	/**
	 * shortpath and language
	 **/
	public static function err($key, $args=NULL) { self::error('GWF', GWF_Debug::shortpath(GWF_HTML::lang($key, $args))); }
	public static function err404($filename) { @header(Common::getProtocol().' 404 File not found'); self::err('ERR_FILE_NOT_FOUND', htmlspecialchars($filename)); }
	public static function error($title, $messages) { self::add('errors', $title, $messages); self::log_error($messages); }
	public static function message($title, $messages) { self::add('messages', $title, $messages); self::log_message($messages); }
	public static function warn($title, $messages) { self::add('criticals', $title, $messages); self::log_warning($messages); }
	public static function critical($title, $messages) { self::add('warnings', $title, $messages); self::log_critical($messages); }

	public static function log($s) { return self::decode(implode("\n", (array)$s)); }
	public static function log_error($content) { GWF_Log::logError(self::log($content)); }
	public static function log_message($content) { GWF_Log::logMessage(self::log($content)); }
	public static function log_warn($content) { GWF_Log::logWarning(self::log($content)); }
	public static function log_critical($content) { GWF_Log::logCritical(self::log($content)); }

	/** @deprecated */
//	public static function displayErrors()
//	{
//		$back = '';
//		$back .= self::display('criticals', 'error.tpl');
//		$back .= self::display('errors', 'error.tpl');
//		$back .= self::display('warnings', 'error.tpl');
//		return $back;
//	}

	/** @deprecated */
//	public static function displayMessages()
//	{
//		return self::display('messages', 'message.tpl');
//	}

	/**
	 * @param string $type criticals|errors|warnings|messages
	 * @param string $title
	 * @param string|array $messages
	 */
	private static function add($type, $title, $messages)
	{
		$messages = (array) $messages;

		if (0 === count($messages))
			return;

		if (true === isset(self::$_all[$type][$title]))
			self::$_all[$type][$title] = array_merge(self::$_all[$type][$title], $messages);
		else
			self::$_all[$type][$title] = $messages;
	}

	/** @deprecated */
//	private static function display($type, $tpl)
//	{
//		if (true === empty(self::$_all[$type]))
//		{
//			return '';
//		}
//		if (true === isset($_GET['ajax']))
//		{
//			return self::displayAjax(self::$_all[$type]);
//		}

//		return GWF_Template::templateMain($tpl, array('messages' => self::$_all[$type]));
//	}

	public static function displayAll()
	{
		foreach (self::$_all as $k => $subject)
		{
			if (true === empty($subject))
			{
				unset(self::$_all[$k]);
			}
		}

		if (true === isset($_GET['ajax']))
		{
			$back = '';
			foreach (self::$_all as $subject)
			{
				$back .= self::displayAjax($subject);
			}
			return $back;
		}

		return GWF_Template::templateMain('errors.tpl', array('messages' => self::$_all));
	}

	private static function displayAjax(&$subject)
	{
		$back = '';
		foreach ($subject as $messages)
		{
			foreach ($messages as $msg)
			{
				$m = GWF_Debug::shortpath(self::decode($msg));
				$back .= sprintf('0:%d:%s', strlen($m), $m).PHP_EOL;
			}
		}
		GWF_Website::addDefaultOutput($back);
		//return $back;
	}

}
