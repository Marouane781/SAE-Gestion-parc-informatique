<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'adminweb') {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../inc/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    if ($nom !== '') {
        $sql = "INSERT INTO os (nom) VALUES (?)";
        $stmt = mysqli_prepare($mysqli, $sql);
        mysqli_stmt_bind_param($stmt, 's', $nom);
        $ok = mysqli_stmt_execute($stmt);
        $message = $ok ? 'Système d’exploitation ajouté.' : 'Erreur : ' . mysqli_error($mysqli);
        mysqli_stmt_close($stmt);
    } else {
        $message = 'Nom obligatoire.';
    }
}
$result = mysqli_query($mysqli, "SELECT id, nom FROM os ORDER BY nom");
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Gérer les OS</title>
  <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
  <header class="nav">
    <div class="brand">
      <a href="admin.php" class="brand-link">
        <img src="../images/logo_sae.webp" alt="Logo de la SAE">
      </a>
      <span class="badge">Admin Web</span>
    </div>
    <ul>
      <li><a href="admin.php" style="color : rgb(0,79,163);">Retour admin</a></li>
      <li><a class="btn secondary" href="../logout.php">Déconnexion</a></li>
    </ul>
  </header>
  <main class="container">
    <div class="card">
      <h1>Systèmes d’exploitation</h1>
      <?php if ($message): ?>
        <p class="note"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>
      <form class="form" method="post" action="manage_os.php">
        <label>Nom de l’OS
          <input class="input" type="text" name="nom" required>
        </label>
        <button class="button" type="submit">Ajouter</button>
      </form>
      <h2>Liste existante</h2>
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Nom</th></tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo htmlspecialchars($row['nom']); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>

