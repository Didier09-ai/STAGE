<?php
// Paramètres de connexion
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";

// Connexion à MySQL
$conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $db);

// Vérification de la connexion
if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Requête pour récupérer les données
$sql = "SELECT id, intitule, autre_intitule, provenance, autre_provenance, date_reception, etat, raison_non_traitement, commentaires FROM listes_de_courrier";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="styles.css">
    
    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
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

        .sidebar .icon-home {
            font-size: 24px;
            margin-right: 10px;
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

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        header {
            background-color: rgb(49, 63, 65);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        h2 {
            color: rgb(206, 213, 212);
            text-align: center;
            padding: 12px;
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

        .sidebar ul li a[href="affichage_courrier.php"] {
            background-color: #3498db;
            color: #ffffff;
            padding: 10px;
            border-radius: 10px;
        }

        .sidebar ul li a[href="affichage_courrier.php"]:hover {
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

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            text-decoration: none;
        }

        .add-button {
            margin-bottom: 20px;
        }

        .add-button button {
            background-color: rgb(0, 133, 89);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            position: relative; 
            left: 730px;
        }

        .actions .view-button {
            background-color: white;
            color: black;
            border: 1px solid black;
            padding: 5px 10px;
        }

        .actions .edit-button {
            background-color: blue;
            color: white;
            border: none;
            padding: 5px 10px;
        }

        .actions .delete-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
        }

        /* Effets d'entrée pour les éléments */
        header, section, table, .sidebar, .add-button {
            opacity: 0;
            animation: fadeIn 1.5s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar animate__animated animate__fadeInLeft">
        <div class="logo">
            <i class="icon-home"></i> Tableau de Bord
        </div>
        <ul>
            <li><a href="tableau_de_bord.php">Accueil</a></li>
            <li><a href="affichage_courrier.php">Instructions</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header class="animate__animated animate__fadeInDown">
            <h2>Liste des instructions</h2>
        </header>
        <section class="animate__animated animate__fadeInUp">
            <a href="Courrier.php" class="add-button"><button class="animate__animated animate__fadeIn">Ajouter</button></a>

            <table class="animate__animated animate__fadeInUp">
                <tr>
                    <th>ID</th>
                    <th>Type de courrier</th>
                    <th>Provenance</th>
                    <th>Date de réception</th>
                    <th>État</th>
                    <th>Raison de non traitement</th>
                    <th>Commentaires</th>
                    <th>Actions</th>
                </tr>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    // Afficher les données pour chaque ligne
                    $index = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr class='animate__animated animate__fadeInUp'>";
                        echo "<td>" . $index . "</td>";
                        echo "<td>" . ($row["intitule"] === "Autre" ? $row["autre_intitule"] : $row["intitule"]) . "</td>";
                        echo "<td>" . ($row["provenance"] === "Autre" ? $row["autre_provenance"] : $row["provenance"]) . "</td>";
                        echo "<td>" . $row["date_reception"] . "</td>";
                        echo "<td>" . $row["etat"] . "</td>";
                        echo "<td>" . ($row["etat"] === "Traité" ? "" : $row["raison_non_traitement"]) . "</td>";
                        echo "<td>" . $row["commentaires"] . "</td>";
                        echo "<td class='actions'>";
                        echo "<a href='voir_courrier.php?id=" . $row["id"] . "' class='view-button'>Voir</a>";
                        echo "<a href='modifie_courrier.php?id=" . $row["id"] . "' class='edit-button'>Modifier</a>";
                        echo "<a href='supprimer_courrier.php?id=" . $row["id"] . "' class='delete-button' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer ce courrier?');\">Supprimer</a>";
                        echo "</td>";
                        echo "</tr>";
                        $index++;
                    }
                } else {
                    echo "<tr><td colspan='8'>Aucun courrier trouvé</td></tr>";
                }
                ?>
            </table>
        </section>
    </div>
</body>
</html>
<?php
// Fermer la connexion
mysqli_close($conn);
?>
