<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'adminweb') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../inc/db.php';

$message = '';

// Création d'un technicien
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
    $mdp_confirm = trim($_POST['confirm_mdp'] ?? '');

    if (strlen($login) < 5) {
        $message = 'Le login doit contenir au minimum 5 caractères.';
    } elseif (strlen($mot_de_passe) < 5) {
        $message = 'Le mot de passe doit contenir au minimum 5 caractères.';
    } elseif ($mot_de_passe !== $mdp_confirm) {
        $message = 'Les mots de passe ne correspondent pas.';
    } else {
        $sqlCheck = "SELECT 1 FROM utilisateur WHERE login = ? LIMIT 1";
        $stmtCheck = mysqli_prepare($mysqli, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, 's', $login);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            $message = 'Ce login existe déjà. Choisissez un autre login.';
            mysqli_stmt_close($stmtCheck);
        } else {
            mysqli_stmt_close($stmtCheck);

            $sql = "INSERT INTO utilisateur (login, mot_de_passe, role) VALUES (?, ?, 'tech')";
            $stmt = mysqli_prepare($mysqli, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $login, $mot_de_passe);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'Technicien créé avec succès.';
            } else {
                $message = 'Erreur lors de la création du technicien.';
            }

            mysqli_stmt_close($stmt);
        }
    }
}

// Récupération des techniciens existants
$techniciens = [];
$sqlTech = "SELECT login FROM utilisateur WHERE role = 'tech' ORDER BY login ASC";
$resTech = mysqli_query($mysqli, $sqlTech);
if ($resTech) {
    while ($row = mysqli_fetch_assoc($resTech)) {
        $techniciens[] = $row;
    }
    mysqli_free_result($resTech);
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Créer un technicien</title>
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
      <li><a href="admin.php" style="color: rgb(0,79,163);">Retour admin</a></li>
      <li><a class="btn secondary" href="../logout.php">Déconnexion</a></li>
    </ul>
  </header>

  <main class="container">
    <div class="card" style="max-width:720px;margin:0 auto">
      <h1>Créer un technicien</h1>
      <p>Le login et le mdp doivent contenir au moins 5 caractères</p>

      <?php if ($message): ?>
        <p class="note"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <form class="form" method="post" action="create_tech.php">
        <label>Login
          <input class="input" type="text" name="login" minlength="5" required>
        </label>

        <label>Mot de passe
          <input class="input" type="password" name="mot_de_passe" minlength="5" required>
        </label>

        <label>Confirmation du mot de passe
          <input class="input" type="password" name="confirm_mdp" minlength="5" required>
        </label>

        <button class="button" type="submit">Créer</button>
      </form>

      <hr style="margin:24px 0;">

      <h2>Techniciens déjà créés</h2>

      <?php if (count($techniciens) === 0): ?>
        <p class="note">Aucun technicien n'a encore été créé.</p>
      <?php else: ?>
        <table class="table" style="width:100%; border-collapse:collapse;">
          <thead>
            <tr>
              <th style="text-align:left; padding:8px; border-bottom:1px solid #ddd;">Login</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($techniciens as $t): ?>
              <tr>
                <td style="padding:8px; border-bottom:1px solid #eee;">
                  <?php echo htmlspecialchars($t['login']); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>

    </div>
  </main>
</body>
</html>

