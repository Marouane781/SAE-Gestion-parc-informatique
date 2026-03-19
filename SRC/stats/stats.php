<?php
session_start();

if (!isset($_SESSION['role'])) {
    header('Location: ../login.php');
    exit;
}

$role = $_SESSION['role'];
if ($role !== 'tech' && $role !== 'adminweb') {
    http_response_code(403);
    echo 'Accès interdit.';
    exit;
}

// Récupérer les fichiers CSV
$devicesFile = __DIR__ . '/../data/inventory_devices.csv';
$monitorsFile = __DIR__ . '/../data/inventory_monitors2.csv';
$connectionsFile = __DIR__ . '/../data/connections.csv';

/**
 * Fonction de lecture CSV et conversion en tableau associatif
 */
function read_csv_assoc($filename) {
    $rows = [];
    if (!file_exists($filename)) {
        return $rows;
    }
    if (($handle = fopen($filename, 'r')) !== false) {
        $headers = fgetcsv($handle, 0, ',');
        if ($headers === false) {
            fclose($handle);
            return $rows;
        }
        while (($data = fgetcsv($handle, 0, ',')) !== false) {
            if (count($data) !== count($headers)) {
                continue;
            }
            $row = [];
            foreach ($headers as $i => $h) {
                $row[$h] = $data[$i];
            }
            $rows[] = $row;
        }
        fclose($handle);
    }
    return $rows;
}

/* ==========================
   STATISTIQUES UNITÉS CENTRALES
   ========================== */
$devices = read_csv_assoc($devicesFile);

$totalDevices = count($devices);
$locationCounts = [];
$cpuCounts = [];
$osCounts = [];
$typeCounts = [];
$ramSum = 0;
$activeCount = 0;
$rebutCount = 0;
$diskSSDCount = 0;
$diskHDDCount = 0;
$garantieSous = 0;
$garantieHors = 0;

foreach ($devices as $d) {
    // Compte de RAM
    $ramSum += (int)($d['RAM_MB'] ?? 0);

    // Compte des filières (location)
    $location = $d['LOCATION'] ?? '';
    if ($location) {
        $locationCounts[$location] = ($locationCounts[$location] ?? 0) + 1;
    }

    // CPU
    $cpu = $d['CPU'] ?? '';
    if ($cpu) {
        $cpuCounts[$cpu] = ($cpuCounts[$cpu] ?? 0) + 1;
    }

    // OS
    $os = $d['OS'] ?? '';
    if ($os) {
        $osCounts[$os] = ($osCounts[$os] ?? 0) + 1;
    }

    // Type de machine
    $type = $d['TYPE'] ?? '';
    if ($type) {
        $typeCounts[$type] = ($typeCounts[$type] ?? 0) + 1;
    }

    // Statut actif ou rebut
    if ($d['etat'] === 'actif') {
        $activeCount++;
    } else {
        $rebutCount++;
    }

    // SSD ou HDD
    $diskGB = (int)($d['DISK_GB'] ?? 0);
    if ($diskGB >= 512) {
        $diskSSDCount++;
    } else {
        $diskHDDCount++;
    }

    // Vérification de garantie (sous garantie ou hors garantie)
    $purchaseDate = $d['PURCHASE_DATE'] ?? '';
    if ($purchaseDate && strtotime($purchaseDate) > strtotime('-3 years')) {
        $garantieSous++;
    } else {
        $garantieHors++;
    }
}

$avgRam = $totalDevices > 0 ? round($ramSum / $totalDevices) : 0;

/* ==========================
   STATISTIQUES MONITEURS
   ========================== */
$monitors = read_csv_assoc($monitorsFile);
$monitorCounts = [];
$connectorCounts = [];
$sizeCounts = [];

foreach ($monitors as $m) {
    $connector = $m['CONNECTOR'] ?? '';
    if ($connector) {
        $connectorCounts[$connector] = ($connectorCounts[$connector] ?? 0) + 1;
    }

    $size = $m['SIZE_INCH'] ?? '';
    if ($size) {
        $sizeCounts[$size] = ($sizeCounts[$size] ?? 0) + 1;
    }
}

arsort($locationCounts);
arsort($cpuCounts);
arsort($osCounts);
arsort($typeCounts);
arsort($connectorCounts);
arsort($sizeCounts);

/* ==========================
   STATISTIQUES CONNEXIONS
   ========================== */
$connections = read_csv_assoc($connectionsFile);
$totalConnections = count($connections);
$userCounts = [];
$totalDuration = 0;

foreach ($connections as $c) {
    $login = $c['login'] ?? '';
    if ($login) {
        $userCounts[$login] = ($userCounts[$login] ?? 0) + 1;
    }

    $duration = (int)($c['duration_seconds'] ?? 0);
    $totalDuration += $duration;
}

$avgDuration = $totalConnections > 0 ? round($totalDuration / $totalConnections) : 0;
arsort($userCounts);
$distinctUsers = count($userCounts);

// Probabilité d'une connexion par utilisateur
$topUser = key($userCounts);
$topUserCount = reset($userCounts);
$topUserProb = ($topUserCount / $totalConnections) * 100;

$probGarantieHors = ($garantieHors / $totalDevices) * 100;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Statistiques du Parc Informatique</title>
  <link rel="stylesheet" href="../style/styles.css">
  <style>
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
    }
    .stat-box {
      padding: 1rem;
      border-radius: 0.75rem;
      border: 1px solid #ddd;
      background: #fafafa;
    }
    .bar {
      height: 12px;
      border-radius: 8px;
      background: #ddd;
      overflow: hidden;
    }
    .bar-fill {
      height: 100%;
      background: #4caf50;
    }
    .pie-chart {
      position: relative;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: conic-gradient(
        #4caf50 0deg <?php echo ($garantieSous / $totalDevices) * 360; ?>deg,
        #f44336 <?php echo ($garantieSous / $totalDevices) * 360; ?>deg 360deg
      );
      margin: 0 auto;
    }
    .pie-slice {
      position: absolute;
      top: 50%;
      left: 50%;
      transform-origin: 100%;
      transform: rotate(var(--angle));
      background-color: rgba(76, 175, 80, 0.6);
      width: 100%;
      height: 100%;
    }
    .pie-slice span {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: white;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <header class="nav">
    <div class="brand">
      <a href="../index.php" class="brand-link">
        <img src="../images/logo_sae.webp" alt="Logo" />
      </a>
      <span class="badge">Statistiques</span>
    </div>
    <ul>
      <li><a href="../index.php" style="color: rgb(0,79,163);">Accueil</a></li>
    </ul>
  </header>

  <main class="container">
    <section class="card">
      <h2>Unités centrales</h2>
      <div class="grid">
        <div class="stat-box">
          <h3>Machines sous / hors garantie</h3>
          <!-- Graphique en Camembert -->
          <div class="pie-chart"></div>
          <p>Sous garantie : <b><?php echo $garantieSous; ?></b></p>
          <p>Hors garantie : <b><?php echo $garantieHors; ?></b></p>
          <p>Probabilité de tomber sur une machine hors garantie : <b><?php echo round($probGarantieHors, 2); ?>%</b></p>
        </div>

        <div class="stat-box">
          <h3>Répartition des CPU</h3>
          <?php 
            $maxCpu = max($cpuCounts);
            foreach ($cpuCounts as $cpu => $count):
              $width = ($count / $maxCpu) * 100;
          ?>
          <div class="bar-row">
            <span><?php echo htmlspecialchars($cpu); ?></span>
            <div class="bar">
              <div class="bar-fill" style="width: <?php echo $width; ?>%;"></div>
            </div>
            <span><?php echo $count; ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="card">
      <h2>Moniteurs</h2>
      <div class="grid">
        <div class="stat-box">
          <h3>Connectiques les plus utilisées</h3>
          <?php foreach ($connectorCounts as $connector => $count): ?>
            <p><?php echo htmlspecialchars($connector); ?> : <b><?php echo $count; ?></b></p>
          <?php endforeach; ?>
        </div>
        <div class="stat-box">
          <h3>Répartition des tailles d’écran</h3>
          <?php foreach ($sizeCounts as $size => $count): ?>
            <p><?php echo $size; ?> pouces : <b><?php echo $count; ?></b></p>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="card">
      <h2>Connexions</h2>
      <div class="grid">
        <div class="stat-box">
          <h3>Top utilisateur (connexion)</h3>
          <p><?php echo htmlspecialchars($topUser); ?> : <b><?php echo $topUserCount; ?></b> connexions</p>
          <p>Probabilité d’une connexion par cet utilisateur : <b><?php echo round($topUserProb, 2); ?>%</b></p>
        </div>
        <div class="stat-box">
          <h3>Durée moyenne des sessions</h3>
          <p><b><?php echo $avgDuration; ?></b> secondes</p>
        </div>
      </div>
    </section>
  </main>
</body>
</html>

