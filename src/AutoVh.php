<?php
/**
 * Created by PhpStorm.
 * User: bosunski
 * Date: 27/08/2018
 * Time: 12:53 PM
 */

namespace AutoVh;

use AutoVh\Parser\HostParser;
use Symfony\Component\Finder\Finder;
use Yosymfony\ResourceWatcher\ResourceWatcher;
use Yosymfony\ResourceWatcher\ResourceCacheFile;


class AutoVh {

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
	public function __construct() {
		$this->finder = new Finder();
		$this->cache = new ResourceCacheFile(ROOT_PATH . "/cache/.file-changes.php");
		$this->initialise();
	}

	public function watch(): void {
		// We can create folder lock and run check against it here
		// So that at boot it will run and see if a new folder has been added
		// $this->runChecks();
		while (true) {
			$this->watcher->findChanges();

			if ($this->watcher->hasChanges()) {
				// ToDo: Load Fresh configuration
				$newFolders = $this->watcher->getNewResources();

				foreach ($newFolders as $folder) {
					$name = basename($folder);
					// $this->createServerBlocks();
					// $this->enableServerBlocks();
					$this->createVhost($name);
				}
			}

			usleep(10000);
		}
	}

	public function createVhost($dir): bool {
		print "Creating VHOST";
		$ip = "127.0.0.1";
		$hostName = $dir;

		HostParser::set($hostName, $ip);
		HostParser::createHostFile("");

		return false;
	}

	public function initialise(): void {
		// Bootstraps Finder
		$this->finder = new Finder();
		$this->finder->directories()
		             ->name("*")
		             ->in(__DIR__ . "/../tests");

		$this->cache = new ResourceCacheFile(__DIR__ . "/.file-changes.php");

		$this->watcher = new ResourceWatcher($this->cache);
		$this->watcher->setFinder($this->finder);
	}
}