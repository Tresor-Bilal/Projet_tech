<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('chatbot.html');

$accessKey = '7oQvM8MBK3uzNSxw1FWEKeAtxfqn_YywjXnZM0dLKmc';

function getUnsplashImageUrl($query, $accessKey) {

$url = "https://api.unsplash.com/photos/random?query=" .
urlencode($query) .
"&client_id=" .
$accessKey .
"&orientation=landscape";

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

<?php include 'includes/header.php'; ?>


<section class="hero">
<div class="overlay text-white text-center">

<h1>Bienvenue sur VIBZ</h1>
<p>Découvrez les meilleurs événements près de chez vous.</p>

</div>
</section>


<div class="container my-5">

<div id="featuredEventsCarousel" class="carousel slide shadow-sm rounded"
data-bs-ride="carousel"
data-bs-interval="5000">

<div class="carousel-inner">

<?php foreach ($featuredEvents as $i => $event):

$img = getUnsplashImageUrl($event['titre'], $accessKey) ?: 'img/fallback.jpg';

?>

<div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">

<img
src="<?= htmlspecialchars($img) ?>"
class="d-block w-100"
alt="<?= htmlspecialchars($event['titre']) ?>"
loading="lazy"
>

<div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">

<h5><?= htmlspecialchars($event['titre']) ?></h5>

<p>
<?= htmlspecialchars($event['lieu']) ?>
—
<?= htmlspecialchars($event['date']) ?>
</p>

<a
href="event-details.php?id=<?= urlencode($event['id']) ?>"
class="btn btn-outline-light btn-sm"
>
Voir détails
</a>

</div>
</div>

<?php endforeach; ?>

</div>

<button class="carousel-control-prev" type="button"
data-bs-target="#featuredEventsCarousel"
data-bs-slide="prev">

<span class="carousel-control-prev-icon"></span>

</button>

<button class="carousel-control-next" type="button"
data-bs-target="#featuredEventsCarousel"
data-bs-slide="next">

<span class="carousel-control-next-icon"></span>

</button>

</div>


<section class="my-5">

<h2 class="text-purple mb-3">À propos de VIBZ</h2>

<p>
VIBZ est votre plateforme dédiée à la découverte
et la participation à des événements uniques.
</p>

<p>
Que vous aimiez la musique,
la culture,
le sport
ou la gastronomie,
trouvez l'événement qui vous correspond.
</p>

</section>


<div class="text-center mt-4">

<a href="events.php" class="btn btn-purple btn-lg">
Voir tous les événements
</a>

</div>

</div>



<div class="container my-5">

<h2 class="text-center text-purple mb-4">
Carte des événements
</h2>

<div id="map"></div>

</div>


<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

const map = L.map('map').setView([46.6031,1.8883],6);

L.tileLayer(
'https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png',
{
attribution:'© OpenStreetMap',
minZoom:4,
maxZoom:18
}
).addTo(map);

const events = <?= json_encode($events) ?>;

events.forEach(event => {

if(event.lat && event.lng){

const marker = L.marker([event.lat,event.lng]).addTo(map);

marker.bindPopup(
`<strong>${event.titre}</strong><br>${event.lieu} — ${event.date}`
);

}

});

</script>


<?php include 'includes/footer.php'; ?>