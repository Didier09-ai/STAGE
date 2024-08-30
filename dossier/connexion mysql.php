<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method ="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    Nom<input type="text" name="Nom">
    Prenom<input type="text" name="Prenom">
    Adresse<input type="text" name="Adresse">
    <button ><a href="Ajouters.php"> Ajouter</a></button>
    <button><a href="Modifier fourprod.php"> Modifier</a></button>
    <button><a href="supprimer.php"> supprimer</a></button>
    </form>
    <?php
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        # code...
        $Nom=$_POST["Nom"];
        //echo "Bonjour,$Nom!";
    }
    ?>
</body>
</html>
<?php
// Paramètres de connexion
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";

// Connexion à MySQL
$connexion = mysqli_connect($serveur, $utilisateur, $mot_de_passe);
// Vérification de la connexion
if (!$connexion) {
 die("La connexion a échoué : " . mysqli_connect_error());
}
echo "Connexion réussie";

// Requête SQL pour créer une base de données
$sql = "CREATE DATABASE fourprod";
// Exécution de la requête
if (mysqli_query($connexion,$sql)) {
 echo " La Base de donnée fourprod a été créée avec succès";
} else {
 echo "Erreur lors de la création de la base de données : " . mysqli_error($connexion);
}
// Fonction pour afficher les données des tables
function displayData($tableName) {
    global $conn;
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr>";
        // Affichage des noms de colonnes
        $row = $result->fetch_assoc();
        foreach ($row as $key => $value) {
            echo "<th>$key</th>";
        }
        echo "</tr>";
        // Affichage des données
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>$value</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
}
