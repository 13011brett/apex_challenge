<?php
require_once('Whitelist.php');

$whitelist = new Whitelist();

$user = $whitelist->getUserDetails();
print_r($user);
$whitelist->addUserToWhitelist($user);