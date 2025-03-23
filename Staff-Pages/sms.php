<?php
function sendSMSNotification($phone) {
    $api_key = getenv('SEMAPHORE_API_KEY');
    $message = "Your ByGems payment is overdue. Settle it now.";
    $sender_name = "ByGems";

    $url = "https://semaphore.co/api/v4/messages";
    $params = [
        'apikey' => $api_key,
        'number' => $phone,
        'message' => $message,
        'sendername' => $sender_name
    ];

    file_get_contents($url . "?" . http_build_query($params));
}
?>