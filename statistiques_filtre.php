<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";
$conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $db);

if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Fonction pour récupérer les statistiques filtrées par date ou par recherche
function getStatistics($conn, $startDate = '', $endDate = '', $search = '') {
    $conditions = [];

    if (!empty($startDate)) {
        $conditions[] = "lc.date_reception >= '$startDate'";
    }

    if (!empty($endDate)) {
        $conditions[] = "lc.date_reception <= '$endDate'";
    }

    if (!empty($search)) {
        $conditions[] = "(
            lc.intitule LIKE '%$search%' OR 
            lc.provenance LIKE '%$search%' OR 
            lc.date_reception LIKE '%$search%' OR 
            lc.raison_non_traitement LIKE '%$search%'
        )";
    }

    $whereClause = '';
    if (!empty($conditions)) {
        $whereClause = 'WHERE ' . implode(' AND ', $conditions);
    }

    $sql = "
        SELECT 
            lc.intitule,
            lc.provenance,
            lc.date_reception,
            COUNT(lc.id) AS nombre_total,
            SUM(CASE WHEN lc.etat = 'Traité' THEN 1 ELSE 0 END) AS nombre_traite,
            SUM(CASE WHEN lc.etat = 'Non Traité' THEN 1 ELSE 0 END) AS nombre_non_traite,
            lc.raison_non_traitement
        FROM listes_de_courrier lc
        $whereClause
        GROUP BY lc.intitule, lc.provenance, lc.date_reception, lc.raison_non_traitement
        ORDER BY lc.intitule ASC, lc.provenance ASC, lc.date_reception ASC
    ";

    $result = mysqli_query($conn, $sql);
    $instructions = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $intitule = $row['intitule'];
        $provenance = $row['provenance'];
        $date_reception = $row['date_reception'];
        $raison_non_traitement = $row['raison_non_traitement'];

        if (!isset($instructions[$intitule])) {
            $instructions[$intitule] = [
                'dates' => []
            ];
        }

        if (!isset($instructions[$intitule]['dates'][$date_reception])) {
            $instructions[$intitule]['dates'][$date_reception] = [
                'nombre_total' => 0,
                'nombre_traite' => 0,
                'nombre_non_traite' => 0,
                'raisons_non_traitement' => [],
                'provenances' => []
            ];
        }

        $instructions[$intitule]['dates'][$date_reception]['nombre_total'] += $row['nombre_total'];
        $instructions[$intitule]['dates'][$date_reception]['nombre_traite'] += $row['nombre_traite'];
        $instructions[$intitule]['dates'][$date_reception]['nombre_non_traite'] += $row['nombre_non_traite'];

        if ($row['nombre_non_traite'] > 0 && $raison_non_traitement) {
            if (!isset($instructions[$intitule]['dates'][$date_reception]['raisons_non_traitement'][$raison_non_traitement])) {
                $instructions[$intitule]['dates'][$date_reception]['raisons_non_traitement'][$raison_non_traitement] = [];
            }
            $instructions[$intitule]['dates'][$date_reception]['raisons_non_traitement'][$raison_non_traitement][] = $provenance;
        }

        if ($provenance) {
            if (!isset($instructions[$intitule]['dates'][$date_reception]['provenances'][$provenance])) {
                $instructions[$intitule]['dates'][$date_reception]['provenances'][$provenance] = 0;
            }
            $instructions[$intitule]['dates'][$date_reception]['provenances'][$provenance] += $row['nombre_total'];
        }
    }

    return $instructions;
}

// Récupération des statistiques initiales
$instructions = getStatistics($conn);

if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
    $startDate = isset($_POST['startDate']) ? mysqli_real_escape_string($conn, $_POST['startDate']) : '';
    $endDate = isset($_POST['endDate']) ? mysqli_real_escape_string($conn, $_POST['endDate']) : '';
    $search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';

    $instructions = getStatistics($conn, $startDate, $endDate, $search);

    ob_start();
    include 'stats_table.php';
    $tableHTML = ob_get_clean();

    echo json_encode(['tableHTML' => $tableHTML]);
    exit;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques des Instructions</title>
    <style>
        /* Le style de la page reste inchangé */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
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
            background-color: #ecf0f1;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: rgb(49, 63, 65);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #fff;
            text-align: center;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-input, .date-input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px 0;
            flex: 1 1 200px;
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
            background-color: #3498db; /* Choisissez la couleur de fond souhaitée */
            color: #ffffff; /* Couleur du texte */
            padding: 10px; /* Pour ajouter un peu de padding autour du texte */
            border-radius: 10px; /* Pour arrondir légèrement les coins */
        }

        .sidebar ul li a[href="statistiques.php"]:hover {
            background-color: #2980b9; /* Couleur de fond au survol */
            color: #ffffff; /* Couleur du texte au survol */
        }


        .download-button {
            display: flex;
            justify-content: center;
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

            .search-container {
                flex-direction: column;
            }

            .search-input, .date-input {
                width: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">Tableau de Bord</div>
        <ul>
        <li><a href="tableau_de_bord.php">Accueil</a></li>
            <li><a href="affichage_courrier.php">Instructions</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Statistiques des Instructions</h1>
        </header>

        <div class="search-container">
            <input type="text" id="search" class="search-input" placeholder="Rechercher...">
            <label for="startDate"><strong>De:</strong></label>
            <input type="date" id="startDate" class="date-input" max="<?= date('Y-m-d') ?>">
            <label for="endDate"><strong>À:</strong></label>
            <input type="date" id="endDate" class="date-input" max="<?= date('Y-m-d') ?>">
        </div>

        <div id="table-container">
            <?php include 'stats_table.php'; ?>
        </div>

        <!-- <div class="download-button">
    <form id="downloadForm" action="generate_pdf.php" method="post" target="_blank">
        <input type="hidden" id="startDateField" name="startDate" value="<?php echo htmlspecialchars($startDate); ?>">
        <input type="hidden" id="endDateField" name="endDate" value="<?php echo htmlspecialchars($endDate); ?>">
        <input type="hidden" id="searchQueryField" name="searchQuery" value="<?php echo htmlspecialchars($searchQuery); ?>">
        <button type="submit">Télécharger les statistiques en PDF</button>
    </form>
</div> -->
    </div>

    <script>
    document.getElementById('downloadForm').addEventListener('submit', function() {
        document.getElementById('startDateField').value = document.getElementById('startDate').value;
        document.getElementById('endDateField').value = document.getElementById('endDate').value;
        document.getElementById('searchQueryField').value = document.getElementById('search').value;  // Assurez-vous que 'search' est l'ID correct du champ de recherche
    });
</script>

    <script>
        document.getElementById('search').addEventListener('keyup', function() {
            var searchValue = this.value.toLowerCase();
            var rows = document.querySelectorAll('#statsTable tbody tr');

            rows.forEach(function(row) {
                var cells = row.getElementsByTagName('td');
                var found = false;

                for (var i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().indexOf(searchValue) > -1) {
                        found = true;
                        break;
                    }
                }

                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        var startDateInput = document.getElementById('startDate');
        var endDateInput = document.getElementById('endDate');
        
       

        

        var today = new Date().toISOString().split('T')[0];
        startDateInput.setAttribute('max', today);
        endDateInput.setAttribute('max', today);

        startDateInput.addEventListener('change', function() {
            endDateInput.setAttribute('min', this.value);
            fetchFilteredData();
        });

        endDateInput.addEventListener('change', function() {
            startDateInput.setAttribute('max', this.value);
            fetchFilteredData();
        });

        function fetchFilteredData() {
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;

            
            

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'statistiques_filtre.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    console.log(response);
                    
                    document.getElementById('table-container').innerHTML = response.tableHTML;
                }
            };
            xhr.send('ajax=1&startDate=' + startDate + '&endDate=' + endDate);
        }
    </script>
</body>
</html>
