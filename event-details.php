<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

// Récupération des événements depuis JSON
$events = json_decode(file_get_contents(__DIR__ . '/data/events.json'), true);

$id = $_GET['id'] ?? null;
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

// Image Unsplash
$accessKey = '7oQvM8MBK3uzNSxw1FWEKeAtxfqn_YywjXnZM0dLKmc';
function getUnsplashImageUrl($query, $accessKey) {
    $url = "https://api.unsplash.com/photos/random?query=" . urlencode($query) . "&client_id=" . $accessKey . "&orientation=landscape";
    $opts = [
        "http" => ["method" => "GET", "header" => "Accept: application/json\r\n"],
        "ssl" => ["verify_peer" => false, "verify_peer_name" => false]
    ];
    $context = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);
    if (!$response) return null;
    $data = json_decode($response, true);
    return $data['urls']['regular'] ?? null;
}
$imageUrl = getUnsplashImageUrl($event['titre'], $accessKey) ?: 'img/fallback.jpg';

?>

<?php include 'includes/header.php'; ?>

<section class="hero small-hero" style="background-image: url('<?= htmlspecialchars($imageUrl) ?>'); background-size: cover; background-position: center;">
  <div class="overlay text-white d-flex flex-column justify-content-center align-items-center" style="min-height: 300px;">
    <h1 class="text-shadow"><?= htmlspecialchars($event['titre']) ?></h1>
    <p class="text-shadow fs-5"><?= htmlspecialchars($event['lieu']) ?> – <?= htmlspecialchars($event['date']) ?></p>
  </div>
</section>

<div class="container my-5">
  <div class="card shadow p-4">
    <h2 class="mb-4 text-purple"><?= htmlspecialchars($event['titre']) ?></h2>
    <p><strong>Date :</strong> <?= htmlspecialchars($event['date']) ?></p>
    <p><strong>Lieu :</strong> <?= htmlspecialchars($event['lieu']) ?></p>
    <p><strong>Description :</strong><br><?= nl2br(htmlspecialchars($event['description'])) ?></p>
    <a href="events.php" class="btn btn-outline-purple mt-4">Retour aux événements</a>
  </div>
</div>

<?php include 'includes/footer.php'; ?>