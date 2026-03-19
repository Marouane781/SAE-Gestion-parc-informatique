<?php
session_start();


$connecte = isset($_SESSION['role']);

if ($connecte) {
    switch ($_SESSION['role']) {
        case 'adminweb':
            $lienEspace = 'admin/admin.php';
            break;
        case 'sysadmin':
            $lienEspace = 'sysadmin/sysadmin.php';
            break;
        case 'tech':
            $lienEspace = 'tech/tech.php';
            break;
        default:
            $lienEspace = 'login.php';
    }
} else {
    $lienEspace = 'login.php';
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Parc Info — Accueil</title>
  <link rel="stylesheet" href="style/styles.css">
</head>
<body>
  <header class="nav">
    <div class="brand">
      <a href="index.php" class="brand-link">
        <img src="images/logo_sae.webp" alt="Logo de la SAE">
      </a>
      <span class="badge"><?php echo $connecte ? "Connecté" : "Invité"; ?></span>
    </div>
    <ul>
      <li><a href="index.php" style="color: rgb(0, 79, 163);">Accueil</a></li>

      <?php if ($connecte && ($_SESSION['role'] === 'adminweb' || $_SESSION['role'] === 'tech')): ?>
          <li><a href="stats/stats.php" style="color: rgb(0,79,163);">Statistiques</a></li>
      <?php endif; ?>


      <?php if (!$connecte): ?>
          <li><a class="btn" href="login.php">Se connecter</a></li>
      <?php else: ?>
          <li><a class="btn" href="<?php echo $lienEspace; ?>">Mon espace</a></li>
          <li><a class="btn secondary" href="logout.php">Déconnexion</a></li>
      <?php endif; ?>

    </ul>
  </header>

  <main class="container">
    <section class="hero">
      <div class="card">
        <h1>Gérez votre parc informatique.</h1>
        <p class="note">Cette page est désormais <b>dynamique PHP</b> pour la SAÉ 3 – FI 2.</p>
        <div class="kpis">
          <div class="kpi"><div class="badge">UC</div><h2>128</h2><div class="note">Unités centrales</div></div>
          <div class="kpi"><div class="badge">Moniteurs</div><h3>212</h3><div class="note">Écrans suivis</div></div>
          <div class="kpi"><div class="badge">Rebut</div><h4>7</h4><div class="note">En attente</div></div>
        </div>
        <div class="actions" style="margin-top:16px">
          <a class="button" href="<?php echo $lienEspace; ?>">
            <?php echo $connecte ? "Accéder à mon espace" : "Accéder à l'espace"; ?>
          </a>
        </div>
      </div>
      <div class="card">
        <h5>Fonctionnalités clés</h5>
        <div class="features">
          <div><div class="badge">Inventaire</div><p>Liste des UC et moniteurs, filtres par bâtiment/salle.</p></div>
          <div><div class="badge">CSV</div><p>Import/Export CSV pour des mises à jour en masse.</p></div>
          <div><div class="badge">Rebut</div><p>Marquer, verrouiller et restaurer du rebut.</p></div>
        </div>
      </div>
    </section>

    <section class="card video-section">
      <img src="images/annonce-video.png" alt="Vidéo de présentation (à venir)" class="annonce-video">
      <div>
        <h2>Présentation de la plateforme</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.</p>
        <p>Proin porttitor, orci nec nonummy molestie, enim est eleifend mi…</p>
      </div>
    </section>

    <section class="card">
      <h2>Extrait d'inventaire (vue publique)</h2>
      <table class="table">
        <thead>
          <tr>
            <th>Nom</th><th>Modèle</th><th>CPU</th><th>RAM</th><th>OS</th><th>Bât.</th>
          </tr>
        </thead>
        <tbody>
          <tr><td>UC-PA-001</td><td>Dell OptiPlex 7090</td><td>i5-10500</td><td>16&nbsp;Go</td><td>Windows 10</td><td>A</td></tr>
          <tr><td>UC-VA-014</td><td>HP EliteDesk 800</td><td>Ryzen 5 Pro</td><td>8&nbsp;Go</td><td>Ubuntu 22.04</td><td>B</td></tr>
          <tr><td>UC-LAB-021</td><td>Lenovo M920</td><td>i7-9700</td><td>32&nbsp;Go</td><td>Windows 11</td><td>C</td></tr>
        </tbody>
      </table>
      <p class="note">Données factices pour maquette.</p>
    </section>
  </main>

  <footer class="footer">
    <p>Groupe composé de : <b>AKHDARI ILYES, BOULAKHRAS MOHAMED, MOUHSINI ILYES, YOUSSOUFI MAROUANE et ZERROUK ACHRAF</b></p>
  </footer>
</body>
</html>
