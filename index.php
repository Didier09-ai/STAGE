<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion de Courrier - Ministère</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .header {
    background-color:black;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px 20px;
}

.logo img {
    height: 60px;}
.nav-links a {
    color: #fff;
    text-decoration: none;
    margin-left: 20px;
    font-size: 16px;
}

        .container {
            background-color: #ffffff;
            padding: 1px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333333;
            margin-bottom: 2px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 5px;
            text-decoration: none;
            color: #ffffff;
            font-weight: bold;
            background-color: #007BFF;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .button-secondary {
            background-color: #28a745;
        }

        .button-secondary:hover {
            background-color: #218838;
        }
        footer {
            background-color: #003366;
            color: #ffffff;
            text-align: center;
            padding: 5px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
<header class="header">
        <div class="logo">
            <img src="MESRS 2.png" alt="Logo">
        </div>
        <nav class="nav-links">
            <a href="orientation.html">Orientation</a>
            <a href="index.html">Inscription</a>
            <a href="connexion.html">Connexion</a>
        </nav>
    </header>
    <div class="container">
        <h1>Bienvenue au Système de Gestion de Courrier</h1>
        <p>Ministère</p>
        <a href="se-connecter.html" class="button">Se connecter</a>
        <a href="s-inscrire.html" class="button button-secondary">S'inscrire</a>
    </div>
    <footer>
        <p>&copy; 2024Ministère de l'Enseignement Superieure et de la Recherche Scientifique. Tous droits réservés.</p>
        <p>Adresse | Téléphone | Email</p>
    </footer>
</body>
</html>
