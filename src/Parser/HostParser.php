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

//		$hostFile = ROOT_PATH . "/tests/hosts";
		$hostFile = "/etc/hosts";
		$file = fopen($hostFile, "r");
		$hosts = [];
		while (!feof($file)) {
			$line = fgets($file);
			if (substr($line, 0,1) !== "#" && $line !== "") {
//				list($ip, $hostName) = explode("\t", $line);
//				$hosts[$hostName] = $ip;
				$parts = preg_split('/[\s,]+/', $line, -1, PREG_SPLIT_NO_EMPTY);
				if (isset($parts[0])) {
					$hosts[$parts[1]] = $parts[0];
				}
			}
//			list($ip, $hostName) = explode("", fgets($file));
		}
		fclose($file);

		return $hosts;
	}

	public static function createHostFile($file)
	{
		$previousHosts = self::loadHosts();
		$newHosts = self::$hosts;
		self::$hosts = array_merge($previousHosts, $newHosts);
		$lines = "";
		foreach (self::$hosts as $hostName => $ip) {
			$lines .= $ip . "\t" . $hostName . "\n";
		}
//		file_put_contents(ROOT_PATH . "/tests/hosts", $lines);
		file_put_contents("/etc/hosts", $lines);
	}

	public static function addHost($hostName, $ip)
	{
		$line = PHP_EOL . "$ip\t$hostName" . PHP_EOL;
		return file_put_contents("/etc/hosts", $line, FILE_APPEND);
	}
}