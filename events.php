<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

require_once 'functions.php';

$eventbriteToken = 'TOFZBXFUQQXETDXG6SFH';
$searchCity = isset($_GET['ville']) ? trim($_GET['ville']) : '';

$events = fetchEventbriteEvents($eventbriteToken, $searchCity);
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
        <li class="nav-item"><a class="nav-link active" href="events.php">Événements</a></li>
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

<section class="hero small-hero">
  <div class="overlay text-center text-white">
    <h1>Nos événements</h1>
    <p>Inspirez-vous, explorez et participez à des moments inoubliables.</p>
  </div>
</section>

<div class="container my-5">
  <form method="get" class="mb-4">
    <div class="input-group">
      <input type="text" name="ville" class="form-control" placeholder="Rechercher une ville..." value="<?= htmlspecialchars($searchCity) ?>" />
      <button class="btn btn-purple" type="submit">Rechercher</button>
    </div>
  </form>

  <div class="row" id="eventsContainer">
    <?php if (!empty($events)): ?>
      <?php foreach ($events as $event): ?>
        <?php
          $title = $event['name']['text'] ?? 'Titre indisponible';
          $date = isset($event['start']['local']) ? date('Y-m-d H:i', strtotime($event['start']['local'])) : 'Date non spécifiée';
          $venue = $event['venue']['address']['localized_address_display'] ?? 'Lieu non spécifié';
          $imageUrl = $event['logo']['url'] ?? 'img/fallback.jpg';
        ?>
        <div class="col-md-4 mb-4">
          <div class="card shadow-sm h-100">
            <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($title) ?>" class="card-img-top" style="object-fit: cover; height: 200px;" />
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($title) ?></h5>
              <p class="card-text"><strong>Lieu :</strong> <?= htmlspecialchars($venue) ?></p>
              <p class="card-text"><strong>Date :</strong> <?= htmlspecialchars($date) ?></p>
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