<?php

require_once('../vendor/autoload.php');
require_once('../bootstrap.php');

// example usage:
$version = $_ENV['RANDOM_ORG_RPC_VERSION'];
$apiKey  = $_ENV['RANDOM_ORG_API_KEY'];

$client = new \RandomOrg\Client($version, $apiKey);

try {
    echo 'Password: ' . $client->getPassword(15) . '<br>';
} catch(\RandomOrg\RandomOrgException $e) {
    echo $e->getMessage();
}

try {
    echo 'UUID: ' . $client->getUUID();
} catch(\RandomOrg\RandomOrgException $e) {
    echo $e->getMessage();
}

?>