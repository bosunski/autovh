<?php

namespace AutoVh\Contracts;

interface ServerInterface
{
	public function createVirtualHostFile($path);
	public function restartServer();
	public function enableVirtualHost($path);
}