<?php
if (session_status()==PHP_SESSION_NONE)
session_start();

if (!isset($_SESSION['user_id'])) {
    // On mémorise la page demandée dans la session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

$accessKey = '7oQvM8MBK3uzNSxw1FWEKeAtxfqn_YywjXnZM0dLKmc';


// Fonction pour récupérer une image depuis Unsplash
function getUnsplashImageUrl($query, $accessKey) {
    $url = "https://api.unsplash.com/photos/random?query=" . urlencode($query) . "&client_id=" . $accessKey . "&orientation=landscape";

    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "Accept: application/json\r\n"
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false
        ]
    ];

    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);

    if (!$response) return null;

    $data = json_decode($response, true);
    return $data['urls']['regular'] ?? null;
}

// Vérifie la présence d'un ID dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: events.php');
    exit;
}

$id = $_GET['id'];

// Récupère les événements
$events = json_decode(file_get_contents(__DIR__ . '/data/events.json'), true);

// Trouve l’événement par ID
$event = null;
foreach ($events as $e) {
    if ((string)$e['id'] === (string)$id) {
        $event = $e;
        break;
    }
}

if (!$event) {
    header('Location: events.php');
    exit;
}

// Récupération de l'image
$imageUrl = getUnsplashImageUrl($event['titre'], $accessKey) ?: 'img/fallback.jpg';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($event['titre']) ?> – EventMatch</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .text-shadow { text-shadow: 2px 2px 8px rgba(0,0,0,0.7); }
  </style>
</head>
<body> 

<?php session_start(); ?>
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


<!-- Hero avec image Unsplash -->
<section class="hero small-hero" style="background-image: url('<?= htmlspecialchars($imageUrl) ?>'); background-size: cover; background-position: center;">
  <div class="overlay text-white d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
    <h1 class="text-shadow"><?= htmlspecialchars($event['titre']) ?></h1>
    <p class="text-shadow fs-5"><?= htmlspecialchars($event['lieu']) ?> – <?= htmlspecialchars($event['date']) ?></p>
  </div>
</section>

<!-- Détails de l’événement -->
<div class="container my-5">
  <div class="card shadow p-4">
    <h2 class="mb-4 text-purple"><?= htmlspecialchars($event['titre']) ?></h2>
    <p><strong>Date :</strong> <?= htmlspecialchars($event['date']) ?></p>
    <p><strong>Lieu :</strong> <?= htmlspecialchars($event['lieu']) ?></p>
    <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($event['description'])) ?></p>
    <a href="events.php" class="btn btn-outline-purple mt-4">Retour aux événements</a>
  </div>
</div>

<footer class="text-center footer-purple">
  <p class="mb-0">© 2025 EventMatch. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
