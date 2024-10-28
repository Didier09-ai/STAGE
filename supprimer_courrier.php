<?php
// Paramètres de connexion
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";

// Connexion à MySQL
$conn = mysqli_connect("mysql-didier.alwaysdata.net", "didier", "Didiergbedan@123", "didier_db");
// Vérification de la connexion
if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Vérifier si l'ID est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête de suppression
    $sql = "DELETE FROM listes_de_courrier WHERE id = ?";

    // Préparer l'instruction
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Lier les variables
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Exécuter l'instruction
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Courrier supprimé avec succès !!!');</script>";
            header('Refresh:0.1;url=affichage_courrier.php');
            exit(); // Assurez-vous de quitter après redirection
        } else {
            echo "<script>alert('Échec de la suppression du courrier !!!');</script>";
        }

        // Fermer l'instruction
        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur de préparation de la requête : " . mysqli_error($conn);
    }
} else {
    echo "ID du courrier manquant.";
}

// Fermer la connexion
mysqli_close($conn);
?>
