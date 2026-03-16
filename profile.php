<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$success = '';
$error = '';

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {

    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if ($prenom && $nom && $email) {

        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? AND id != ?");
        $stmt->execute([$email,$userId]);

        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {

            $update = $pdo->prepare(
            "UPDATE utilisateurs SET prenom=?, nom=?, email=?, phone=? WHERE id=?");

            $update->execute([$prenom,$nom,$email,$phone,$userId]);

            $success = "Profil mis à jour avec succès.";

            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id=?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
        }

    } else {
        $error = "Veuillez remplir les champs obligatoires.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {

$ancien = $_POST['ancien_mot_de_passe'];
$nouveau = $_POST['nouveau_mot_de_passe'];
$confirmation = $_POST['confirmation_mot_de_passe'];

if (!$ancien || !$nouveau || !$confirmation) {

$error = "Veuillez remplir tous les champs.";

} elseif ($nouveau !== $confirmation) {

$error = "Les mots de passe ne correspondent pas.";

} elseif (!password_verify($ancien,$user['mot_de_passe'])) {

$error = "Ancien mot de passe incorrect.";

} else {

$hash = password_hash($nouveau,PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe=? WHERE id=?");
$stmt->execute([$hash,$userId]);

$success = "Mot de passe mis à jour.";
}

}
?>

<?php include 'includes/header.php'; ?>

<div class="container my-5">

<?php if($success): ?>
<div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<?php if($error): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<h2 class="text-purple mb-4">Mes informations</h2>

<form method="post" class="bg-white p-4 rounded shadow mb-5">

<input type="hidden" name="update_profile" value="1">

<div class="mb-3">
<label>Prénom</label>
<input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>">
</div>

<div class="mb-3">
<label>Nom</label>
<input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>">
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
</div>

<div class="mb-3">
<label>Téléphone</label>
<input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
</div>

<button class="btn btn-purple">Mettre à jour</button>

</form>

<h2 class="text-purple mb-4">Changer de mot de passe</h2>

<form method="post" class="bg-white p-4 rounded shadow">

<input type="hidden" name="change_password" value="1">

<div class="mb-3">
<label>Ancien mot de passe</label>
<input type="password" name="ancien_mot_de_passe" class="form-control">
</div>

<div class="mb-3">
<label>Nouveau mot de passe</label>
<input type="password" name="nouveau_mot_de_passe" class="form-control">
</div>

<div class="mb-3">
<label>Confirmer</label>
<input type="password" name="confirmation_mot_de_passe" class="form-control">
</div>

<button class="btn btn-purple">Modifier le mot de passe</button>

</form>

</div>

<?php include 'includes/footer.php'; ?>