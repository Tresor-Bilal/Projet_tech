<?php
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nom = $prenom = $email = $date = $password = $sexe = $phone = '';

$errors = [
'nom'=>'',
'prenom'=>'',
'email'=>'',
'date'=>'',
'password'=>'',
'sexe'=>'',
'phone'=>''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$nom = trim($_POST['nom'] ?? '');
$prenom = trim($_POST['prenom'] ?? '');
$date = trim($_POST['date'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$sexe = $_POST['sexe'] ?? '';
$phone = $_POST['phone'] ?? '';

$valid = true;

if(empty($nom)){
$errors['nom']="Veuillez saisir votre nom.";
$valid=false;
}

if(empty($prenom)){
$errors['prenom']="Veuillez saisir votre prénom.";
$valid=false;
}

if(empty($email) || !filter_var($email,FILTER_VALIDATE_EMAIL)){
$errors['email']="Adresse e-mail invalide.";
$valid=false;
}

if(empty($password)){
$errors['password']="Veuillez saisir un mot de passe.";
$valid=false;
}

if(empty($date)){
$errors['date']="Veuillez saisir votre date de naissance.";
$valid=false;
}

if(empty($sexe)){
$errors['sexe']="Veuillez choisir votre sexe.";
$valid=false;
}

if(empty($phone) || !preg_match('/^[0-9]{10}$/',$phone)){
$errors['phone']="Numéro invalide (10 chiffres).";
$valid=false;
}

if($valid){

$stmt=$pdo->prepare("SELECT id FROM utilisateurs WHERE email=?");
$stmt->execute([$email]);

if($stmt->fetch()){

$errors['email']="Email déjà utilisé.";

}else{

$hashedPassword=password_hash($password,PASSWORD_DEFAULT);

$insert=$pdo->prepare(
"INSERT INTO utilisateurs (nom,prenom,email,date_naissance,mot_de_passe,civilite,phone)
VALUES (?,?,?,?,?,?,?)"
);

$insert->execute([
$nom,
$prenom,
$email,
$date,
$hashedPassword,
$sexe,
$phone
]);

header("Location: login.php?inscription=success");
exit;

}

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
<small class="text-danger"><?= $errors['nom'] ?></small>
</div>

<div class="mb-3">
<label>Prénom</label>
<input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($prenom) ?>">
<small class="text-danger"><?= $errors['prenom'] ?></small>
</div>

<div class="mb-3">
<label>Date de naissance</label>
<input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>">
<small class="text-danger"><?= $errors['date'] ?></small>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
<small class="text-danger"><?= $errors['email'] ?></small>
</div>

<div class="mb-3">
<label>Téléphone</label>
<input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>">
<small class="text-danger"><?= $errors['phone'] ?></small>
</div>

<div class="mb-3">
<label>Sexe</label>
<select name="sexe" class="form-control">
<option value="">Choisir</option>
<option value="M" <?= $sexe=="M"?"selected":"" ?>>Homme</option>
<option value="F" <?= $sexe=="F"?"selected":"" ?>>Femme</option>
</select>
<small class="text-danger"><?= $errors['sexe'] ?></small>
</div>

<div class="mb-3">
<label>Mot de passe</label>
<input type="password" name="password" class="form-control">
<small class="text-danger"><?= $errors['password'] ?></small>
</div>

<div class="d-grid">
<button class="btn btn-purple">
S'inscrire
</button>
</div>

</fieldset>

</form>

</div>

<?php include 'includes/footer.php'; ?>