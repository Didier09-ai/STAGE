<?php
// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";
$conn = mysqli_connect("mysql-didier.alwaysdata.net", "didier", "Didiergbedan@123", "didier_db");

if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Récupérer l'ID du courrier depuis la requête GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Préparer la requête pour récupérer les données
$sql = "SELECT * FROM listes_des_courriers WHERE id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $intitule = $row['intitule'];
        $provenance = $row['provenance'];
        $date_reception = $row['date_reception'];
        $etat = $row['etat'];
        $raison_non_traitement = $row['raison_non_traitement'];
        $commentaires = $row['commentaires'];
    } else {
        echo "Aucun courrier trouvé.";
        exit();
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Erreur de préparation de la requête.";
    exit();
}

mysqli_close($conn);
?>
