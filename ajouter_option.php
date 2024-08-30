<?php
// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";
$conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $db);

if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $valeur = mysqli_real_escape_string($conn, $_POST['valeur']);

    if ($type == 'courrier') {
        $sql = "INSERT INTO courrier_types (type_courrier) VALUES ('$valeur')";
    } elseif ($type == 'provenance') {
        $sql = "INSERT INTO provenance_types (type_provenance) VALUES ('$valeur')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "Option ajoutée avec succès";
    } else {
        echo "Erreur: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
