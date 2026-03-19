<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tech') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../inc/db.php';

$message = '';

// Listes déroulantes
$result_os = mysqli_query($mysqli, "SELECT id, nom FROM os ORDER BY nom");
$result_const = mysqli_query($mysqli, "SELECT id, nom FROM constructeur ORDER BY nom");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $modele = trim($_POST['modele'] ?? '');
    $cpu = trim($_POST['cpu'] ?? '');
    $ram = (int)($_POST['ram'] ?? 0);

    $os_id = (int)($_POST['os_id'] ?? 0);
    $constructeur_id = (int)($_POST['constructeur_id'] ?? 0);

    $batiment = trim($_POST['batiment'] ?? '');
    $salle = trim($_POST['salle'] ?? '');
    $etat = $_POST['etat'] ?? 'actif';

    // 3 champs obligatoires : nom, os, salle
    if (strlen($nom) < 1) {
        $message = "Le champ Nom est obligatoire.";
    } elseif ($os_id <= 0) {
        $message = "Le champ Système d’exploitation (OS) est obligatoire.";
    } elseif (strlen($salle) < 1) {
        $message = "Le champ Salle est obligatoire.";
    } else {
        $sql = "INSERT INTO machine (nom, modele, cpu, ram, os_id, constructeur_id, batiment, salle, etat)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($mysqli, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            'sssiiisss',
            $nom,
            $modele,
            $cpu,
            $ram,
            $os_id,
            $constructeur_id,
            $batiment,
            $salle,
            $etat
        );

        $ok = mysqli_stmt_execute($stmt);
        $message = $ok ? 'Machine ajoutée avec succès.' : 'Erreur : ' . mysqli_error($mysqli);
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Ajouter une machine</title>
  <link rel="stylesheet" href="../style/styles.css">
  <style>
    .required { color: red; font-weight: bold; margin-left: 4px; }
  </style>
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
    <div class="card" style="max-width:720px;margin:0 auto">
      <h1>Ajouter une machine</h1>

      <?php if ($message): ?>
        <p class="note"><?php echo htmlspecialchars($message); ?></p>
      <?php endif; ?>

      <p class="note">Les champs marqués d’un <span class="required">*</span> sont obligatoires.</p>

      <form class="form" method="post" action="add_machine.php">
        <label>Nom <span class="required">*</span>
          <input class="input" type="text" name="nom" required>
        </label>

        <label>Système d’exploitation (OS) <span class="required">*</span>
          <select class="input" name="os_id" required>
            <option value="0">-- Choisir un OS --</option>
            <?php if ($result_os): ?>
              <?php while ($o = mysqli_fetch_assoc($result_os)): ?>
                <option value="<?php echo (int)$o['id']; ?>">
                  <?php echo htmlspecialchars($o['nom']); ?>
                </option>
              <?php endwhile; ?>
            <?php endif; ?>
          </select>
        </label>

        <label>Salle <span class="required">*</span>
          <input class="input" type="text" name="salle" required>
        </label>

        <label>Modèle
          <input class="input" type="text" name="modele">
        </label>

        <label>CPU
          <input class="input" type="text" name="cpu">
        </label>

        <label>RAM (Mo)
          <input class="input" type="number" name="ram" min="0" value="0">
        </label>

        <label>Constructeur
          <select class="input" name="constructeur_id">
            <option value="0">-- Non renseigné --</option>
            <?php if ($result_const): ?>
              <?php while ($c = mysqli_fetch_assoc($result_const)): ?>
                <option value="<?php echo (int)$c['id']; ?>">
                  <?php echo htmlspecialchars($c['nom']); ?>
                </option>
              <?php endwhile; ?>
            <?php endif; ?>
          </select>
        </label>

        <label>Bâtiment
          <input class="input" type="text" name="batiment">
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
