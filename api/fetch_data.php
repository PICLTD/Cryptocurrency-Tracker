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

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST']; 
$baseUrl = $protocol . '://' . $host . '/api/fetch_all_symbols.php';
$apiUrl = $baseUrl;

$response = file_get_contents($apiUrl);

if ($response === FALSE) {
    echo json_encode(['error' => 'Error fetching symbols data.']);
    exit;
}

$symbolsData = json_decode($response, true);

if (!isset($symbolsData['Data']) || empty($symbolsData['Data'])) {
    echo json_encode(['error' => 'No symbols data found.']);
    exit;
}

$symbols = [];
foreach ($symbolsData['Data'] as $symbol) {
    $symbols[] = $symbol['Symbol'];
}

// Limit the number of symbols to avoid large API requests (adjust as necessary)
$symbols = array_slice($symbols, 0, 100); // Fetch data for first 100 symbols
$symbolList = implode(',', $symbols);
$priceApiUrl = "https://min-api.cryptocompare.com/data/pricemultifull?fsyms=$symbolList&tsyms=USD";
$priceResponse = file_get_contents($priceApiUrl);

if ($priceResponse === FALSE) {
    echo json_encode(['error' => 'Error fetching price data.']);
    exit;
}

$priceData = json_decode($priceResponse, true);

if (!isset($priceData['RAW']) || empty($priceData['RAW'])) {
    echo json_encode(['error' => 'No price data found.']);
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

$filteredData = [];
foreach ($symbolsData['Data'] as $symbol) {
    if (isset($priceData['RAW'][$symbol['Symbol']]['USD'])) {
        $crypto = $priceData['RAW'][$symbol['Symbol']]['USD'];
        $filteredData[] = [
            'Symbol' => $symbol['Symbol'],
            'Name' => $symbol['CoinName'],
            'Price' => $crypto['PRICE'],
            '24hChange' => $crypto['CHANGEPCT24HOUR'],
            'Volume' => $crypto['VOLUME24HOUR'],
            'MarketCap' => $crypto['MKTCAP'],
            'AssetWebsiteUrl' => isset($symbol['AssetWebsiteUrl']) ? $symbol['AssetWebsiteUrl'] : '', 
            'Description' => isset($symbol['Description']) ? $symbol['Description'] : '', 
			'ContentCreatedOn' => isset($symbol['ContentCreatedOn']) ? $symbol['ContentCreatedOn'] : '', 			
			'AssetLaunchDate' => isset($symbol['AssetLaunchDate']) ? $symbol['AssetLaunchDate'] : '', 
			'PlatformType' => isset($symbol['PlatformType']) ? $symbol['PlatformType'] : '', 		
            'ImageUrl' => "https://www.cryptocompare.com" . $symbol['ImageUrl'],
        ];
    }
}

usort($filteredData, function ($a, $b) {
    return $b['MarketCap'] <=> $a['MarketCap'];
});

if ($search) {
    $filteredData = array_filter($filteredData, function ($item) use ($search) {
        return strpos(strtolower($item['Name']), $search) !== false || strpos(strtolower($item['Symbol']), $search) !== false;
    });
}

$total = count($filteredData);
$offset = ($page - 1) * $limit;
$paginatedData = array_slice($filteredData, $offset, $limit);

echo json_encode([
    'data' => $paginatedData,
    'total' => $total,
    'page' => $page,
    'limit' => $limit,
]);
?>
