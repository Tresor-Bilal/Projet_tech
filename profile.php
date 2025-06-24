<?php
require_once 'db.php';
if (session_status()==PHP_SESSION_NONE)
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$success = '';
$error = '';

// Récupérer les infos utilisateur actuelles
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    // Si utilisateur introuvable, déconnexion
    session_destroy();
    header('Location: login.php');
    exit;
}

// Traitement de la mise à jour des infos utilisateur (hors mot de passe)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($prenom && $nom && $email) {
        // Vérifier que l'email est unique sauf pour cet utilisateur
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $update = $pdo->prepare("UPDATE utilisateurs SET prenom = ?, nom = ?, email = ?, phone = ? WHERE id = ?");
            $update->execute([$prenom, $nom, $email, $phone, $userId]);
            $success = "Profil mis à jour avec succès.";
            // Recharger les données mises à jour
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
        }
    } else {
        $error = "Veuillez remplir les champs obligatoires.";
    }
}

// Traitement du changement de mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $ancien = $_POST['ancien_mot_de_passe'] ?? '';
    $nouveau = $_POST['nouveau_mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation_mot_de_passe'] ?? '';

    if (empty($ancien) || empty($nouveau) || empty($confirmation)) {
        $error = "Veuillez remplir tous les champs du changement de mot de passe.";
    } elseif ($nouveau !== $confirmation) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        if (password_verify($ancien, $user['mot_de_passe'])) {
            $nouveauHash = password_hash($nouveau, PASSWORD_DEFAULT);
            $updatePwd = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
            $updatePwd->execute([$nouveauHash, $userId]);
            $success = "Mot de passe mis à jour avec succès.";
        } else {
            $error = "Ancien mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Profil – EventMatch</title>
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
        <li class="nav-item"><a class="nav-link" href="profile.php">Profil</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-5">

  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <h2 class="mb-4 text-purple">Mes informations</h2>
  <form method="post" class="mb-5 bg-white p-4 rounded shadow" autocomplete="off">
    <input type="hidden" name="update_profile" value="1" />
    <div class="mb-3">
      <label for="prenom" class="form-label">Prénom</label>
      <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required />
    </div>
    <div class="mb-3">
      <label for="nom" class="form-label">Nom</label>
      <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required />
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">E-mail</label>
      <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required />
    </div>
    <div class="mb-3">
      <label for="phone" class="form-label">Téléphone</label>
      <input type="tel" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" />
    </div>
    <button type="submit" class="btn btn-purple">Mettre à jour</button>
  </form>

  <h2 class="mb-4 text-purple">Changer mon mot de passe</h2>
  <form method="post" class="bg-white p-4 rounded shadow" autocomplete="off">
    <input type="hidden" name="change_password" value="1" />
    <div class="mb-3">
      <label for="ancien_mot_de_passe" class="form-label">Ancien mot de passe</label>
      <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="nouveau_mot_de_passe" class="form-label">Nouveau mot de passe</label>
      <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" class="form-control" required />
    </div>
    <div class="mb-3">
      <label for="confirmation_mot_de_passe" class="form-label">Confirmer le nouveau mot de passe</label>
      <input type="password" id="confirmation_mot_de_passe" name="confirmation_mot_de_passe" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-purple">Mettre à jour le mot de passe</button>
  </form>
</div>

<footer class="text-center footer-purple">
  <p class="mb-0">© 2025 EventMatch. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
