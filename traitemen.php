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

// Vérifier la méthode de requête
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $intitule = $_POST['intitule'];
    $autre_intitule = $_POST['autre_intitule'] ?? null;
    $provenance = $_POST['provenance'];
    $autre_provenance = $_POST['autre_provenance'] ?? null;
    $date_reception = $_POST['date_reception'];
    $etat = $_POST['etat'];
    $raison_non_traitement = $_POST['raison_non_traitement'] ?? null;
    $commentaires = $_POST['commentaires'];

    // Préparer la requête SQL
    $sql = "INSERT INTO listes_de_courrier (intitule, autre_intitule, provenance, autre_provenance, date_reception, etat, raison_non_traitement, commentaires) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Préparer l'instruction
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Lier les variables
        mysqli_stmt_bind_param($stmt, "ssssssss", $intitule, $autre_intitule, $provenance, $autre_provenance, $date_reception, $etat, $raison_non_traitement, $commentaires);

        // Exécuter l'instruction
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Ajout réussi avec succès !!!');</script>";
            header('Refresh:0.1;url=affichage_courrier.php');
            exit(); // Assurez-vous de quitter après redirection
        } else {
            echo "<script>alert('Ajout échoué !!!');</script>";
            header('Refresh:0.1;url=traitemen.php');
            exit(); // Assurez-vous de quitter après redirection
        }

        // Fermer l'instruction
        mysqli_stmt_close($stmt);
    } else {
        // Gestion des erreurs de préparation
        echo "<script>alert('Erreur de préparation de la requête !!!');</script>";
        echo "Erreur : " . mysqli_error($conn); // Affiche l'erreur SQL pour le débogage
    }
}

// Fermer la connexion
mysqli_close($conn);
?>
