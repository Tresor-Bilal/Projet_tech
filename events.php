<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
$_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
header('Location: login.php');
exit;
}

$accessKey='7oQvM8MBK3uzNSxw1FWEKeAtxfqn_YywjXnZM0dLKmc';

function getUnsplashImageUrl($query,$accessKey){

$url="https://api.unsplash.com/photos/random?query=".urlencode($query)."&client_id=".$accessKey;

$response=@file_get_contents($url);

if(!$response) return null;

$data=json_decode($response,true);

return $data['urls']['regular'] ?? null;
}

$events=json_decode(file_get_contents(__DIR__.'/data/events.json'),true);

$search=$_GET['ville'] ?? '';

if($search!=''){
$events=array_filter($events,function($e) use($search){
return stripos($e['lieu'],$search)!==false;
});
}
?>

<?php include 'includes/header.php'; ?>

<section class="hero small-hero">
<div class="overlay text-white text-center">
<h1>Nos événements</h1>
<p>Explorez les événements près de vous</p>
</div>
</section>

<div class="container my-5">

<form method="get" class="mb-4">
<div class="input-group">
<input type="text" name="ville" class="form-control" placeholder="Rechercher une ville..." value="<?= htmlspecialchars($search) ?>">
<button class="btn btn-purple">Rechercher</button>
</div>
</form>

<div class="row">

<?php foreach($events as $event): ?>

<?php
$image=getUnsplashImageUrl($event['titre'],$accessKey) ?: 'img/fallback.jpg';
?>

<div class="col-md-4 mb-4">

<div class="card h-100 shadow">

<img src="<?= htmlspecialchars($image) ?>" class="card-img-top" style="height:200px;object-fit:cover">

<div class="card-body d-flex flex-column">

<h5><?= htmlspecialchars($event['titre']) ?></h5>

<p><strong>Lieu:</strong> <?= htmlspecialchars($event['lieu']) ?></p>

<p><strong>Date:</strong> <?= htmlspecialchars($event['date']) ?></p>

<p class="flex-grow-1"><?= htmlspecialchars($event['description']) ?></p>

<a href="event-details.php?id=<?= $event['id'] ?>" class="btn btn-outline-purple mt-auto">
Voir détails
</a>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

</div>

<?php include 'includes/footer.php'; ?>