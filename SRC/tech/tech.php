<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tech') {
    header('Location: ../login.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Espace Technicien</title>
  <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
  <header class="nav">
    <div class="brand">
      <a href="../index.php" class="brand-link">
        <img src="../images/logo_sae.webp" alt="Logo de la SAE">
      </a>
      <span class="badge">Technicien</span>
    </div>
    <ul>
      <li><a href="../index.php" style="color : rgb(0,79,163);">Accueil</a></li>
      <li><a class="btn secondary" href="../logout.php">Déconnexion</a></li>
    </ul>
  </header>

  <main class="container">
    <div class="card">
      <h1>Bienvenue <?php echo htmlspecialchars($_SESSION['login']); ?></h1>
      <p class="note">Actions disponibles sur le parc informatique.</p>
      <ul>
        <li><a href="list_machines.php" style="color : rgb(0,79,163);">Consulter le parc informatique</a></li>
        <li><a href="add_machine.php" style="color: rgb(0,79,163);">Ajouter une machine</a></li>
        <li><a href="add_screen.php" style="color: rgb(0,79,163);">Ajouter un ecran</a></li>
      </ul>
    </div>
  </main>
</body>
</html>

