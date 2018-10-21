<?php
require_once 'config.php';

$_POST = json_decode(file_get_contents('php://input'), true);
header('Content-Type: application/json');
set_error_handler(function($errno, $errstr, $errfile, $errline ) {
    die(json_encode([
        'code' => $errno,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ]));
});

$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE, PORT);
$conn->set_charset('utf8mb4');
if (!isset($_POST['sql'])) {
    die(json_encode([
        'code' => 200,
        'message' => 'Connection successfully!'
    ]));
}
$sql = $_POST['sql'];
$res = $conn->query($sql);
if (is_bool($res)) {
    if ($res) {
        die(json_encode([
            'code' => 200,
            'message' => 'Query executed successfully',
            'affected_rows' => $conn->affected_rows
        ]));
    } else {
        die(json_encode([
            'code' => 500,
            'message' => $conn->error
        ]));
    }
} else {
    $rows = [];
    while ($row = $res->fetch_assoc()) $rows[] = $row;
    die(json_encode($rows));
}
$conn->close();