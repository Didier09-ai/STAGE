<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Système de Gestion des Instructions</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: rgb(49, 63, 65);
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: fixed;
            height: 100%;
            transition: all 0.3s ease;
        }

        .sidebar .logo {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .sidebar .icon-home {
            font-size: 24px;
            margin-right: 10px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        header {
            background-color: rgb(49, 63, 65);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: white;
            text-align: center;
        }

        section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: rgb(206, 213, 212);
            text-align: center;
            padding: 12px;
        }

        table {
            width: 100%;
            background-color: rgb(206, 213, 212);
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: rgb(107, 143, 146);
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .welcome-section {
            background-color: rgb(49, 63, 65);
            color: rgb(68, 173, 70);
            padding: 20px;
            border-radius: 8px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions a {
            text-decoration: none;
        }

        .add-button {
            margin-bottom: 20px;
        }

        .add-button button {
            background-color: rgb(0, 133, 89);
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }

        .actions .view-button {
            background-color: white;
            color: black;
            border: 1px solid black;
            padding: 5px 10px;
        }

        .actions .edit-button {
            background-color: blue;
            color: white;
            border: none;
            padding: 5px 10px;
        }

        .actions .delete-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
        }

        footer {
            background-color: #101010;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            position: relative;
            width: 100%;
            box-sizing: border-box;
        }
         /* Nouveau style pour changer la couleur de fond de "Accueil" */
    .sidebar ul li a[href="tableau_de_bord.php"] {
        background-color: #3498db; /* Choisissez la couleur de fond souhaitée */
        color: #ffffff; /* Couleur du texte */
        padding: 10px; /* Pour ajouter un peu de padding autour du texte */
        border-radius: 4px; /* Pour arrondir légèrement les coins */
    }

    .sidebar ul li a[href="tableau_de_bord.php"]:hover {
        background-color: #2980b9; /* Couleur de fond au survol */
        color: #ffffff; /* Couleur du texte au survol */
    }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            header {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .sidebar ul li a {
                font-size: 16px;
            }

            header {
                font-size: 16px;
            }

            .sidebar .logo {
                font-size: 20px;
            }
        }
        /* Nouveau style pour la section de présentation du tableau de bord */
.dashboard-overview {
    background-color: #f5f5f5;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    text-align: center;
}

.overview-content {
    animation: fadeIn 1.5s ease-in-out;
}

/* Effets d'apparition pour le texte */
.fade-in {
    opacity: 0;
    animation: fadeIn 1s forwards;
    animation-delay: 0.3s;
}

/* Effets d'apparition pour le bouton */
.slide-in {
    opacity: 0;
    display: inline-block;
    animation: slideIn 1s forwards;
    animation-delay: 0.6s;
}

/* Animation pour le texte */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Animation pour le bouton */
@keyframes slideIn {
    0% {
        opacity: 0;
        transform: translateX(-50px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Style du bouton */
.stats-button button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.stats-button button:hover {
    background-color: #0056b3;
}

    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <i class="icon-home"></i> Tableau de Bord
        </div>
        <ul>
        <li><a href="tableau_de_bord.php">Accueil</a></li>
            <li><a href="affichage_courrier.php">Instructions</a></li>
            <li><a href="statistiques.php">Statistiques</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h2>Système de Gestion des Instructions du Ministère</h2>
        </header>
        <section class="dashboard-overview">
    <div class="overview-content">
        <p class="fade-in">
            <b>Bienvenue sur le tableau de bord du système de gestion des instructions. Utilisez l'option "Instructions" pour gérer vos instructions, ou explorez les statistiques de traitement de vos instructions en cliquant sur le bouton ci-dessous.</b>
        </p>
        <a href="statistiques.php" class="stats-button slide-in">
            <button>
                Voir les statistiques
            </button>
        </a>
    </div>
</section>

    </div>
    <footer>
        <p>&copy; Ministère de l'Enseignement Supérieur et de la Recherche Scientifique | Tous droits réservés | 2024</p>
    </footer>
</body>

</html>
