<?php
/**
 * Created by PhpStorm.
 * User: bosunski
 * Date: 27/08/2018
 * Time: 12:53 PM
 */

namespace AutoVh;

use Symfony\Component\Finder\Finder;
use Yosymfony\ResourceWatcher\ResourceWatcher;
use Yosymfony\ResourceWatcher\ResourceCacheFile;


class AutoVh {
	public function __construct() {
		$this->finder = new Finder();
		$this->cache = new ResourceCacheFile(ROOT_PATH . "/cache/.file-changes.php");
		$this->initialise();
	}

	public function watch(): void {
		$this->createVhost("");
		while (true) {
			$this->watcher->findChanges();

			if ($this->watcher->hasChanges()) {
				$newFolders = $this->watcher->getNewResources();

				foreach ($newFolders as $folder) {
					$name = basename($folder);
					$this->createVhost($name);
				}
			}

			usleep(10000);
		}
	}

	public function createVhost($dir): bool {
		print "Creating VHOST";

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