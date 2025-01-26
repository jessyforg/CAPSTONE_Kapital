<?php

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;

$factory = (new Factory)
    ->withServiceAccount('kapital-a798a-firebase-adminsdk-fbsvc-f02430e5fa.json')
    ->withDatabaseUri('https://kapital-a798a-default-rtdb.asia-southeast1.firebasedatabase.app/');

$database = $factory->createDatabase();
?>