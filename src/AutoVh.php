<?php

namespace AutoVh;

use AutoVh\Foundation\Application;
use AutoVh\Foundation\Server;
use Symfony\Component\Finder\Finder;
use Yosymfony\ResourceWatcher\ResourceWatcher;
use Yosymfony\ResourceWatcher\ResourceCacheFile;

/**
 * Class AutoVh
 * @package AutoVh
 *  ToDo: Add Server Interfaces to take care of how Implementations are done for different servers
 */

class AutoVh
{
	/**
	 * Watcher Object
	 * @var ResourceWatcher
	 */
	protected $watcher;

	/**
	 * Symfony finder Object
	 * @var Finder
	 */
	protected $finder;

	/**
	 * Cache Object
	 * @var ResourceCacheFile
	 */
	protected $cache;

	/**
	 * Extension for the Hostname
	 * @var string
	 */
	protected $extension = "test";

	private $app;

	public function __construct(Server $server, Finder $finder, ResourceCacheFile $cache)
	{
		$this->server =  $server;
		$this->finder = $finder;
		$this->cache = $cache;
	}

	public function watch(): void
	{
		$this->initialise();
		// We can create folder lock and run check against it here
		// So that at boot it will run and see if a new folder has been added
		// $this->runChecks();
		while (true) {
			// toDO: Take note of error thrown when MTime cannot be gotten e.g, if i use mkdir to make a folder
			$this->watcher->findChanges();

			if ($this->watcher->hasChanges()) {
				// ToDo: Load Fresh configuration
				$newFolders = $this->watcher->getNewResources();
				$this->server->respondToChanges($newFolders);
			}
			usleep(10000);
		}
	}


	public function initialise(): void
	{
		$this->finder = new Finder();
		$this->finder->directories()
		             ->name("*")
		             ->depth(0)
		             ->in("/usr/local/var/www");

		$this->cache = new ResourceCacheFile(__DIR__ . "/.file-changes.php");

		$this->watcher = new ResourceWatcher($this->cache);
		$this->watcher->setFinder($this->finder);
	}
}