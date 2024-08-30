<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Gestion d'Instruction et de Demande</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }
        strong{
            color: rgb(7, 165, 146);
        }

        .header {
            background-color: #101010;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            flex-wrap: wrap; /* Permet aux éléments de se replier sur plusieurs lignes si nécessaire */
        }

        .logo img {
            height: 60px; /* Réduit la hauteur du logo sur les petits écrans */
        }

        .nav-links {
            display: flex;
            gap: 10px; /* Espace entre les liens */
        }

        .nav-links a {
            background-color: rgb(222, 146, 0);
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px; /* Réduit la taille du texte sur les petits écrans */
        }

        .container {
            text-align: center;
            padding: 20px;
        }

        .container b {
            width: 100%; /* Utilise toute la largeur disponible */
            margin-bottom: 20px;
            background-color: rgb(255, 255, 255);
            color: rgb(0, 133, 89);
            display: inline-block;
            padding: 10px;
            box-sizing: border-box;
        }

        .container h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px; /* Réduit la taille du texte sur les petits écrans */
        }

        .container p {
            color: #666;
            margin-bottom: 20px;
            font-size: 16px; /* Réduit la taille du texte sur les petits écrans */
        }

        .btn-green {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px; /* Réduit la taille du texte sur les petits écrans */
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn-green:hover {
            background-color: #218838;
        }

        footer {
            background-color: #101010;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-sizing: border-box;
        }

        /* Styles pour les petits écrans */
        @media (max-width: 600px) {
            .header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .nav-links {
                margin-top: 10px;
                flex-direction: column;
            }

            .nav-links a {
                margin: 5px 0; /* Espace entre les liens pour les petits écrans */
                padding: 10px;
                font-size: 14px;
            }

            .container b {
                font-size: 14px; /* Réduit la taille du texte */
            }

            .container h2 {
                font-size: 18px; /* Réduit la taille du texte */
            }

            .container p {
                font-size: 14px; /* Réduit la taille du texte */
            }

            .btn-green {
                padding: 10px 20px;
                font-size: 14px; /* Réduit la taille du texte */
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="MESRS 2.png" alt="Logo">
        </div>
        <nav class="nav-links">
            <a href="register.php">Créer un compte</a>
            <a href="login.php">Se connecter</a>
        </nav>
    </header>
    <div class="container">
        <b><marquee behavior="" direction="left">Plateforme d'enregistrement et de suivi des instructions reçues au niveau du Ministère</marquee></b>
        <h2>Bienvenue sur le système de gestion d'instruction ou de courrier. Veuillez vous inscrire si vous n'avez pas de compte ou cliquez sur connexion pour vous connecter.</h2>
        <p>Notre plateforme vous permet de d'enregistrer, suivre les instructions et demandes adressées au ministère. Nous sommes là pour vous aider à chaque étape.</p>
        <p>Enregistrer et suivez plus facilement la réception et la gestion de vos instructions.</p>
        
        <a href="login.php" class="btn-green">Commencer Maintenant !!!</a>
    </div>
    <footer>
        <p>&copy; Ministère de l'Enseignement Supérieur et de la Recherche Scientifique | Tous droits réservés | 2024</p>
    </footer>
</body>
</html>

