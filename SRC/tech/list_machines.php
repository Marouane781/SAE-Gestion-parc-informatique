<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'tech') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../inc/db.php';

$parPage = 10;

function bind_params_dynamic($stmt, $types, $params) {
    if ($types === '' || empty($params)) return;
    $bind_names = [];
    $bind_names[] = $types;
    for ($i = 0; $i < count($params); $i++) {
        $bind_names[] = &$params[$i];
    }
    call_user_func_array('mysqli_stmt_bind_param', array_merge([$stmt], $bind_names));
}

/* ====== Filtres GET ====== */
$filtre_os = isset($_GET['os']) ? (int)$_GET['os'] : 0;       // 0 = tous
$filtre_batiment = trim($_GET['batiment'] ?? '');             // '' = tous
$filtre_salle = trim($_GET['salle'] ?? '');                   // '' = tous

/* ====== Options filtres ====== */
$osOptions = [];
$resOs = mysqli_query($mysqli, "SELECT id, nom FROM os ORDER BY nom");
if ($resOs) {
    while ($r = mysqli_fetch_assoc($resOs)) $osOptions[] = $r;
    mysqli_free_result($resOs);
}

$batOptions = [];
$resBat = mysqli_query($mysqli, "SELECT DISTINCT batiment FROM machine WHERE batiment IS NOT NULL AND batiment <> '' ORDER BY batiment");
if ($resBat) {
    while ($r = mysqli_fetch_assoc($resBat)) $batOptions[] = $r['batiment'];
    mysqli_free_result($resBat);
}

$salleOptions = [];
$resSalle = mysqli_query($mysqli, "SELECT DISTINCT salle FROM machine WHERE salle IS NOT NULL AND salle <> '' ORDER BY salle");
if ($resSalle) {
    while ($r = mysqli_fetch_assoc($resSalle)) $salleOptions[] = $r['salle'];
    mysqli_free_result($resSalle);
}

/* ====== WHERE dynamique ====== */
$where = [];
$types = '';
$params = [];

if ($filtre_os > 0) {
    $where[] = "m.os_id = ?";
    $types .= 'i';
    $params[] = $filtre_os;
}
if ($filtre_batiment !== '') {
    $where[] = "m.batiment = ?";
    $types .= 's';
    $params[] = $filtre_batiment;
}
if ($filtre_salle !== '') {
    $where[] = "m.salle = ?";
    $types .= 's';
    $params[] = $filtre_salle;
}

$whereSql = count($where) ? ("WHERE " . implode(" AND ", $where)) : "";

/* ====== Pagination ====== */
$sqlCount = "SELECT COUNT(*) AS total
            FROM machine m
            LEFT JOIN os o ON m.os_id = o.id
            LEFT JOIN constructeur c ON m.constructeur_id = c.id
            $whereSql";

$stmtCount = mysqli_prepare($mysqli, $sqlCount);
if (!$stmtCount) { die("Erreur SQL (count)."); }
bind_params_dynamic($stmtCount, $types, $params);
mysqli_stmt_execute($stmtCount);
$resCount = mysqli_stmt_get_result($stmtCount);
$rowCount = mysqli_fetch_assoc($resCount);
$totalMachines = (int)($rowCount['total'] ?? 0);
mysqli_stmt_close($stmtCount);

$totalPages = $totalMachines > 0 ? (int)ceil($totalMachines / $parPage) : 1;

$pageCourante = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($pageCourante < 1) $pageCourante = 1;
if ($pageCourante > $totalPages) $pageCourante = $totalPages;

$offset = ($pageCourante - 1) * $parPage;

/* ====== Requête liste ====== */
$sql = "SELECT m.id, m.nom, m.modele, m.cpu, m.ram, m.batiment, m.salle, m.etat,
               o.nom AS os_nom, c.nom AS constructeur_nom
        FROM machine m
        LEFT JOIN os o ON m.os_id = o.id
        LEFT JOIN constructeur c ON m.constructeur_id = c.id
        $whereSql
        ORDER BY m.id DESC
        LIMIT ?, ?";

$stmt = mysqli_prepare($mysqli, $sql);
if (!$stmt) { die("Erreur SQL (liste)."); }

$typesMain = $types . 'ii';
$paramsMain = array_merge($params, [$offset, $parPage]);
bind_params_dynamic($stmt, $typesMain, $paramsMain);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

function build_query_with_filters($page, $filtre_os, $filtre_batiment, $filtre_salle) {
    $q = ['page' => $page];
    if ($filtre_os > 0) $q['os'] = $filtre_os;
    if ($filtre_batiment !== '') $q['batiment'] = $filtre_batiment;
    if ($filtre_salle !== '') $q['salle'] = $filtre_salle;
    return http_build_query($q);
}
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Parc informatique</title>
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
    <div class="card">
      <h1>Parc informatique</h1>

      <form method="get" action="list_machines.php" class="form" style="margin-bottom:16px;">
        <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:end;">
          <label style="min-width:220px;">Filtrer par OS
            <select class="input" name="os">
              <option value="0">Tous</option>
              <?php foreach ($osOptions as $o): ?>
                <option value="<?php echo (int)$o['id']; ?>" <?php echo ($filtre_os == (int)$o['id']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($o['nom']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </label>

          <label style="min-width:220px;">Filtrer par bâtiment
            <select class="input" name="batiment">
              <option value="">Tous</option>
              <?php foreach ($batOptions as $b): ?>
                <option value="<?php echo htmlspecialchars($b); ?>" <?php echo ($filtre_batiment === $b) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($b); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </label>

          <label style="min-width:220px;">Filtrer par salle
            <select class="input" name="salle">
              <option value="">Toutes</option>
              <?php foreach ($salleOptions as $s): ?>
                <option value="<?php echo htmlspecialchars($s); ?>" <?php echo ($filtre_salle === $s) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($s); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </label>

          <button class="button" type="submit">Appliquer</button>
          <a class="btn secondary" href="list_machines.php" style="align-self:center;color: rgb(0,79,163);">Réinitialiser</a>
        </div>
      </form>

      <p class="note">
        Machines affichées :
        <?php
          $debut = $totalMachines === 0 ? 0 : $offset + 1;
          $fin = min($offset + $parPage, $totalMachines);
          echo $debut . " à " . $fin . " sur " . $totalMachines;
        ?>
      </p>

      <table class="table">
        <thead>
          <tr>
            <th>Nom</th><th>Modèle</th><th>CPU</th><th>RAM</th>
            <th>OS</th><th>Constructeur</th><th>Bât.</th><th>Salle</th><th>État</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($totalMachines === 0): ?>
            <tr><td colspan="10">Aucune machine ne correspond aux filtres.</td></tr>
          <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['nom']); ?></td>
                <td><?php echo htmlspecialchars($row['modele']); ?></td>
                <td><?php echo htmlspecialchars($row['cpu']); ?></td>
                <td><?php echo htmlspecialchars($row['ram']); ?> Mo</td>
                <td><?php echo htmlspecialchars($row['os_nom']); ?></td>
                <td><?php echo htmlspecialchars($row['constructeur_nom']); ?></td>
                <td><?php echo htmlspecialchars($row['batiment']); ?></td>
                <td><?php echo htmlspecialchars($row['salle']); ?></td>
                <td><?php echo htmlspecialchars($row['etat']); ?></td>
                <td><a href="edit_machine.php?id=<?php echo (int)$row['id']; ?>" style="color: rgb(0,79,163);">Modifier</a></td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>

      <?php if ($totalPages > 1): ?>
        <div class="pagination" style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;color: rgb(0,79,163);">
          <?php if ($pageCourante > 1): ?>
            <a href="list_machines.php?<?php echo htmlspecialchars(build_query_with_filters($pageCourante - 1, $filtre_os, $filtre_batiment, $filtre_salle)); ?>" style="color: rgb(0,79,163);">&laquo; Précédent</a>
          <?php endif; ?>

          <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <?php if ($p == $pageCourante): ?>
              <span style="font-weight:bold;"><?php echo $p; ?></span>
            <?php else: ?>
              <a href="list_machines.php?<?php echo htmlspecialchars(build_query_with_filters($p, $filtre_os, $filtre_batiment, $filtre_salle)); ?>"style="color: rgb(0,79,163);"><?php echo $p; ?></a>
            <?php endif; ?>
          <?php endfor; ?>

          <?php if ($pageCourante < $totalPages): ?>
            <a href="list_machines.php?<?php echo htmlspecialchars(build_query_with_filters($pageCourante + 1, $filtre_os, $filtre_batiment, $filtre_salle)); ?>" style="color: rgb(0,79,163);">Suivant &raquo;</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>
  </main>
</body>
</html>
<?php
mysqli_stmt_close($stmt);
?>
