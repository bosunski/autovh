<?php

define("ROOT_PATH", __DIR__);

/**
 * Created by PhpStorm.
 * User: bosunski
 * Date: 27/08/2018
 * Time: 12:25 PM
 */

require_once "vendor/autoload.php";
use AutoVh\AutoVh;

$autoVH = new AutoVh;
$autoVH->watch();
