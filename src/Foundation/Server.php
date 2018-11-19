<?php

namespace AutoVh\Foundation;

use AutoVh\Config;
use AutoVh\Contracts\ServerInterface;
use AutoVh\Parser\HostParser;

class Server extends Application
{
	protected $config;


	public function __construct(Config $config)
	{
		$this->config = $config;
		parent::__construct();
	}

	public function getServerNameFromPath($path): string
	{
		return basename($path) . "." . $this->config->getConfig('extension');
	}

	protected function appendHost($dir): self
	{
		print "Creating VHOST" . PHP_EOL;

		$ip = $this->config->getConfig("ip");

		$hostName = $this->getServerNameFromPath($dir);

		HostParser::addHost($hostName, $ip);
		return $this;

	}

	public function getServer(): ServerInterface
	{
		$name = __NAMESPACE__  . '\\' . ucfirst($this->config->getConfig('serverName')) . "Server";

		return $this->container->make($name);
	}

	public function respondToChanges($newFolders)
	{
		$server = $this->getServer();

		foreach ($newFolders as $folder) {
			$folder = str_slug($folder);
			$this->appendHost($folder);

			$server->enableVirtualHost($folder)
					->createVirtualHostFile($folder)
					->restartServer();
		}
	}

	protected function hasPublicFolder($path)
	{
		$publicFolder = $path . '/public';
		if (is_dir($publicFolder)) {
			return $publicFolder;
		}

		return $path;
	}
}