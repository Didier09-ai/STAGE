<?php 
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formulaire";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
} else {
    // echo "connexion réussir";
}

// Récupération des données
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $intitule = $_POST['intitule'];
    // $demande_reçu = $_POST['demande_reçu'];
    // $demande_traite = $_POST['demande_traite'];
    // $demande_non_traite = $_POST['demande_non_traite'];
    // $raison_non_traitement = $_POST['raison_non_traitement'];
    $provenance = $_POST['provenance'];
    $date = $_POST['date_reception'];
    $commentaires = $_POST['commentaires'];

    // Insertion des données dans la base de données
    $sql = "INSERT INTO `formulaire`(`id`,`intitule', `provenance`,'date_reception','commentaires',) 
            VALUES ('$id','$intitule', '$provenance', $date, $commentaires )";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Ajout réussi avec succès !!!')</script>";
        header('Refresh:0.1;url=listes_des_courrier.php');
    } else {
        echo "<script>alert('Ajout échoué !!!')</script>";
        header('Refresh:0.1;url=formulaire.php');
    }
}

// Fermer la connexion
$conn->close();
?>
