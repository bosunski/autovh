<?php

define("ROOT_PATH", __DIR__ . "/src");

require_once "vendor/autoload.php";
use AutoVh\AutoVh;
global $container;
$container = new \Illuminate\Container\Container();

$container->bind('autoVH', AutoVh::class);
$container->bind('application', \AutoVh\Foundation\Application::class);
$container->bind('config', \AutoVh\Config::class);
$container->bind('server', \AutoVh\Foundation\Server::class);
$container->bind(\Yosymfony\ResourceWatcher\ResourceCacheFile::class, function ($container) {
	return new \Yosymfony\ResourceWatcher\ResourceCacheFile(ROOT_PATH . "/cache/.file-changes.php");
});
$container->bind(\AutoVh\Config::class, function ($container) {
	return new \AutoVh\Config(require ROOT_PATH . "/settings.php");
});

$container->singleton(\Illuminate\Container\Container::class, function ($container) {
	return $container;
});

$autoVH = $container->make(AutoVh::class);
$autoVH->watch();
