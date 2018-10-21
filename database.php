<?php
require_once 'config.php';

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
if (!isset($_GET['sql'])) {
    die(json_encode([
        'code' => 200,
        'message' => 'Connection successfully!'
    ]));
}
$sql = $_GET['sql'];
$res = $conn->query($sql);
$rows = [];
while ($row = $res->fetch_assoc()) $rows[] = $row;
die(json_encode($rows));