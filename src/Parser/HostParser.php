<?php
/**
 * Created by PhpStorm.
 * User: bosunski
 * Date: 27/08/2018
 * Time: 1:37 PM
 */

namespace AutoVh\Parser;


class HostParser {
	static $hosts = [];

	public static function set($key, $value)
	{
		self::$hosts[$key] = $value;
	}

	public static function get($key)
	{
		return self::$hosts[$key];
	}

	public static function loadHosts()
	{

		$hostFile = ROOT_PATH . "/tests/hosts";
		$file = fopen($hostFile, "r");
		$hosts = [];
		while (!feof($file)) {
			$line = fgets($file);
			if (substr($line, 0,1) !== "#") {
				$parts = preg_split('/\s+/', $line);
				$hosts[$parts[1]] = $parts[0];
			}
//			list($ip, $hostName) = explode("", fgets($file));
		}
		fclose($file);

		self::$hosts = $hosts;
	}

	public static function createHostFile($file)
	{
		foreach (self::$hosts as $hostName => $ip) {
			$lines .= $ip . "    " . $hostName . "\n";
		}
	}
}