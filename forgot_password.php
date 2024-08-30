<?php
session_start();

// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";
$conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $db);

if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Inclure le fichier PHPMailer
require 'vendor/autoload.php';

// Définir la fonction send_reset_email
function send_reset_email($email, $reset_link) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Paramètres du serveur
        $mail->isSMTP();
        $mail->Host = 'localhost';  // Serveur SMTP de hMailServer
        $mail->SMTPAuth = false;    // Pas d'authentification SMTP nécessaire pour hMailServer local
        $mail->Port = 25;           // Port SMTP par défaut pour hMailServer

        // Destinataires
        $mail->setFrom('didiergbedan12@gmail.com', 'didier'); // Remplacez par votre adresse e-mail et nom
        $mail->addAddress($email); // Adresse e-mail du destinataire

        // Contenu de l'e-mail
        $mail->isHTML(true);                                  // Définir le format de l'e-mail en HTML
        $mail->Subject = 'Réinitialisation de votre mot de passe'; // Sujet de l'e-mail
        $mail->Body    = "Cliquez sur ce lien pour réinitialiser votre mot de passe : $reset_link"; // Corps de l'e-mail en HTML
        $mail->AltBody = "Cliquez sur ce lien pour réinitialiser votre mot de passe : $reset_link"; // Corps de l'e-mail en texte brut

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Traitement du formulaire de réinitialisation de mot de passe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Vérifier si l'e-mail existe dans la base de données
    $sql = "SELECT * FROM users WHERE email = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            // Générer un token de réinitialisation et une date d'expiration
            $token = bin2hex(random_bytes(50));
            $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Insérer le token dans la base de données
            $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "sss", $email, $token, $expires_at);
                mysqli_stmt_execute($stmt);

                // Créer le lien de réinitialisation
                $reset_link = "http://localhost/stage/reset_password.php?token=$token";

                // Envoyer l'e-mail de réinitialisation
                if (send_reset_email($email, $reset_link)) {
                    echo "Un e-mail de réinitialisation a été envoyé à votre adresse e-mail.";
                } else {
                    echo "Erreur lors de l'envoi de l'e-mail.";
                }
            }
        } else {
            echo "Aucun utilisateur trouvé avec cette adresse e-mail.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Fermer la connexion
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
    <style>
        body {
            background-color: #becbd3;
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
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<h2>Réinitialisation du mot de passe</h2>
<form action="forgot_password.php" method="post">
    <label for="email">Adresse e-mail :</label>
    <input type="email" id="email" name="email" required>

    <input type="submit" value="Envoyer l'e-mail de réinitialisation">
</form>
</body>
</html>
