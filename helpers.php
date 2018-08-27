<?php

# This file is generated, changes you make will be lost.
# Make your changes in /usr/local/var/www/aeres/helpers.pre instead.

function startProcess($tag, $command, $log = null)
{
    $log = $log ?? "/dev/null";
    $output = "> {$log} 2> {$log}";

    exec("$command tag={$tag} {$output} &");
}

function identifyProcess($tag)
{
    exec("ps -ax | grep '[t]ag={$tag}'", $lines);
    $parts = explode(" ", trim($lines[0]));

    return (int) $parts[0];
}

function stopProcess($tag)
{
    $pid = identifyProcess($tag);

    if (!$pid) {
        return;
    }

    exec("kill -9 {$pid}");
}