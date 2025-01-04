<?php

$databases = [
    __DIR__ . '/../chinook.sqlite',
    __DIR__ . '/../northwind.db',
];

// Next goes the actual code. Do not modify anything below this line.
// ==================================================================


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    $request = json_decode(file_get_contents('php://input'), true);

    try {
        switch ($request['type']) {
            case 'execute':
                $query = $request['query'];
                $db = new PDO('sqlite:' . $databases[$request['database']]);
                $result = $db->query($query);
                $response['rows'] = $result->fetchAll(PDO::FETCH_ASSOC);
                break;

            case 'list':
                $response['databases'] = array_map(function ($db) {
                    return basename($db);
                }, $databases);
                break;
        }
    } catch (PDOException $e) {
        $response['error'] = $e->getMessage();
    }

    die(json_encode($response, JSON_PRETTY_PRINT));
}
