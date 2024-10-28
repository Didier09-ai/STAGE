<?php
session_start();
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";
$conn = mysqli_connect("mysql-didier.alwaysdata.net", "didier", "Didiergbedan@123", "didier_db");

if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_password) || empty($confirm_password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier le token
        $sql = "SELECT * FROM password_resets WHERE token = ? AND expiry > NOW()";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($reset = mysqli_fetch_assoc($result)) {
                // Mettre à jour le mot de passe
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $email = $reset['email'];

                $sql = "UPDATE users SET password = ? WHERE email = ?";
                if ($stmt = mysqli_prepare($conn, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $email);
                    mysqli_stmt_execute($stmt);
                    $success = "Votre mot de passe a été réinitialisé avec succès.";
                } else {
                    $errors[] = "Erreur lors de la mise à jour du mot de passe : " . mysqli_error($conn);
                }
            } else {
                $errors[] = "Token invalide ou expiré.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Erreur de préparation de la requête : " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
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
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
<h2>Réinitialiser le mot de passe</h2>
<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div class="success">
        <p><?php echo htmlspecialchars($success); ?></p>
    </div>
<?php endif; ?>
<form action="reset_password.php" method="post">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
    <label for="new_password">Nouveau mot de passe:</label>
    <input type="password" id="new_password" name="new_password" required>
    <label for="confirm_password">Confirmer le mot de passe:</label>
    <input type="password" id="confirm_password" name="confirm_password" required>
    <input type="submit" value="Réinitialiser le mot de passe">
</form>
</body>
</html>
