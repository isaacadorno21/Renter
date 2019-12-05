<?php
$client = new MongoDB\Client(
    'mongodb+srv://uiucrenter:<uiucrenter>@uiucrenter-gimsv.mongodb.net/test?retryWrites=true&w=majority');

$db = $client->test;
$collection = $client->createCollection("mycol");
?>