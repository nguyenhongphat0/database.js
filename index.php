<?php
    // error_reporting(E_DEPRECATED);
    $config = json_decode(file_get_contents('config.json'));
    $conn = new mysqli($config->host, $config->username, $config->password, $config->database);
    if ($conn->connect_error) {
        die('Connection to database '.$config->database.' with username '.$config->username.' failed!');
    }
    $conn->set_charset('utf8mb4');
    $sql = $_GET['sql'];
    $res = $conn->query($sql);
    $rows = [];
    while ($row = $res->fetch_assoc()) $rows[] = $row;
    die(json_encode($rows));