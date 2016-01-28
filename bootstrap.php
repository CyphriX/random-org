<?php

$baseDir = __DIR__;
$vendorDir = $baseDir . '/vendor';

// prepare environment
$dotEnv = new \Dotenv\Dotenv($baseDir);
$dotEnv->load();

