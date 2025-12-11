<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sysadmin') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Administrateur Système</title>
    <link rel="stylesheet" href="../style/styles.css">
    <style>
        /* Style spécifique pour le tableau de bord admin */
        .card {
            padding: 1rem;
            border-radius: 0.75rem;
            background: #fafafa;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .card h2 {
            margin-bottom: 1rem;
        }

        .btn {
            background-color: #4caf50;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .nav ul {
            display: flex;
            list-style: none;
            padding: 0;
            justify-content: flex-start;
        }

        .nav ul li {
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <header class="nav">
        <div class="brand">
            <a href="../index.php" class="brand-link">
                <img src="../images/logo_sae.webp" alt="Logo" />
            </a>
            <span class="badge">Administrateur Système</span>
        </div>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../logout.php" class="btn secondary">Déconnexion</a></li>
        </ul>
    </header>

    <main class="container">
        <section class="card">
            <h2>Bienvenue, Administrateur Système</h2>
            <p>Vous avez accès à toutes les fonctionnalités d'administration du système.</p>
            <p>Utilisez le bouton ci dessous pour accéder au jounal d'activités</p>
        </section>

        <section class="card">
            <h2>Gestion du système</h2>
            <ul>
                <li><a href="logs.php" class="btn">Voir les journaux d'activité</a></li>
            </ul>
        </section>
    </main>
</body>
</html>
