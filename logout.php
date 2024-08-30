<?php
session_start();

// Vérifier si l'utilisateur a confirmé la déconnexion
if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
    session_destroy();
    header("Location: login.php");
    exit();
} elseif (isset($_POST['confirm']) && $_POST['confirm'] == 'no') {
    header("Location: tableau_de_bord.php"); // Redirige vers le tableau de bord si l'utilisateur annule la déconnexion
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnexion</title>
</head>
<body>
    <h2>Voulez-vous vraiment vous déconnecter ?</h2>
    <form method="post">
        <button type="submit" name="confirm" value="yes">Oui</button>
        <button type="submit" name="confirm" value="no">Non</button>
    </form>
</body>
</html>
