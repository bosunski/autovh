<?php


namespace AutoVh\Foundation;


use AutoVh\Config;
use AutoVh\Contracts\ServerInterface;

class NginxServer extends Server implements ServerInterface
{
	public function __construct(Config $config)
	{
		parent::__construct($config);
	}

	/**
	 * Generates appropriate Virtual Host File
	 *
	 * @param $path
	 *
	 * @return NginxServer
	 */
	public function createVirtualHostFile($path): self
	{
		$buffer = file_get_contents(ROOT_PATH . "/stubs/nginx.conf");

		$publicFolder = $this->hasPublicFolder($path);
		$buffer = str_replace("{@root_dir}", $publicFolder, $buffer);
		$serverName = $this->getServerNameFromPath($path);
		$buffer = str_replace("{@server_name}", $serverName, $buffer);

		$destination = $this->config->getConfig('serverDirectory') . "/sites-available/" . $serverName;

		file_put_contents($destination, $buffer);

		return $this;
	}

	/**
	 * Restarts the Server
	 */
	public function restartServer(): self
	{
		exec("nginx -s reload");

		return $this;
	}

	/**
	 * Enables virtual host File
	 *
	 * @param $path
	 *
	 * @return NginxServer
	 */
	public function enableVirtualHost($path): NginxServer
	{
		$serverName = $this->getServerNameFromPath($path);
		$serverDirectory = $this->config->getConfig('serverDirectory');
		exec("ln -s $serverDirectory/sites-available/$serverName $serverDirectory/sites-enabled/", $output, $returnCode);

		return $this;
	}
}