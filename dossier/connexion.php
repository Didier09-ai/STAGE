<?php 
// Paramètres de connexion
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "courrier";

// Connexion à MySQL
  $conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $db);
// $conn = mysqli_connect('localhost', 'root', '', 'courrier', 3306);

// Vérification de la connexion
if (!$conn) {
 die("La connexion a échoué : " . mysqli_connect_error());
}
echo "Connexion réussie";

?>