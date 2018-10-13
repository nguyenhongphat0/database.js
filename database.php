<?php
require_once "config.php";
$conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die('Connection to database '.DATABASE.' with username '.USERNAME.' failed!');
}
$conn->set_charset('utf8mb4');
$sql = $_GET['sql'];
$res = $conn->query($sql);
$rows = [];
while ($row = $res->fetch_assoc()) $rows[] = $row;
die(json_encode($rows));