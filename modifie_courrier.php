<?php
$conn = mysqli_connect('localhost', 'root', '', 'formulaire');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $intitule = $_POST['intitule'] == 'Autre' ? $_POST['autre_intitule'] : $_POST['intitule'];
    $provenance = $_POST['provenance'] == 'Autre' ? $_POST['autre_provenance'] : $_POST['provenance'];
    $date_reception = $_POST['date_reception'];
    $etat = $_POST['etat'];
    $raison_non_traitement = $_POST['raison_non_traitement'] ?? '';
    $commentaires = $_POST['commentaires'];

    // Mise à jour dans la base de données avec une requête préparée
    $stmt = mysqli_prepare($conn, "UPDATE listes_de_courrier SET intitule=?, provenance=?, date_reception=?, etat=?, raison_non_traitement=?, commentaires=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssssssi", $intitule, $provenance, $date_reception, $etat, $raison_non_traitement, $commentaires, $id);
    mysqli_stmt_execute($stmt);

    // Enregistrement des nouveaux intitulés et provenances si nécessaire
    if ($_POST['intitule'] == 'Autre' && !empty($_POST['autre_intitule'])) {
        $nouvel_intitule = mysqli_real_escape_string($conn, $_POST['autre_intitule']);
        $stmt = mysqli_prepare($conn, "INSERT IGNORE INTO courrier_types (type_courrier) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $nouvel_intitule);
        mysqli_stmt_execute($stmt);
    }

    if ($_POST['provenance'] == 'Autre' && !empty($_POST['autre_provenance'])) {
        $nouvelle_provenance = mysqli_real_escape_string($conn, $_POST['autre_provenance']);
        $stmt = mysqli_prepare($conn, "INSERT IGNORE INTO provenance_types (type_provenance) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $nouvelle_provenance);
        mysqli_stmt_execute($stmt);
    }

    mysqli_close($conn);
    header('Location: affichage_courrier.php');
    exit();
} else {
    $id = $_GET['id'];
    $stmt = mysqli_prepare($conn, "SELECT * FROM listes_de_courrier WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $listes_de_courrier = mysqli_fetch_assoc($result);

    // Requêtes pour récupérer les intitulés et provenances
    $result_intitule = mysqli_query($conn, "SELECT DISTINCT intitule FROM listes_de_courrier");
    $result_provenance = mysqli_query($conn, "SELECT DISTINCT provenance FROM listes_de_courrier");
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Modifier Courrier</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        header {
            background-color: rgb(206, 213, 212);
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
            background-color: rgb(49, 63, 65);
            color: rgb(206, 213, 212);
            text-align: center;
            padding: 12px;
        }

        label {
            display: block;
            margin-top: 1px;
        }

        input, select, textarea {
            width: 75%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"], input[type="button"] {
            background-color: rgb(0, 133, 89);
            color: white;
            border: none;
            box-sizing: border-box;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: rgb(0, 133, 89);
        }
    </style> 
</head>
<body>
    <div class="sidebar">
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
        <header>
    <h1>Modifier le Courrier</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($listes_de_courrier['id']); ?>">

        <label for="intitule"><b>Intitulé:</b></label>
        <select id="intitule" name="intitule" required>
            <option value="" disabled>Choisir un type de courrier</option>
            <?php while ($row = mysqli_fetch_assoc($result_intitule)): ?>
                <option value="<?php echo htmlspecialchars($row['intitule']); ?>" <?php echo $listes_de_courrier['intitule'] == $row['intitule'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['intitule']); ?>
                </option>
            <?php endwhile; ?>
            <option value="Demande d'avis technique et autres appuis sollicités">Demande d'avis technique et autres appuis sollicités</option>
    <option value="Lettres d'informations (Notes de service, Nominations, comptes rendus de réunions, arrêtés, etc.…)">Lettres d'informations (Notes de service, Nominations, comptes rendus de réunions, arrêtés, etc.…)</option>
    <option value="Communiqués de bourses pour les étudiants à publier sur le site Web du MESRS">Communiqués de bourses pour les étudiants à publier sur le site Web du MESRS</option>
    <option value="Demande de Stage">Demande de Stage</option>
    <option value="Demande d'appel à candidature au poste">Demande d'appel à candidature au poste</option>
    <option value="Demande d'audience">Demande d'audience</option>
    <option value="Gestion des carrières du personnel de la DSI">Gestion des carrières du personnel de la DSI</option>
    <option value="Classement et sélection des bacheliers de 2023 (Demandes de réclamation)">Classement et sélection des bacheliers de 2023 (Demandes de réclamation)</option>
    <option value="Validation et réception de rapports d’études (Enquête de satisfaction des usagers, ProDES, P2D, Conception d’un guide de budgétisation équitable et sensible au genre au MESRS)">Validation et réception de rapports d’études (Enquête de satisfaction des usagers, ProDES, P2D, Conception d’un guide de budgétisation équitable et sensible au genre au MESRS)</option>
    <option value="Ouverture et analyse des offres relatives aux marchés publics">Ouverture et analyse des offres relatives aux marchés publics</option>
    <option value="Rapport de performance + Revue annuelle PTA">Rapport de performance + Revue annuelle PTA</option>
    <option value="Réception des matériels informatiques et consommables">Réception des matériels informatiques et consommables</option>
           
            <option value="Autre">Autre (Veuillez spécifier ci-dessous)</option>
        </select>
        <input type="text" id="autre_intitule" name="autre_intitule" placeholder="Autre type de courrier" style="display:none;">

        <label for="provenance"><b>Provenance:</b></label>
        <select id="provenance" name="provenance" required>
            <option value="" disabled>Choisir une provenance</option>
            <?php while ($row = mysqli_fetch_assoc($result_provenance)): ?>
                <option value="<?php echo htmlspecialchars($row['provenance']); ?>" <?php echo $listes_de_courrier['provenance'] == $row['provenance'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['provenance']); ?>
                </option>
            <?php endwhile; ?>
            <option value="UAC">UAC</option>
            <option value="PRMP">PRMP</option>
            <option value="DGBO">DGBO</option>
    <option value="CUEP">CUEP</option>
    <option value="DBAU">DBAU</option>
    <option value="SGM">SGM</option>
    <option value="COUS-AS">COUS-AS</option>
    <option value="COUS-P">COUS-P</option>
    <option value="DPAF">DPAF</option>
    <option value="UNA">UNA</option>
    <option value="UNSTIM">UNSTIM</option>
    <option value="DGES">DGES</option>
    <option value="DEC">DEC</option>
    <option value="DC">DC</option>
    <option value="INFOSEC">INFOSEC</option>
    <option value="ENSBAA">ENSBAA</option>
    <option value="CEFORP">CEFORP</option>
    <option value="INSTI">INSTI</option>
    <option value="IRSP">IRSP</option>
    <option value="DGRSI">DGRSI</option>
    <option value="CUEP">CUEP</option>
    <option value="DOB">DOB</option>
    <option value="ABeVRIT">ABeVRIT</option>
    <option value="INE">INE</option>
    <option value="INMAAC">INMAAC</option>
    <option value="FNRSI">FNRSIT</option>
    <option value="ENSGMM">ENSGMM</option>
    <option value="IDRC-CRDI">IDRC-CRDI</option>
    <option value="FORAM Initiatives">FORAM Initiatives</option>
    <option value="GNV SYSTEM">GNV SYSTEM</option>
    <option value="Cercle des docteurs">Cercle des docteurs</option>
    <option value="LE REVELATEUR">LE REVELATEUR</option>
    <option value="SP/MESRS">SP/MESRS</option>
    <option value="Intéressés">Intéressés</option>
    <option value="IGM">IGM</option>
    <option value="DG/CBRSI">DG/CBRSI</option>
    <option value="CoE-EIE">CoE-EIE</option>
            <option value="Autre">Autre (Veuillez spécifier ci-dessous)</option>
        </select>
        <input type="text" id="autre_provenance" name="autre_provenance" placeholder="Autre provenance" style="display:none;">

        <label for="date_reception"><b>Date:</b></label>
        <input type="date" id="date_reception" name="date_reception" required value="<?php echo htmlspecialchars($listes_de_courrier['date_reception']); ?>">

        <label for="etat"><b>État:</b></label>
        <select id="etat" name="etat" required>
            <option value="Non traité" <?php echo $listes_de_courrier['etat'] == 'Non traité' ? 'selected' : ''; ?>>Non traité</option>
            <option value="Traité" <?php echo $listes_de_courrier['etat'] == 'Traité' ? 'selected' : ''; ?>>Traité</option>
        </select>

        <label for="raison_non_traitement" id="raison_label" style="<?php echo $listes_de_courrier['etat'] == 'Non traité' ? '' : 'display:none;'; ?>"><b>Raison de non traitement:</b></label>
        <textarea id="raison_non_traitement" name="raison_non_traitement" placeholder="Veuillez renseigner la raison du non traitement" rows="4" style="<?php echo $listes_de_courrier['etat'] == 'Non traité' ? '' : 'display:none;'; ?>"><?php echo htmlspecialchars($listes_de_courrier['raison_non_traitement']); ?></textarea>

        <label for="commentaires"><b>Commentaires:</b></label>
        <textarea id="commentaires" name="commentaires" placeholder="Veuillez renseigner plus d'informations sur votre courrier" rows="4" required><?php echo htmlspecialchars($listes_de_courrier['commentaires']); ?></textarea>
        
        <input type="submit" value="Mettre à Jour">
    </form>

    <script>
        document.getElementById('intitule').addEventListener('change', function () {
            const autreIntitule = document.getElementById('autre_intitule');
            if (this.value === 'Autre') {
                autreIntitule.style.display = 'block';
                autreIntitule.required = true;
            } else {
                autreIntitule.style.display = 'none';
                autreIntitule.required = false;
            }
        });

        document.getElementById('provenance').addEventListener('change', function () {
            const autreProvenance = document.getElementById('autre_provenance');
            if (this.value === 'Autre') {
                autreProvenance.style.display = 'block';
                autreProvenance.required = true;
            } else {
                autreProvenance.style.display = 'none';
                autreProvenance.required = false;
            }
        });

        document.getElementById('etat').addEventListener('change', function () {
            const display = this.value === 'Non traité' ? 'block' : 'none';
            document.getElementById('raison_label').style.display = display;
            document.getElementById('raison_non_traitement').style.display = display;
        });
    </script>
    <script>
$(document).ready(function() {
    $('#intitule').on('change', function() {
        if ($(this).val() === 'Autre') {
            $('#autre_intitule').show().attr('required', true);
        } else {
            $('#autre_intitule').hide().attr('required', false);
        }
    });

    $('#provenance').on('change', function() {
        if ($(this).val() === 'Autre') {
            $('#autre_provenance').show().attr('required', true);
        } else {
            $('#autre_provenance').hide().attr('required', false);
        }
    });

    // Trigger change to handle pre-selected "Autre" values
    $('#intitule').trigger('change');
    $('#provenance').trigger('change');
});
</script>

</body>
</html>

<?php
mysqli_close($conn);
?>