<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'adminweb') {
    header('Location: ../login.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Espace Admin Web</title>
  <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
  <header class="nav">
    <div class="brand">
      <a href="../index.php" class="brand-link">
        <img src="../images/logo_sae.webp" alt="Logo de la SAE">
      </a>
      <span class="badge">Admin Web</span>
    </div>
    <ul>
      <li><a href="../index.php" style="color: rgb(0,79,163);">Accueil</a></li>
      <li><a class="btn secondary" href="../logout.php">Déconnexion</a></li>
    </ul>
  </header>

  <main class="container">
    <div class="card">
      <h1>Bienvenue <?php echo htmlspecialchars($_SESSION['login']); ?></h1>
      <p class="note">Gestion des comptes techniciens et des informations réutilisables.</p>
      <ul>
        <li><a href="create_tech.php" style="color: rgb(0,79,163);">Créer un technicien</a></li>
        <li><a href="manage_os.php" style="color: rgb(0,79,163);">Gérer les systèmes d’exploitation</a></li>
        <li><a href="manage_constructeur.php" style="color: rgb(0,79,163);">Gérer les constructeurs</a></li>
      </ul>
    </div>
  </main>
</body>
</html>

