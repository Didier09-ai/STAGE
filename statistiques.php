<?php
require 'vendor/autoload.php';

// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";
$conn = mysqli_connect("mysql-didier.alwaysdata.net", "didier", "Didiergbedan@123", "didier_db");
if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Fonction pour récupérer les statistiques
function getStatistics($conn) {
    $sql = "
        SELECT 
            lc.intitule,
            lc.provenance,
            COUNT(lc.id) AS nombre_total,
            SUM(CASE WHEN lc.etat = 'Traité' THEN 1 ELSE 0 END) AS nombre_traite,
            SUM(CASE WHEN lc.etat = 'Non Traité' THEN 1 ELSE 0 END) AS nombre_non_traite,
            lc.raison_non_traitement
        FROM listes_de_courrier lc
        GROUP BY lc.intitule, lc.provenance, lc.raison_non_traitement
        ORDER BY lc.intitule ASC, lc.provenance ASC
    ";

    $result = mysqli_query($conn, $sql);
    $instructions = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $intitule = $row['intitule'];
        $provenance = $row['provenance'];
        $raison_non_traitement = $row['raison_non_traitement'];

        if (!isset($instructions[$intitule])) {
            $instructions[$intitule] = [
                'nombre_total' => 0,
                'nombre_traite' => 0,
                'nombre_non_traite' => 0,
                'raisons_non_traitement' => [],
                'provenances' => []
            ];
        }

        $instructions[$intitule]['nombre_total'] += $row['nombre_total'];
        $instructions[$intitule]['nombre_traite'] += $row['nombre_traite'];
        $instructions[$intitule]['nombre_non_traite'] += $row['nombre_non_traite'];

        if ($row['nombre_non_traite'] > 0 && $raison_non_traitement) {
            if (!isset($instructions[$intitule]['raisons_non_traitement'][$raison_non_traitement])) {
                $instructions[$intitule]['raisons_non_traitement'][$raison_non_traitement] = [];
            }
            $instructions[$intitule]['raisons_non_traitement'][$raison_non_traitement][] = $provenance;
        }

        if ($provenance) {
            if (!isset($instructions[$intitule]['provenances'][$provenance])) {
                $instructions[$intitule]['provenances'][$provenance] = 0;
            }
            $instructions[$intitule]['provenances'][$provenance] += $row['nombre_total'];
        }
    }

    return $instructions;
}

// Récupération des statistiques
$instructions = getStatistics($conn);

// Fermeture de la connexion
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Instructions</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            background-color: #ecf0f1;
        }

        .sidebar {
            width: 250px;
            background-color: rgb(49, 63, 65);
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .sidebar .logo {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        header {
            background-color: rgb(49, 63, 65);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: white;
            text-align: center;
        }

        section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h2 {
            color: rgb(49, 63, 65);
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            background-color: rgb(206, 213, 212);
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: rgb(107, 143, 146);
            color: white;
        }

        .sidebar ul li a[href="statistiques.php"] {
            background-color: #3498db;
            color: #ffffff;
            padding: 10px;
            border-radius: 10px;
        }

        .sidebar ul li a[href="statistiques.php"]:hover {
            background-color: #2980b9;
            color: #ffffff;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .add-button {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .add-button button {
            background-color: rgb(0, 133, 89);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .add-button button:hover {
            background-color: rgb(0, 102, 69);
        }

        .download-button {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .download-button button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .download-button button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 10px;
            }

            .main-content {
                padding: 10px;
            }
        }

        /* Animation fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .fade-in-1 {
            animation-delay: 0.3s;
        }

        .fade-in-2 {
            animation-delay: 0.6s;
        }
    </style>
</head>
<body>
    <div class="sidebar fade-in fade-in-1">
        <div class="logo">Tableau de Bord</div>
        <ul>
            <li><a href="tableau_de_bord.php">Accueil</a></li>
            <li><a href="affichage_courrier.php">Instructions</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="fade-in fade-in-1">
            <h1>Statistiques des Instructions</h1>
        </header>

        <section class="container fade-in fade-in-2">
            <h2>Tableau des Statistiques</h2>
            <table>
                <tr>
                    <th>N°</th>
                    <th>Intitulé</th>
                    <th>Demande Reçu</th>
                    <th>Demande Traité</th>
                    <th>Demande Non Traité</th>
                    <th>Raison de Non Traitement</th>
                    <th>Provenance</th>
                </tr>
                <?php
                $num = 1;
                foreach ($instructions as $intitule => $data) {
                    echo '<tr>';
                    echo '<td>' . $num++ . '</td>';
                    echo '<td>' . $intitule . '</td>';
                    echo '<td>' . $data['nombre_total'] . '</td>';
                    echo '<td>' . $data['nombre_traite'] . ' (' . round(($data['nombre_traite'] / $data['nombre_total']) * 100, 2) . '%)</td>';
                    echo '<td>' . $data['nombre_non_traite'] . ' (' . round(($data['nombre_non_traite'] / $data['nombre_total']) * 100, 2) . '%)</td>';

                    $raisons = [];
                    foreach ($data['raisons_non_traitement'] as $raison => $provenances) {
                        $raisons[] = $raison . ' : ' . implode(', ', $provenances);
                    }
                    echo '<td>' . implode('<br>', $raisons) . '</td>';

                    $provs = [];
                    foreach ($data['provenances'] as $prov => $count) {
                        $provs[] = $prov . ' : ' . $count;
                    }
                    echo '<td>' . implode('<br>', $provs) . '</td>';

                    echo '</tr>';
                }
                ?>
            </table>
            <div class="download-button">
                <form action="generate_pdf.php" method="post">
                    <button type="submit">Télécharger PDF</button>
                </form>
            </div>
            <div class="button">
                <form action="statistiques_filtre.php" method="">
                    <button type="submit">Filtrer</button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
