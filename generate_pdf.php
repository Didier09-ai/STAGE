<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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

// Création du PDF avec dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('defaultFont', 'Times');
$dompdf = new Dompdf($options);

// Récupérer l'année actuelle
$currentYear = date('Y');

// Chemin absolu du logo
$logoPath = realpath(__DIR__ . '/MESRS 2.png');

// Création du contenu HTML
$html = '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: "Times", serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
        .logo { text-align: center; margin-bottom: 20px; }
        .logo img { max-width: 100px; }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="' . $logoPath . '" alt="Logo">
        </div>
    </header>
    <h1>POINT DES INSTRUCTIONS REÇUES ET NIVEAU DE LEUR EXECUTION (Taux d’exécution) ANNEE ' . $currentYear . ' A LA DSI</h1>
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Intitulé</th>
                <th>Demande Reçu</th>
                <th>Demande Traité</th>
                <th>Demande Non Traité</th>
                <th>Raison de Non Traitement</th>
                <th>Provenance</th>
            </tr>
        </thead>
        <tbody>';

$num = 1;
foreach ($instructions as $intitule => $data) {
    $nombre_total = $data['nombre_total'];
    $nombre_traite = $data['nombre_traite'];
    $nombre_non_traite = $data['nombre_non_traite'];

    $pourcentage_traite = $nombre_total > 0 ? round(($nombre_traite / $nombre_total) * 100, 2) : 0;
    $pourcentage_non_traite = $nombre_total > 0 ? round(($nombre_non_traite / $nombre_total) * 100, 2) : 0;

    $raison_text = "";
    foreach ($data['raisons_non_traitement'] as $raison => $provenances) {
        $provenance_list = implode(", ", $provenances);
        $raison_text .= "{$raison} : {$provenance_list}<br>";
    }

    $provenance_text = "";
    foreach ($data['provenances'] as $provenance => $nombre) {
        $provenance_text .= "{$provenance} : {$nombre}<br>";
    }

    $html .= "<tr>
        <td>{$num}</td>
        <td>{$intitule}</td>
        <td>{$nombre_total}</td>
        <td>{$nombre_traite} ({$pourcentage_traite}%)</td>
        <td>{$nombre_non_traite} ({$pourcentage_non_traite}%)</td>
        <td>{$raison_text}</td>
        <td>{$provenance_text}</td>
    </tr>";

    $num++;
}

$html .= '</tbody>
    </table>
</body>
</html>';

// Charger le HTML dans dompdf
$dompdf->loadHtml($html);

// (Optional) Configurez la taille du papier et l'orientation
$dompdf->setPaper('A4', 'portrait');

// Rendre le PDF
$dompdf->render();

// Télécharger le fichier PDF
$dompdf->stream('Statistiques.pdf', array('Attachment' => 1));
?>
