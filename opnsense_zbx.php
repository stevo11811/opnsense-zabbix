<?php

// Fetch API output
$ch = curl_init();

// Ensure an argument is passed to specify the endpoint
if (empty($argv[3])) {
    die("Please specify the API endpoint as the first argument.\n");
}

// Init Args
$folder = $argv[3];
$api = $argv[4];
$endpoint = $argv[5];

// API Runs locally, SSL Verification bypassed as the SSL cert will never match, can be modified if needed.
// First two arguments of input are the API KEYS used for auth which is input into Zabbix.
curl_setopt($ch, CURLOPT_URL, "https://127.0.0.1/api/{$folder}/{$api}/{$endpoint}");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, "$argv[1]:$argv[2]");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
    exit(1);
}
curl_close($ch);

// Parse JSON
$data = json_decode($result, true);

// Remove the first six elements from the $argv array
for ($i = 0; $i < 6; $i++) {
    array_shift($argv);
}

// If there's just one argument left, treat it as a key.
foreach ($argv as $key) {
//    echo "Attempting to access key: {$key}\n";
    if (is_array($data) && isset($data[$key])) {
        $data = $data[$key];
  //      echo "Current data: " . json_encode($data) . "\n";
    } else {
        echo "Error: Key {$key} not found";
        exit(1);
    }
}
// Print the final value
if (is_array($data)) {
    echo json_encode($data, JSON_PRETTY_PRINT);
} else {
    echo $data;
}

?>
