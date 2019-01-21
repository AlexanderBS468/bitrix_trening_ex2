<?php
namespace Techdir\PhpInterface\Diag;

class Debug
{
	/**
	 * Функция в удобном виде выводит:
	 * 1. Информацию о скрипте, из которого она была вызвана
	 * 2. Содержимое переменной, которая передана первым параметром
	 *
	 * При запуске скрипта из cli выводится полный путь до файла и print_r переменной
	 *
	 * @param      $obj                array
	 * @param bool $visibleForEveryone показывать ли блок всем посетителям сайта (по-умолчанию блок видят только
	 *                                 администраторы сайта)
	 *
	 * @return bool возвращает false, если сообщение не было показано (если пользователь не администратор)
	 * */
	public static function pr($obj, $visibleForEveryone = false)
	{
		static $isAdmin = null;

		if (version_compare(PHP_VERSION, '5.4.0') >= 0)
		{
			$trace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT & DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		}
		else
		{
			$trace = debug_backtrace();
		}

		$trace = $trace[1];

		if (PHP_SAPI == 'cli')
		{
			echo $trace['file'] . ':' . $trace['line'] . PHP_EOL;
			print_r($obj);

			return true;
		}

		$trace['file'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $trace['file']);

		$trace['file_path'] = dirname($trace['file']);
		$trace['file_name'] = pathinfo($trace['file'], PATHINFO_BASENAME);

		if (is_null($isAdmin))
		{
			$isAdmin = $GLOBALS['USER']->IsAdmin();
		}

		if (!$isAdmin and !$visibleForEveryone)
		{
			return false;
		}

		echo '<pre style="font:normal 10pt/12pt monospace;background:#fff;color:#000;margin:10px;padding:10px;border:1px solid red;text-align:left;max-width:800px;max-height:600px;overflow:scroll">';
		echo '<a style="font:normal 10pt/12pt monospace;color:#00e;text-decoration:underline" href="/bitrix/admin/fileman_admin.php?path='
			. rawurlencode($trace['file_path']) . '" target="_blank">' . $trace['file_path'] . '</a>/';
		echo '<a style="font:normal 10pt/12pt monospace;color:#60d;text-decoration:underline" href="/bitrix/admin/fileman_file_edit.php?path='
			. rawurlencode($trace['file']) . '&full_src=Y" target="_blank">' . $trace['file_name'] . '</a>:'
			. $trace['line'] . '<br />';
		echo htmlspecialcharsEx(print_r($obj, true));
		echo '</pre>';

		return true;
	}
}
