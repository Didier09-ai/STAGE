<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Courrier</title>
    <style>
        body {
            background-color:#b3c1c9;
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: yellow; 
            color: black;
            border: none;
        position: center;
        left: 60%;
        box-sizing:border-box;
        border-radius:  0.5rem;
        border-top:  0;
        border-bottom: 0;
        border-right: 0;
        border-left: 0;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Ajouter un Courrier</h2>
    <form action="traitemen.php" method="post">
    <label for="intitule"><b>Type du courrier:</b></label>
    <select id="intitule" name="intitule" type="text">
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
    <option value="Invitations (atelier, séminaire, formation, validation, etc..)">Invitations (atelier, séminaire, formation, validation, etc..)</option>
  </select><br>
    <!-- <input type="text" id="intitule" name="intitule" required> -->

    <!-- <label for="demande_recue"><b>Nombre de demandes reçues:</b></label>
    <input type="number" id="demande_recue" name="demande_recue" required>

    <label for="demande_traite"><b>Nombre de demandes traitées:</b></label>
    <input type="number" id="demande_traite" name="demande_traite" required>

    <label for="demande_non_traite"><b>Nombre de demandes non traitées:</b></label>
    <input type="number" id="demande_non_traite" name="demande_non_traite" required>

    <label for="raison_non_traitement"><b>Raison du non traitement:</b></label>
    <textarea id="raison_non_traitement" name="raison_non_traitement" rows="4"></textarea> -->

    <label for="provenance"><b>Provenance:</b></label>
    <select id="provenance" name="provenance" type="text">
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
    <option value="Autre">Autre</option>
  </select><br><br>

    <label for="date_reception"><b>Date:</b></label>
    <input type="date" id="date_reception" name="date_reception" required>

    <label for="commentaires"><b>Commentaires:</b></label>
    <textarea id="commentaires" name="commentaires" placeholder="Veuillez renseignez plus d'information sur votre courrier" rows="4" required></textarea>
    <input type="submit" value="Ajouter">
    </form>
</body>
</html>

<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulaire";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intitule = $_POST['intitule'];
    $provenance = $_POST['provenance'];
    $date_reception = $_POST['date_reception'];
    $commentaires = $_POST['commentaires'];

    $sql = "INSERT INTO courriers (intitule, provenance, date_reception, commentaires) VALUES ('$intitule', '$provenance', '$date_reception', '$commentaires')";

    if ($conn->query($sql) === TRUE) {
        echo "Nouveau courrier ajouté avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>