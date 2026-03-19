<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tech') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../inc/db.php';

$message = '';

$result_const = mysqli_query($mysqli, "SELECT id, nom FROM constructeur ORDER BY nom");
$result_machines = mysqli_query($mysqli, "SELECT id, nom FROM machine ORDER BY nom");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serial = trim($_POST['serial'] ?? '');
    $modele = trim($_POST['modele'] ?? '');
    $taille = (int)($_POST['taille_pouces'] ?? 0);
    $resolution = trim($_POST['resolution'] ?? '');
    $connectique = trim($_POST['connectique'] ?? '');
    $constructeur_id = (int)($_POST['constructeur_id'] ?? 0);
    $machine_id = (int)($_POST['machine_id'] ?? 0);
    $etat = $_POST['etat'] ?? 'actif';

    if (strlen($serial) < 3) {
        $message = "Le numéro de série est obligatoire (au moins 3 caractères).";
    } elseif ($modele === '') {
        $message = "Le modèle est obligatoire.";
    } else {
        $sql = "INSERT INTO ecran
                (serial, modele, taille_pouces, resolution, connectique, constructeur_id, machine_id, etat)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($mysqli, $sql);

        $taille_val = ($taille > 0) ? $taille : NULL;
        $constructeur_val = ($constructeur_id > 0) ? $constructeur_id : NULL;
        $machine_val = ($machine_id > 0) ? $machine_id : NULL;

        mysqli_stmt_bind_param(
            $stmt,
            'ssissiis',
            $serial,
            $modele,
            $taille_val,
            $resolution,
            $connectique,
            $constructeur_val,
            $machine_val,
            $etat
        );

        $ok = mysqli_stmt_execute($stmt);
        $message = $ok ? 'Écran ajouté avec succès.' : 'Erreur : ' . mysqli_error($mysqli);
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Ajouter un écran</title>
  <link rel="stylesheet" href="../style/styles.css">
</head>
<body>
  <header class="nav">
    <div class="brand">
      <a href="tech.php" class="brand-link">
        <img src="../images/logo_sae.webp" alt="Logo de la SAE">
      </a>
      <span class="badge">Technicien</span>
    </div>
    <ul>
      <li><a href="tech.php" style="color: rgb(0,79,163);">Menu technicien</a></li>
      <li><a class="btn secondary" href="../logout.php">Déconnexion</a></li>
    </ul>
  </header>

  <main class="container">
    <div class="card" style="max-width:640px;margin:0 auto">
      <h1>Ajouter un écran</h1>

      <?php if ($message): ?>
        <p class="note"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <form class="form" method="post" action="add_screen.php">
        <label>Numéro de série
          <input class="input" type="text" name="serial" required>
        </label>

        <label>Modèle
          <input class="input" type="text" name="modele" required>
        </label>

        <label>Taille (pouces)
          <input class="input" type="number" name="taille_pouces" min="0">
        </label>

        <label>Résolution
          <input class="input" type="text" name="resolution" placeholder="ex: 1920x1080">
        </label>

        <label>Connectique
          <input class="input" type="text" name="connectique" placeholder="ex: HDMI, DP, VGA">
        </label>

        <label>Constructeur
          <select class="input" name="constructeur_id">
            <option value="0">-- Non renseigné --</option>
            <?php while ($c = mysqli_fetch_assoc($result_const)): ?>
              <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['nom']); ?></option>
            <?php endwhile; ?>
          </select>
        </label>

        <label>Attaché à une machine
          <select class="input" name="machine_id">
            <option value="0">-- Non attaché --</option>
            <?php while ($m = mysqli_fetch_assoc($result_machines)): ?>
              <option value="<?php echo (int)$m['id']; ?>"><?php echo htmlspecialchars($m['nom']); ?></option>
            <?php endwhile; ?>
          </select>
        </label>

        <label>État
          <select class="input" name="etat">
            <option value="actif">actif</option>
            <option value="rebut">rebut</option>
          </select>
        </label>

        <button class="button" type="submit">Ajouter</button>
      </form>
    </div>
  </main>
</body>
</html>

