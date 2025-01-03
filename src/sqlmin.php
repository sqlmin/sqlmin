<?php

$database = __DIR__ . '/../chinook.sqlite';

// Next goes the actual code. Do not modify anything below this line.
// ==================================================================

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request = json_decode(file_get_contents('php://input'), true);
    $query = $request['query'];
    $db = new PDO('sqlite:' . $database);
    $result = $db->query($query);
    $response['data'] = $result->fetchAll(PDO::FETCH_ASSOC);
    $response['status'] = 'success';
    die(json_encode($response, JSON_PRETTY_PRINT));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request = json_decode(file_get_contents('php://input'), true);

    die(json_encode($response, JSON_PRETTY_PRINT));
}
