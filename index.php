<?php
session_start();

$accessKey = '7oQvM8MBK3uzNSxw1FWEKeAtxfqn_YywjXnZM0dLKmc';

function getUnsplashImageUrl($query, $accessKey) {
    $url = "https://api.unsplash.com/photos/random?query=" . urlencode($query) . "&client_id=" . $accessKey . "&orientation=landscape";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    if (!$response) return null;
    $data = json_decode($response, true);
    return $data['urls']['regular'] ?? null;
}

$events = json_decode(file_get_contents(__DIR__ . '/data/events.json'), true);
$featuredEvents = array_slice($events, 0, 3);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EventMatch – Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <style>
    .hero {
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    .hero .overlay {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 40px;
      border-radius: 12px;
      color: white;
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 1.5rem;
    }

    .carousel-item img {
      max-height: 350px;
      object-fit: cover;
      width: 100%;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-purple">
  <div class="container">
    <a class="navbar-brand" href="index.php">EventMatch</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">Accueil</a></li>
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

<!-- HERO AVEC IMAGE PRINCIPALE -->
<section class="hero">
  <div class="overlay text-white text-center">
    <h1>Bienvenue sur EventMatch</h1>
    <p>Découvrez les meilleurs événements près de chez vous.</p>
  </div>
</section>

<!-- CARROUSEL ÉVÉNEMENTS EN VEDETTE -->
<div class="container my-5">
  <div id="featuredEventsCarousel" class="carousel slide shadow-sm rounded" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-inner">
      <?php foreach ($featuredEvents as $i => $event):
        $img = getUnsplashImageUrl($event['titre'], $accessKey) ?: 'img/fallback.jpg';
      ?>
      <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
        <img src="<?= htmlspecialchars($img) ?>" class="d-block w-100" alt="<?= htmlspecialchars($event['titre']) ?>" loading="lazy" />
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
          <h5><?= htmlspecialchars($event['titre']) ?></h5>
          <p><?= htmlspecialchars($event['lieu']) ?> — <?= htmlspecialchars($event['date']) ?></p>
          <a href="event-details.php?id=<?= urlencode($event['id']) ?>" class="btn btn-outline-light btn-sm">Voir détails</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#featuredEventsCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#featuredEventsCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Suivant</span>
    </button>
  </div>

  <!-- À PROPOS -->
  <section class="my-5">
    <h2 class="text-purple mb-3">À propos d'EventMatch</h2>
    <p>EventMatch est votre plateforme dédiée à la découverte et la participation à des événements uniques. Que vous aimiez la musique, la culture, le sport ou la gastronomie, trouvez l'événement qui vous correspond.</p>
    <p>Inscrivez-vous et profitez d’une expérience personnalisée avec vos événements favoris, des recommandations et bien plus encore.</p>
  </section>

  <div class="text-center mt-4">
    <a href="events.php" class="btn btn-purple btn-lg">Voir tous les événements</a>
  </div>
</div>

<footer class="text-center footer-purple py-3">
  <p class="mb-0">© 2025 EventMatch. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
