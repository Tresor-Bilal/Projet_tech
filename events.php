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



// Fonction pour obtenir une image aléatoire depuis Unsplash en fonction du titre
function getUnsplashImageUrl($query, $accessKey) {
    $url = "https://api.unsplash.com/photos/random?query=" . urlencode($query) . "&client_id=" . $accessKey . "&orientation=landscape";

    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "Accept: application/json\r\n"
        ],
        "ssl" => [
            "verify_peer" => false,
            "verify_peer_name" => false,
        ],
    ];

    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);

    if (!$response) return null;

    $data = json_decode($response, true);
    return $data['urls']['regular'] ?? null;
}

// Charger les événements depuis le JSON
$events = json_decode(file_get_contents(__DIR__ . '/data/events.json'), true);

// Filtrer les événements si une ville est précisée
$search = isset($_GET['ville']) ? strtolower(trim($_GET['ville'])) : '';
if ($search !== '') {
    $events = array_filter($events, function ($event) use ($search) {
        return strpos(strtolower($event['lieu']), $search) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EventMatch – Événements</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
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

    </div>
  </div>
</nav>

<section class="hero small-hero">
  <div class="overlay text-center text-white">
    <h1>Nos événements</h1>
    <p>Inspirez-vous, explorez et participez à des moments inoubliables.</p>
  </div>
</section>

<div class="container my-5">
  <!-- Barre de recherche -->
  <form method="get" class="mb-4">
    <div class="input-group">
      <input type="text" name="ville" class="form-control" placeholder="Rechercher une ville..." value="<?= htmlspecialchars($search) ?>" />
      <button class="btn btn-purple" type="submit">Rechercher</button>
    </div>
  </form>

  <!-- Résultats -->
  <div class="row">
    <?php if (!empty($events)): ?>
      <?php foreach ($events as $event): ?>
        <?php
          $imageUrl = getUnsplashImageUrl($event['titre'], $accessKey);
          if (!$imageUrl) $imageUrl = 'img/fallback.jpg';
        ?>
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm h-100">
            <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($event['titre']) ?>" class="card-img-top" style="object-fit: cover; height: 200px;" />
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($event['titre']) ?></h5>
              <p class="card-text"><strong>Lieu :</strong> <?= htmlspecialchars($event['lieu']) ?></p>
              <p class="card-text"><strong>Date :</strong> <?= htmlspecialchars($event['date']) ?></p>
              <p class="card-text flex-grow-1"><?= htmlspecialchars($event['description']) ?></p>
              <a href="event-details.php?id=<?= urlencode($event['id']) ?>" class="btn btn-outline-purple mt-auto">Voir détails</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center">Aucun événement trouvé pour cette ville.</p>
    <?php endif; ?>
  </div>
</div>

<footer class="text-center footer-purple">
  <p class="mb-0">© 2025 EventMatch. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
