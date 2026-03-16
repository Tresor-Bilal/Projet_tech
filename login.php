<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erreur_connexion = "";
$success_message = "";

// Affiche un message si l'inscription vient de réussir
if (isset($_GET['inscription']) && $_GET['inscription'] === 'success') {
    $success_message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['password'] ?? '';

    if (!empty($email) && !empty($mot_de_passe)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user'] = $user['prenom'];

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

<?php include 'includes/header.php'; ?>

<section class="hero small-hero">
    <div class="overlay text-center text-white">
        <h1>Connexion</h1>
        <p>Accède à ton compte pour découvrir tes événements personnalisés.</p>
    </div>
</section>

<div class="container my-5">

    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($erreur_connexion)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erreur_connexion) ?>
        </div>
    <?php endif; ?>

    <form class="bg-white p-4 rounded shadow" method="post">
        <fieldset>
            <legend class="text-purple">Connexion</legend>

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" required autofocus autocomplete="email">
            </div>

            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required autocomplete="current-password">
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-purple">
                    Connexion
                </button>
                <p class="mb-0">
                    Pas de compte ?
                    <a href="register.php">Créer un compte</a>
                </p>
            </div>
        </fieldset>
    </form>
</div>

<?php include 'includes/footer.php'; ?>