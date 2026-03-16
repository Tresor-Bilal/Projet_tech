<?php

// clé API Unsplash
$accessKey = '7oQvM8MBK3uzNSxw1FWEKeAtxfqn_YywjXnZM0dLKmc';

/**
 * Récupère une image aléatoire Unsplash selon une recherche
 * @param string $query
 * @param string $accessKey
 * @return string|null URL de l'image ou null si erreur
 */
function getUnsplashImageUrl($query, $accessKey) {
    $url = "https://api.unsplash.com/photos/random?query=" . urlencode($query) . "&client_id=" . $accessKey . "&orientation=landscape";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return null; // erreur réseau
    }
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['urls']['regular'] ?? null;
}
