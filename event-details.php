<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

$eventbriteToken = 'TOFZBXFUQQXETDXG6SFH'; // ma clé

function fetchEventDetailsFromEventbrite($id, $token) {
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

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return null;
    }

    return json_decode($response, true);
}

// Vérifie la présence d’un ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: events.php');
    exit;
}

$eventId = $_GET['id'];
$event = fetchEventDetailsFromEventbrite($eventId, $eventbriteToken);

if (!$event || isset($event['error'])) {
    // Optionnel : afficher un message d’erreur au lieu de rediriger
    // echo "<p>Événement introuvable.</p>";
    header('Location: events.php');
    exit;
}

// Préparation des infos
$title = $event['name']['text'] ?? 'Titre indisponible';
$description = $event['description']['html'] ?? 'Aucune description.';
$date = isset($event['start']['local']) ? date('Y-m-d H:i', strtotime($event['start']['local'])) : 'Date inconnue';
$venue = $event['venue']['address']['localized_address_display'] ?? 'Lieu non spécifié';
$imageUrl = $event['logo']['url'] ?? 'img/fallback.jpg';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($title) ?> – EventMatch</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <style>.text-shadow { text-shadow: 2px 2px 8px rgba(0,0,0,0.7); }</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-purple">
  <div class="container">
    <a class="navbar-brand" href="index.php">EventMatch</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="events.php">Événements</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero avec image -->
<section class="hero small-hero" style="background-image: url('<?= htmlspecialchars($imageUrl) ?>'); background-size: cover; background-position: center;">
  <div class="overlay text-white d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
    <h1 class="text-shadow"><?= htmlspecialchars($title) ?></h1>
    <p class="text-shadow fs-5"><?= htmlspecialchars($venue) ?> – <?= htmlspecialchars($date) ?></p>
  </div>
</section>

<!-- Détails -->
<div class="container my-5">
  <div class="card shadow p-4">
    <h2 class="mb-4 text-purple"><?= htmlspecialchars($title) ?></h2>
    <p><strong>Date :</strong> <?= htmlspecialchars($date) ?></p>
    <p><strong>Lieu :</strong> <?= htmlspecialchars($venue) ?></p>
    <p><strong>Description :</strong></p>
    <div><?= $description ?></div>
    <a href="events.php" class="btn btn-outline-purple mt-4">Retour aux événements</a>
  </div>
</div>

<footer class="text-center footer-purple">
  <p class="mb-0">© 2025 EventMatch. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>