<?php
require_once 'config.php';

function getEventbriteEvents($ville = 'Paris') {
    $token = EVENTBRITE_API_KEY;
    $url = "https://www.eventbriteapi.com/v3/events/search/?location.address=" . urlencode($ville) . "&token=$token";

    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "Accept: application/json\r\n"
        ]
    ];

    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);
    if (!$response) return [];

    $data = json_decode($response, true);
    return $data['events'] ?? [];
}
