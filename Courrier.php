<?php
// Connexion à la base de données
$serveur = "localhost";
$utilisateur = "root";
$mot_de_passe = "";
$db = "formulaire";
$conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $db);

if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}

// Récupérer les types de courrier
$sql_courrier = "SELECT type_courrier FROM courrier_types";
$result_courrier = mysqli_query($conn, $sql_courrier);
$courrierTypes = [];
if (mysqli_num_rows($result_courrier) > 0) {
    while ($row = mysqli_fetch_assoc($result_courrier)) {
        $courrierTypes[] = $row['type_courrier'];
    }
}

// Récupérer les types de provenance
$sql_provenance = "SELECT type_provenance FROM provenance_types";
$result_provenance = mysqli_query($conn, $sql_provenance);
$provenanceTypes = [];
if (mysqli_num_rows($result_provenance) > 0) {
    while ($row = mysqli_fetch_assoc($result_provenance)) {
        $provenanceTypes[] = $row['type_provenance'];
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
<style>
    body {
    margin: 0;
    font-family: Arial, sans-serif;
    display: flex;
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
.sidebar ul li a[href="affichage_courrier.php"] {
        background-color: #3498db; /* Choisissez la couleur de fond souhaitée */
        color: #ffffff; /* Couleur du texte */
        padding: 10px; /* Pour ajouter un peu de padding autour du texte */
        border-radius: 10px; /* Pour arrondir légèrement les coins */
    }

    .sidebar ul li a[href="affichage_courrier.php"]:hover {
        background-color: #2980b9; /* Couleur de fond au survol */
        color: #ffffff; /* Couleur du texte au survol */
    }

/* Main Content Styles */
.main-content {
    flex: 1;
    padding: 20px;
}

header {
    background-color: rgb(49, 63, 65);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

section {
    background-color: rgb(206, 213, 212);
        padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

h2{
    color: rgb(206, 213, 212);
        }
        label {
            display: block;
            margin-top: 1px;
        }
        input, select, textarea {
            width: 50%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
         input[type="button"] {
            background-color: rgb(34, 88, 166);
            color: white;
            border: none;
            box-sizing: border-box;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        input[type="submit"] {
            background-color: rgb(0, 133, 89);
            color: white;
            border: none;
            box-sizing: border-box;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        
        /* input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #45a049;
        } */
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
        <h2>Ajouter/Enregistrer une instruction</h2>
        </header>
        <section>
        <form action="traitemen.php" method="post">
        <label for="intitule"><b>Type d'instruction:</b></label>
        <select id="intitule" name="intitule" required>
            <option value="" disabled selected>Choisir un type de courrier</option>
            <?php foreach ($courrierTypes as $type): ?>
                <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
            <?php endforeach; ?>
            <option value="Demande d'avis technique et autres appuis sollicités">Demande d'avis technique et autres appuis sollicités</option>
    <option value="Lettres d'informations (Notes de service, Nominations, comptes rendus de réunions, arrêtés, etc.…)">Lettres d'informations (Notes de service, Nominations, comptes rendus de réunions, arrêtés, etc.…)</option>
    <option value="Communiqués de bourses pour les étudiants à publier sur le site Web du MESRS">Communiqués de bourses pour les étudiants à publier sur le site Web du MESRS</option>
    <option value="Demande de Stage">Demande de Stage</option>
    <option value="Demande d'appel à candidature au poste">Demande d'appel à candidature au poste</option>
    <option value="Demande d'audience">Demande d'audience</option>
    <option value="Gestion des carrières du personnel de la DSI">Gestion des carrières du personnel de la DSI</option>
    <option value="Classement et sélection des bacheliers de 2023 (Demandes de réclamation)">Classement et sélection des bacheliers de 2023 (Demandes de réclamation)</option>
    <option value="Validation et réception de rapports d’études (Enquête de satisfaction des usagers, ProDES, P2D, Conception d’un guide de budgétisation équitable et sensible au genre au MESRS)">Validation et réception de rapports d’études (Enquête de satisfaction des usagers, ProDES, P2D, Conception d’un guide de budgétisation équitable et sensible au genre au MESRS)</option>
    <option value="Ouverture et analyse des offres relatives aux marchés publics">Ouverture et analyse des offres relatives aux marchés publics</option>
    <option value="Rapport de performance + Revue annuelle PTA">Rapport de performance + Revue annuelle PTA</option>
    <option value="Réception des matériels informatiques et consommables">Réception des matériels informatiques et consommables</option>
            <option value="Autre">Autre (Veuillez spécifier ci-dessous)</option>
        </select>
        <input type="text" id="autre_intitule" name="autre_intitule" placeholder="Autre type de courrier" style="display:none;">
        <input type="button" id="ajouter_type_courrier" value="Ajouter un nouveau type de courrier" style="display:none;">

        <label for="provenance"><b>Provenance:</b></label>
        <select id="provenance" name="provenance" required>
            <option value="" disabled selected>Choisir une provenance</option>
            <?php foreach ($provenanceTypes as $type): ?>
                <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
            <?php endforeach; ?>
            <option value="UAC">UAC</option>
            <option value="PRMP">PRMP</option>
            <option value="DGBO">DGBO</option>
    <option value="CUEP">CUEP</option>
    <option value="DBAU">DBAU</option>
    <option value="SGM">SGM</option>
    <option value="COUS-AS">COUS-AS</option>
    <option value="COUS-P">COUS-P</option>
    <option value="DPAF">DPAF</option>
    <option value="UNA">UNA</option>
    <option value="UNSTIM">UNSTIM</option>
    <option value="DGES">DGES</option>
    <option value="DEC">DEC</option>
    <option value="DC">DC</option>
    <option value="INFOSEC">INFOSEC</option>
    <option value="ENSBAA">ENSBAA</option>
    <option value="CEFORP">CEFORP</option>
    <option value="INSTI">INSTI</option>
    <option value="IRSP">IRSP</option>
    <option value="DGRSI">DGRSI</option>
    <option value="CUEP">CUEP</option>
    <option value="DOB">DOB</option>
    <option value="ABeVRIT">ABeVRIT</option>
    <option value="INE">INE</option>
    <option value="INMAAC">INMAAC</option>
    <option value="FNRSI">FNRSIT</option>
    <option value="ENSGMM">ENSGMM</option>
    <option value="IDRC-CRDI">IDRC-CRDI</option>
    <option value="FORAM Initiatives">FORAM Initiatives</option>
    <option value="GNV SYSTEM">GNV SYSTEM</option>
    <option value="Cercle des docteurs">Cercle des docteurs</option>
    <option value="LE REVELATEUR">LE REVELATEUR</option>
    <option value="SP/MESRS">SP/MESRS</option>
    <option value="Intéressés">Intéressés</option>
    <option value="IGM">IGM</option>
    <option value="DG/CBRSI">DG/CBRSI</option>
    <option value="CoE-EIE">CoE-EIE</option>
          
            <option value="Autre">Autre (Veuillez spécifier ci-dessous)</option>
        </select>
        <input type="text" id="autre_provenance" name="autre_provenance" placeholder="Autre provenance" style="display:none;">
        <input type="button" id="ajouter_provenance" value="Ajouter une nouvelle provenance" style="display:none;">

        <label for="date_reception"><b>Date:</b></label>
        <input type="date" id="date_reception" name="date_reception" required>

        <label for="etat"><b>État:</b></label>
        <select id="etat" name="etat" required>
            <option value="Non traité">Non traitée</option>
            <option value="Traité">Traitée</option>
        </select>

        <label for="raison_non_traitement" id="raison_label" style="display:none;"><b>Raison du non traitement:</b></label>
        <textarea id="raison_non_traitement" name="raison_non_traitement" placeholder="Veuillez renseigner la raison du non traitement" rows="4" style="display:none;"></textarea>

        <label for="commentaires"><b>Commentaires:</b></label>
        <textarea id="commentaires" name="commentaires" placeholder="Veuillez renseigner plus d'informations sur votre courrier" rows="4" required></textarea>
        
        <input type="submit" value="Enregistrer">
    </form>

    <script>
        // Afficher le champ pour le type de courrier personnalisé
        document.getElementById('intitule').addEventListener('change', function() {
            var autreIntitule = document.getElementById('autre_intitule');
            var ajouterTypeCourrier = document.getElementById('ajouter_type_courrier');
            if (this.value === 'Autre') {
                autreIntitule.style.display = 'block';
                ajouterTypeCourrier.style.display = 'block';
            } else {
                autreIntitule.style.display = 'none';
                ajouterTypeCourrier.style.display = 'none';
            }
        });

        // Afficher le champ pour la provenance personnalisée
        document.getElementById('provenance').addEventListener('change', function() {
            var autreProvenance = document.getElementById('autre_provenance');
            var ajouterProvenance = document.getElementById('ajouter_provenance');
            if (this.value === 'Autre') {
                autreProvenance.style.display = 'block';
                ajouterProvenance.style.display = 'block';
            } else {
                autreProvenance.style.display = 'none';
                ajouterProvenance.style.display = 'none';
            }
        });

        // Afficher le champ de raison non-traitement par défaut
var raisonNonTraitement = document.getElementById('raison_non_traitement');
var raisonLabel = document.getElementById('raison_label');

// Afficher ou masquer le champ en fonction de l'état sélectionné
document.getElementById('etat').addEventListener('change', function() {
    if (this.value === 'Traité') {
        raisonNonTraitement.style.display = 'none';
        raisonLabel.style.display = 'none';
    } else {
        raisonNonTraitement.style.display = 'block';
        raisonLabel.style.display = 'block';
    }
});

// Afficher le champ par défaut au chargement de la page si l'état n'est pas "Traité"
if (document.getElementById('etat').value !== 'Traité') {
    raisonNonTraitement.style.display = 'block';
    raisonLabel.style.display = 'block';
};
        // Ajouter les nouveaux types de courriers
        document.getElementById('ajouter_type_courrier').addEventListener('click', function() {
            var nouveauType = document.getElementById('autre_intitule').value;
            if (nouveauType) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "ajouter_option.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Nouveau type de courrier ajouté !");
                        location.reload(); // Recharger la page pour mettre à jour les options
                    }
                };
                xhr.send("type=courrier&valeur=" + encodeURIComponent(nouveauType));
            }
        });

        // Ajouter les nouvelles provenances
        document.getElementById('ajouter_provenance').addEventListener('click', function() {
            var nouvelleProvenance = document.getElementById('autre_provenance').value;
            if (nouvelleProvenance) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "ajouter_option.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert("Nouvelle provenance ajoutée !");
                        location.reload(); // Recharger la page pour mettre à jour les options
                    }
                };
                xhr.send("type=provenance&valeur=" + encodeURIComponent(nouvelleProvenance));
            }
        });
    </script>
        </section>
    </div>
</body>
</html>


