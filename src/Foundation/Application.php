<?php

namespace AutoVh\Foundation;


use AutoVh\Contracts\ServerInterface;

class Application
{
	private $server;

	protected $container;

	public function __construct()
	{
		global $container;
		$this->container = $container;
	}

	public function getServer() : ServerInterface
	{
		return $this->server->getServer();
	}
}