<?php
session_start();

// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";
$conn = mysqli_connect("mysql-didier.alwaysdata.net", "didier", "Didiergbedan@123", "didier_db");

if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation des champs
    if (empty($email) || empty($password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (empty($errors)) {
        $sql = "SELECT password, username FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $hashed_password, $username);
            if (mysqli_stmt_fetch($stmt)) {
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['username'] = $username;
                    header("Location: welcome.php"); // Rediriger vers la page d'accueil
                    exit();
                } else {
                    $errors[] = "Email ou mot de passe incorrect.";
                }
            } else {
                $errors[] = "Email ou mot de passe incorrect.";
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Erreur de préparation de la requête : " . mysqli_error($conn);
        }
    }
}

// Fermer la connexion
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            margin: 0;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding-top: 120px; /* Laisse de l'espace pour le header */
            box-sizing: border-box; /* Inclut le padding et la marge dans la largeur totale */
        }

        .header {
            background-color: #101010;
            color: white;
            padding: 20px;
            position: fixed; /* Fixe le header en haut de la page */
            top: 0;
            width: 100%;
            text-align: center;
            z-index: 1000; /* Assure que le header reste au-dessus des autres éléments */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .logo img {
            height: 80px;
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.1); /* Agrandit légèrement le logo au survol */
        }

        h2 {
            text-align: center;
            color: rgb(34, 88, 166);
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px; /* Limite la largeur du formulaire */
            text-align: center;
            transition: transform 0.3s ease;
        }

        .form-container:hover {
            transform: scale(1.02); /* Effet de zoom léger au survol */
        }

        .error {
            color: red;
            text-align: left;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-top: 10px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input:focus {
            border-color: rgb(34, 88, 166);
            box-shadow: 0 0 5px rgba(34, 88, 166, 0.2);
            outline: none;
        }

        input[type="submit"] {
            background-color: rgb(34, 88, 166);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 10px 20px;
            width: 100%;
            max-width: 200px;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: rgb(24, 68, 146);
            transform: translateY(-2px); /* Effet de soulèvement au survol */
        }

        .register-link,
        .forgot-password {
            margin-top: 15px;
            text-align: center;
        }

        @media (max-width: 600px) {
            .form-container {
                padding: 10px;
                max-width: 90%; /* Prend presque toute la largeur de l'écran */
            }

            .header {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
<header class="header">
    <div class="logo">
        <img src="MESRS 2.png" alt="Logo">
    </div>
</header>
<div class="form-container">
    <h2>Connectez-vous</h2>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Se connecter">
        <p class="register-link">Vous n'avez pas de compte? <a href="register.php">Inscrivez-vous</a></p>
        <p class="forgot-password">Mot de passe oublié? <a href="forgot_password.php">Réinitialisez-le</a></p>
    </form>
</div>
</body>
</html>
