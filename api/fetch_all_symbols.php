<?php

/**
 * Cryptocurrency Tracker
 * 
 * A PHP application that utilizes the CryptoCompare API to fetch and display real-time cryptocurrency data.
 * This project features a responsive design built with Bootstrap, including functionalities like pagination,
 * search, and automatic price updates.
 * 
 * Created by Amin Amiri
 * Email: amin.shahmirani@gmail.com
 */

header('Content-Type: application/json');

$apiUrl = "https://min-api.cryptocompare.com/data/all/coinlist";
$response = file_get_contents($apiUrl);

if ($response === FALSE) {
    echo json_encode(['error' => 'Error fetching data.']);
    exit;
}

$data = json_decode($response, true);

if (!isset($data['Data']) || empty($data['Data'])) {
    echo json_encode(['error' => 'No symbols data found.']);
    exit;
}

echo json_encode($data);
?>
