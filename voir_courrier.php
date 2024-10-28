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

    // Préparer la requête pour récupérer les détails du courrier
    $sql = "SELECT * FROM listes_de_courrier WHERE id = ?";

    // Préparer l'instruction
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Lier les variables
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Exécuter l'instruction
        mysqli_stmt_execute($stmt);

        // Obtenir le résultat
        $result = mysqli_stmt_get_result($stmt);
        $courrier = mysqli_fetch_assoc($result);

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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voir les instruction</title>
</head>
<body>
    <h2>Voir les instructions</h2>
    <?php if ($courrier): ?>
        <p><b>ID:</b> <?php echo $courrier['id']; ?></p>
        <p><b>Type de courrier:</b> <?php echo $courrier['intitule']; ?></p>
        <?php if ($courrier['intitule'] == 'Autre'): ?>
            <p><b>Autre Type de courrier:</b> <?php echo $courrier['autre_intitule']; ?></p>
        <?php endif; ?>
        <p><b>Provenance:</b> <?php echo $courrier['provenance']; ?></p>
        <?php if ($courrier['provenance'] == 'Autre'): ?>
            <p><b>Autre Provenance:</b> <?php echo $courrier['autre_provenance']; ?></p>
        <?php endif; ?>
        <p><b>Date de réception:</b> <?php echo $courrier['date_reception']; ?></p>
        <p><b>État:</b> <?php echo $courrier['etat']; ?></p>
        <?php if ($courrier['etat'] == 'Non traité'): ?>
            <p><b>Raison du non traitement:</b> <?php echo $courrier['raison_non_traitement']; ?></p>
        <?php endif; ?>
        <p><b>Commentaires:</b> <?php echo $courrier['commentaires']; ?></p>
        <a href="modifier_courrier.php?id=<?php echo $courrier['id']; ?>">Modifier</a>
        <a href="supprimer_courrier.php?id=<?php echo $courrier['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier?');">Supprimer</a>
    <?php else: ?>
        <p>Instructions ou demandes non trouvé.</p>
    <?php endif; ?>
</body>
</html>
