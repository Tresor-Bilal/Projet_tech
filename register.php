<?php
require_once 'db.php'; // fichier de connexion à la base de données

$nom = $prenom = $email = $date = $password = $sexe = $phone = '';
$errors = [
  'nom' => '', 'prenom' => '', 'email' => '', 'date' => '',
  'password' => '', 'sexe' => '', 'phone' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date = trim($_POST['date']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $sexe = $_POST['sexe'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $valid = true;

    if (empty($nom)) {
        $errors['nom'] = "Veuillez saisir votre nom.";
        $valid = false;
    }
    if (empty($prenom)) {
        $errors['prenom'] = "Veuillez saisir votre prénom.";
        $valid = false;
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Adresse e-mail invalide.";
        $valid = false;
    }
    if (empty($password)) {
        $errors['password'] = "Veuillez saisir un mot de passe.";
        $valid = false;
    }
    if (empty($date)) {
        $errors['date'] = "Veuillez saisir votre date de naissance.";
        $valid = false;
    }
    if (empty($sexe)) {
        $errors['sexe'] = "Veuillez choisir votre sexe.";
        $valid = false;
    }
    if (empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = "Numéro de téléphone invalide (10 chiffres requis).";
        $valid = false;
    }

    if ($valid) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors['email'] = "Cette adresse e-mail est déjà utilisée.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            try {
                $insert = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, date_naissance, mot_de_passe, civilite, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $insert->execute([$nom, $prenom, $email, $date, $hashedPassword, $sexe, $phone]);
                header("Location: login.php?inscription=success");
                exit;
            } catch (PDOException $e) {
                $errors['email'] = "Erreur : " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription - EventMatch</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
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
        <li class="nav-item"><a class="nav-link" href="login.php">Connexion</a></li>
        <li class="nav-item"><a class="nav-link active" href="register.php">Inscription</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="hero small-hero">
  <div class="overlay text-center text-white">
    <h1>Inscription</h1>
    <p>Créez votre compte pour rejoindre nos événements !</p>
  </div>
</section>

<div class="container my-5">
  <form method="POST" class="bg-white p-4 rounded shadow-sm" autocomplete="off">
    <fieldset>
      <legend class="text-purple">Créer un compte</legend>

      <div class="mb-3">
        <label class="form-label d-block">Civilité</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="sexe" id="sexe_m" value="Monsieur" <?= ($sexe === 'Monsieur') ? 'checked' : '' ?>>
          <label class="form-check-label" for="sexe_m">Homme</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="sexe" id="sexe_f" value="Madame" <?= ($sexe === 'Madame') ? 'checked' : '' ?>>
          <label class="form-check-label" for="sexe_f">Femme</label>
        </div>
        <br><small class="text-danger"><?= $errors['sexe'] ?></small>
      </div>

      <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>" required>
        <small class="text-danger"><?= $errors['nom'] ?></small>
      </div>

      <div class="mb-3">
        <label for="prenom" class="form-label">Prénom</label>
        <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($prenom) ?>" required>
        <small class="text-danger"><?= $errors['prenom'] ?></small>
      </div>

      <div class="mb-3">
        <label for="date" class="form-label">Date de naissance</label>
        <input type="date" id="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>" required>
        <small class="text-danger"><?= $errors['date'] ?></small>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Adresse e-mail</label>
        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required autocomplete="off">
        <small class="text-danger"><?= $errors['email'] ?></small>
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Téléphone</label>
        <input type="tel" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>" required>
        <small class="text-danger"><?= $errors['phone'] ?></small>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
        <small class="text-danger"><?= $errors['password'] ?></small>
      </div>

      <!-- Case affichée uniquement pour le design -->
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="contact_ok" name="contact_ok">
        <label class="form-check-label" for="contact_ok">J'accepte d'être contacté pour les prochains événements</label>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-purple">S'inscrire</button>
      </div>
    </fieldset>
  </form>

  <div class="text-center mt-3">
    <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
  </div>
</div>

<footer class="text-center footer-purple py-3 mt-5">
  <p class="mb-0">© 2025 EventMatch. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
