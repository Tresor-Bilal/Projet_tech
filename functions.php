<?php
// functions.php

/**
 * Récupère une liste d'événements depuis Eventbrite
 * @param string $token Clé API Eventbrite valide
 * @param string $location (optionnel) Adresse / ville pour filtrer
 * @return array Tableau d'événements ou tableau vide en cas d'erreur
 */
function fetchEventbriteEvents(string $token, string $location = ''): array {
    $url = 'https://www.eventbriteapi.com/v3/events/search/?expand=venue,logo&sort_by=date';

    if (!empty($location)) {
        $url .= '&location.address=' . urlencode($location);
    }

    $headers = [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return [];
    }
    curl_close($ch);

    $data = json_decode($response, true);

    if (!isset($data['events']) || !is_array($data['events'])) {
        return [];
    }

    return $data['events'];
}

/**
 * Récupère les détails d'un événement par ID
 * @param string $id ID de l'événement
 * @param string $token Clé API Eventbrite
 * @return array|null Détails de l'événement ou null en cas d'erreur
 */
function fetchEventDetailsFromEventbrite(string $id, string $token): ?array {
    $url = 'https://www.eventbriteapi.com/v3/events/' . urlencode($id) . '/?expand=venue,logo';

    $headers = [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return null;
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (isset($data['error'])) {
        return null;
    }

    return $data;
}
?>