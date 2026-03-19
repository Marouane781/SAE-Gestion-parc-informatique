<?php
session_start();

// Vérifier si l'utilisateur est un administrateur système
if ($_SESSION['role'] !== 'sysadmin') {
    header('Location: ../login.php');  // Redirection vers la page d'accueil si ce n'est pas un admin système
    exit();
}

// Chemin du fichier de log Apache
$logFile = '/var/log/apache2/access.log';

// Vérification si le fichier existe et est lisible
if (file_exists($logFile) && is_readable($logFile)) {
    // Lire les logs
    $logs = file($logFile);
    // Vérifier si des logs ont été récupérés
    if (empty($logs)) {
        $logs = ["Le fichier journal est vide."];
    }
} else {
    // Si le fichier n'existe pas ou n'est pas lisible
    $logs = ["Le fichier de journal est introuvable ou non lisible."];
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Journaux d'Activité</title>
    <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
    <header class="nav">
        <div class="brand">
            <a href="sysadmin.php" class="brand-link">
                <img src="../images/logo_sae.webp" alt="Logo de la SAE" />
            </a>
            <span class="badge">Journaux d'Activité</span>
        </div>
        <ul>
            <li><a href="sysadmin.php" style="color: rgb(0,79,163);">Retour au tableau de bord</a></li>
            <li><a href="../logout.php" class="btn secondary">Déconnexion</a></li>
        </ul>
    </header>

    <main class="container">
        <section class="card">
            <h1>Journaux d'activité</h1>
            <pre style="white-space: pre-wrap; word-wrap: break-word;">
                <?php
                // Affichage des logs
                foreach ($logs as $log) {
                    echo htmlspecialchars($log) . "\n";
                }
                ?>
            </pre>
        </section>
    </main>
</body>
</html>

