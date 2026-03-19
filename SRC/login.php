<?php
// login.php
session_start();
require_once __DIR__ . '/inc/db.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    if ($login === '' || $mot_de_passe === '') {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $sql = "SELECT id, login, mot_de_passe, role FROM utilisateur WHERE login = ? LIMIT 1";
        $stmt = mysqli_prepare($mysqli, $sql);

        if ($stmt === false) {
            $erreur = 'Erreur préparation requête : ' . mysqli_error($mysqli);
        } else {
            mysqli_stmt_bind_param($stmt, 's', $login);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id_db, $login_db, $mdp_db, $role_db);

            if (mysqli_stmt_fetch($stmt)) {
                if ($mot_de_passe === $mdp_db) {
                    
                    $_SESSION['id']    = $id_db;
                    $_SESSION['login'] = $login_db;
                    $_SESSION['role']  = $role_db;

                 
                    switch ($role_db) {
                        case 'adminweb':
                            header('Location: admin/admin.php');
                            exit;
                        case 'sysadmin':
                            header('Location: sysadmin/sysadmin.php');
                            exit;
                        case 'tech':
                            header('Location: tech/tech.php');
                            exit;
                        default:
                            $erreur = 'Rôle inconnu.';
                    }
                } else {
                    $erreur = 'Identifiants incorrects.';
                }
            } else {
                $erreur = 'Identifiants incorrects.';
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Parc Info — Connexion</title>
  <link rel="stylesheet" href="style/styles.css">
</head>
<body>
  <header class="nav">
    <div class="brand">
      <a href="index.php" class="brand-link">
        <img src="images/logo_sae.webp" alt="Logo de la SAE">
        
      </a>
      <span class="badge">Connexion</span>
    </div>
    <ul>
      <li><a href="index.php" style="color: rgb(0,79,163);">Accueil</a></li>
    </ul>
  </header>

  <main class="container">
    <div class="card" style="max-width:520px;margin:0 auto">
      <h1>Connexion</h1>

      <?php if ($erreur): ?>
        <p class="note" style="color:#DC3545;"><?php echo htmlspecialchars($erreur); ?></p>
      <?php else: ?>
        <p class="note">
          Utilisez les identifiants fournis dans le sujet.
        </p>
      <?php endif; ?>

      <form class="form" action="login.php" method="post">
        <label>Identifiant
          <input
            class="input"
            type="text"
            name="login"
            placeholder="ex. tech1"
            required
            autocomplete="off">
        </label>
        <label>Mot de passe
          <input
            class="input"
            type="password"
            name="mot_de_passe"
            placeholder="••••••••"
            required
            autocomplete="off">
        </label>
        <div class="actions">
          <button class="button" type="submit">Se connecter</button>
          <a class="button secondary" href="index.php" style="background-color: rgb(0,79,163);">Retour</a>
        </div>
        <p class="note">
          Cette version utilise une authentification réelle côté serveur (mysqli).
        </p>
      </form>
    </div>
  </main>

  <footer class="footer">
    <p class="note">Vous serez redirigé vers l’espace correspondant à votre rôle après connexion.</p>
  </footer>
</body>
</html>
