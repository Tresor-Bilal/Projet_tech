<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

require_once 'functions.php';

$eventbriteToken = 'TOFZBXFUQQXETDXG6SFH'; // Ta clé Eventbrite

// Récupérer les événements en vedette, fallback sur tableau vide si erreur
$featuredEvents = fetchEventbriteEvents($eventbriteToken) ?? [];
$featuredEvents = array_slice($featuredEvents, 0, 3);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>EventMatch – Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map { height: 400px; margin-bottom: 2rem; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-purple">
  <div class="container">
    <a class="navbar-brand" href="index.php">EventMatch</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
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

<section class="hero">
  <div class="overlay text-center text-white">
    <h1>Bienvenue sur EventMatch</h1>
    <p>Découvrez les événements qui vous passionnent près de chez vous.</p>
  </div>
</section>

<div class="container my-5">
  <h2 class="mb-4" style="color: var(--purple); font-weight: bold;">Événements en vedette</h2>
  
  <div class="row">
    <?php if (count($featuredEvents) === 0): ?>
      <p class="text-center">Aucun événement en vedette disponible pour le moment.</p>
    <?php else: ?>
      <?php foreach ($featuredEvents as $event): ?>
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
    <?php endif; ?>
  </div>
  
  <h2 class="mt-5 mb-4" style="color: var(--purple); font-weight: bold;">Carte des événements</h2>
  <div id="map"></div>
</div>

<footer class="text-center footer-purple">
  <p class="mb-0">© 2025 EventMatch. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
  // Initialisation de la carte
  const map = L.map('map').setView([46.603354, 1.888334], 6); // Centre sur France

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  // Ajouter un marqueur pour chaque événement avec coordonnées
  const events = <?= json_encode(array_filter($featuredEvents, function($e) {
    return isset($e['venue']['latitude'], $e['venue']['longitude']);
  })) ?>;

  events.forEach(event => {
    const lat = parseFloat(event.venue.latitude);
    const lng = parseFloat(event.venue.longitude);
    if (!isNaN(lat) && !isNaN(lng)) {
      const popupContent = `
        <strong>${event.name.text}</strong><br>
        ${event.venue.address.localized_address_display}<br>
        ${new Date(event.start.local).toLocaleString()}
      `;
      L.marker([lat, lng]).addTo(map).bindPopup(popupContent);
    }
  });
</script>

</body>
</html>