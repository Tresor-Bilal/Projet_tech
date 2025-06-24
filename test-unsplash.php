<?php
// Remplace par ta clé API Unsplash
$accessKey = '7oQvM8MBK3uzNSxw1FWEKeAtxfqn_YywjXnZM0dLKmc';

function fetchUnsplashEvents($accessKey) {
    $url = "https://api.unsplash.com/search/photos?query=event&per_page=3&client_id=" . $accessKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // désactive la vérification SSL temporairement

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Erreur cURL : ' . curl_error($ch);
        return null;
    }

    curl_close($ch);

    return json_decode($response, true);
}

$data = fetchUnsplashEvents($accessKey);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Test API Unsplash</title>
</head>
<body>
    <h1>Test affichage images Unsplash - Événements</h1>

    <?php if ($data && isset($data['results'])): ?>
        <div style="display:flex; gap:20px;">
            <?php foreach ($data['results'] as $photo): ?>
                <div style="text-align:center;">
                    <img src="<?= htmlspecialchars($photo['urls']['small']) ?>" alt="<?= htmlspecialchars($photo['alt_description']) ?>" style="max-width:200px; border-radius:8px;" />
                    <p><?= htmlspecialchars($photo['alt_description'] ?: 'Événement') ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Aucune image trouvée ou erreur lors de la requête.</p>
    <?php endif; ?>
</body>
</html>