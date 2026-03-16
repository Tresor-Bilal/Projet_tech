<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialisation des variables
$nom = $prenom = $email = $date_naissance = $password = $civilite = $phone = '';
$errors = [];

// Gestion du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $date_naissance = trim($_POST['date_naissance'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $civilite = $_POST['civilite'] ?? '';
    $phone = trim($_POST['phone'] ?? '');

    // --- Validation ---
    if (!$nom) $errors['nom'] = "Veuillez saisir votre nom.";
    if (!$prenom) $errors['prenom'] = "Veuillez saisir votre prénom.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Adresse e-mail invalide.";
    if (!$password || strlen($password) < 6) $errors['password'] = "Mot de passe requis (min 6 caractères).";
    if (!$date_naissance) $errors['date_naissance'] = "Veuillez saisir votre date de naissance.";
    if (!$civilite || !in_array($civilite, ['Monsieur','Madame'])) $errors['civilite'] = "Veuillez choisir votre civilité.";
    if ($phone && !preg_match('/^[0-9]{10}$/', $phone)) $errors['phone'] = "Numéro invalide (10 chiffres).";

    // --- Vérifier si email existe ---
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email=?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = "Email déjà utilisé.";
        }
    }

    // --- Insérer utilisateur ---
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insert = $pdo->prepare("
            INSERT INTO utilisateurs 
            (nom, prenom, email, date_naissance, mot_de_passe, civilite, phone, date_inscription) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $insert->execute([
            $nom, $prenom, $email, $date_naissance, $hashedPassword, $civilite, $phone ?: null
        ]);

        // Redirection après inscription réussie
        header("Location: login.php?inscription=success");
        exit;
    }
}
?>

<?php include 'includes/header.php'; ?>

<section class="hero small-hero">
    <div class="overlay text-center text-white">
        <h1>Inscription</h1>
        <p>Créez votre compte pour rejoindre nos événements !</p>
    </div>
</section>

<div class="container my-5">
    <form method="POST" class="bg-white p-4 rounded shadow-sm">
        <fieldset>
            <legend class="text-purple">Créer un compte</legend>

            <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($nom) ?>">
                <small class="text-danger"><?= $errors['nom'] ?? '' ?></small>
            </div>

            <div class="mb-3">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($prenom) ?>">
                <small class="text-danger"><?= $errors['prenom'] ?? '' ?></small>
            </div>

            <div class="mb-3">
                <label>Date de naissance</label>
                <input type="date" name="date_naissance" class="form-control" value="<?= htmlspecialchars($date_naissance) ?>">
                <small class="text-danger"><?= $errors['date_naissance'] ?? '' ?></small>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                <small class="text-danger"><?= $errors['email'] ?? '' ?></small>
            </div>

            <div class="mb-3">
                <label>Téléphone</label>
                <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>">
                <small class="text-danger"><?= $errors['phone'] ?? '' ?></small>
            </div>

            <div class="mb-3">
                <label>Civilité</label>
                <select name="civilite" class="form-control">
                    <option value="">Choisir</option>
                    <option value="Monsieur" <?= $civilite=="Monsieur"?"selected":"" ?>>Monsieur</option>
                    <option value="Madame" <?= $civilite=="Madame"?"selected":"" ?>>Madame</option>
                </select>
                <small class="text-danger"><?= $errors['civilite'] ?? '' ?></small>
            </div>

            <div class="mb-3">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control">
                <small class="text-danger"><?= $errors['password'] ?? '' ?></small>
            </div>

            <div class="d-grid">
                <button class="btn btn-purple">S'inscrire</button>
            </div>

        </fieldset>
    </form>
</div>

<?php include 'includes/footer.php'; ?>