<?php
// Démarrer la session si elle n’est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chemin de base (utile pour générer des liens dynamiques si besoin)
define('BASE_URL', '/evenmatch'); // modifie selon ton chemin réel

// Clé API Eventbrite (à garder secrète)
define('EVENTBRITE_API_KEY', 'TOFZBXFUQQXETDXG6SFH');
?>
