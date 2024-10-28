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
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation des champs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    // Vérification des exigences du mot de passe
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un caractère spécial.";
    }

    // Vérification de la correspondance des mots de passe
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['username'] = $username;
                header("Location: welcome.php"); // Rediriger vers la page d'accueil
                exit();
            } else {
                $errors[] = "Erreur lors de l'inscription.";
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
    <title>Inscription</title>
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

        .password-requirements {
            background-color: rgb(7, 165, 146);
            padding: 10px;
            border-radius: 5px;
            color: white;
            text-align: left;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            margin-top: 10px;
            text-align: left;
        }

        .password-container {
            position: relative;
            width: 100%;
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

        .password-container input {
            padding-right: 40px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: rgb(34, 88, 166);
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

        .error {
            color: red;
            text-align: left;
            margin-bottom: 10px;
        }

        .register-link {
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

            .password-requirements {
                padding: 8px;
            }
        }
    </style>

    <script>
        function validatePassword() {
            const password = document.getElementById("password").value;
            const confirm_password = document.getElementById("confirm_password").value;
            const error_message = document.getElementById("error-message");
            const pattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (!pattern.test(password)) {
                error_message.textContent = "Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, un chiffre et un caractère spécial.";
                return false;
            }

            if (password !== confirm_password) {
                error_message.textContent = "Les mots de passe ne correspondent pas.";
                return false;
            }

            return true;
        }

        function togglePasswordVisibility(inputId, toggleIconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleIconId);
            const type = passwordInput.type === "password" ? "text" : "password";
            passwordInput.type = type;
            toggleIcon.textContent = type === "password" ? "👁" : "👁️‍🗨️"; // Change icon
        }
    </script>
</head>
<body>
<header class="header">
    <div class="logo">
        <img src="MESRS 2.png" alt="Logo">
    </div>
</header>

<div class="form-container">
    <h2>Inscrivez-vous ici!</h2>
    <div class="password-requirements">
        <b>Le mot de passe doit :</b>
        <ul>
            <li>Contenir au moins 8 caractères.</li>
            <li>Inclure au moins une majuscule.</li>
            <li>Inclure au moins un chiffre.</li>
            <li>Contenir au moins un caractère spécial.</li>
        </ul>
    </div>
    <?php if (!empty($errors)): ?>
        <div class="error" id="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php else: ?>
        <div class="error" id="error-message"></div>
    <?php endif; ?>

    <form action="register.php" method="post" onsubmit="return validatePassword()">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe:</label>
        <div class="password-container">
            <input type="password" id="password" name="password" required>
            <span class="toggle-password" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')">👁</span>
        </div>

        <label for="confirm_password">Confirmez le mot de passe:</label>
        <div class="password-container">
            <input type="password" id="confirm_password" name="confirm_password" required>
            <span class="toggle-password" id="toggleConfirmPassword" onclick="togglePasswordVisibility('confirm_password', 'toggleConfirmPassword')">👁</span>
        </div>

        <input type="submit" value="S'inscrire">
        <p class="register-link">Vous avez déjà un compte? <a href="login.php">Connectez-vous</a></p>
    </form>
</div>
</body>
</html>
