<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>EventMatch</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="CSS/style.css">

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

<?php if ($currentPage != 'index.php'): ?>
<li class="nav-item">
<a class="nav-link" href="index.php">Accueil</a>
</li>
<?php endif; ?>

<?php if ($currentPage != 'events.php'): ?>
<li class="nav-item">
<a class="nav-link" href="events.php">Événements</a>
</li>
<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>

<?php if ($currentPage != 'profile.php'): ?>
<li class="nav-item">
<a class="nav-link" href="profile.php">Profil</a>
</li>
<?php endif; ?>

<li class="nav-item">
<a class="nav-link" href="logout.php">Déconnexion</a>
</li>

<?php else: ?>

<?php if ($currentPage != 'login.php'): ?>
<li class="nav-item">
<a class="nav-link" href="login.php">Connexion</a>
</li>
<?php endif; ?>

<?php if ($currentPage != 'register.php'): ?>
<li class="nav-item">
<a class="nav-link" href="register.php">Inscription</a>
</li>
<?php endif; ?>

<?php endif; ?>

</ul>

</div>
</div>
</nav>