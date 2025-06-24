<?php
require_once 'db.php';
if (session_status()==PHP_SESSION_NONE)
session_start();

$erreur_connexion = "";

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['password'] ?? '';

    if (!empty($email) && !empty($mot_de_passe)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            // ✅ On stocke l'identifiant pour vérification future
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user['prenom']; // pour affichage éventuel

            // ✅ Redirection vers la page précédemment demandée
            $redirect = $_SESSION['redirect_after_login'] ?? 'index.php';
            unset($_SESSION['redirect_after_login']);
            header("Location: $redirect");
            exit;
        } else {
            $erreur_connexion = "Email ou mot de passe incorrect.";
        }
    } else {
        $erreur_connexion = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EventMatch – Connexion</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="css/style.css"/>
</head>
<body>

<?php if (isset($_SESSION['success'])): ?>
  <div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
      <?= htmlspecialchars($_SESSION['success']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
  </div>
  <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($erreur_connexion)): ?>
  <div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($erreur_connexion) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
  </div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-purple">
  <div class="container">
    <a class="navbar-brand" href="index.php">EventMatch</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link active" href="login.php">Connexion</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Inscription</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="hero small-hero">
  <div class="overlay text-center text-white">
    <h1>Connexion</h1>
    <p>Accède à ton compte pour découvrir tes événements personnalisés.</p>
  </div>
</section>

<div class="container my-5">
  <form class="bg-white p-4 rounded shadow" method="post" autocomplete="off">
    <fieldset>
      <legend class="text-purple">Connexion</legend>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" autocomplete="email" required />
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required />
      </div>

      <div class="d-flex justify-content-between align-items-center">
        <button type="submit" class="btn btn-purple">Connexion</button>
        <div>

        </div>
            <a href="register.php">N'avez-vous pas de compte ?</a>
      </div>
    </fieldset>
  </form>
</div>

<footer class="text-center footer-purple">
  <p class="mb-0">© 2025 EventMatch. Tous droits réservés.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  setTimeout(function () {
    const alert = document.getElementById('success-alert');
    if (alert) {
      alert.classList.add('hide');
      setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
        bsAlert.close();
      }, 500);
    }
  }, 3000);
</script>

</body>
</html>
