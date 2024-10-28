<style>
    /* Le style de la page reste inchangé */
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        display: flex;
        min-height: 100vh;
    }

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

    .main-content {
        flex: 1;
        padding: 20px;
        background-color: #ecf0f1;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }

    header {
        background-color: rgb(49, 63, 65);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        color: #fff;
        text-align: center;
    }

    .search-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-input, .date-input {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin: 5px 0;
        flex: 1 1 200px;
    }

    table {
        width: 100%;
        background-color: rgb(206, 213, 212);
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: rgb(107, 143, 146);
        color: white;
    }

    .sidebar ul li a[href="statistiques.php"] {
        background-color: #3498db; /* Choisissez la couleur de fond souhaitée */
        color: #ffffff; /* Couleur du texte */
        padding: 10px; /* Pour ajouter un peu de padding autour du texte */
        border-radius: 10px; /* Pour arrondir légèrement les coins */
    }

    .sidebar ul li a[href="statistiques.php"]:hover {
        background-color: #2980b9; /* Couleur de fond au survol */
        color: #ffffff; /* Couleur du texte au survol */
    }

    .download-button {
        display: flex;
        justify-content: center;
    }

    .download-button button {
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .download-button button:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        body {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            padding: 10px;
        }

        .main-content {
            padding: 10px;
        }

        .search-container {
            flex-direction: column;
        }

        .search-input, .date-input {
            width: 50%;
        }
    }
</style>

<table id="statsTable">
    <thead>
        <tr>
            <th>Type d'instruction</th>
            <th>Date de réception</th>
            <th>Nombre total</th>
            <th>Provenances</th>
            <th>Nombre traité</th>
            <th>Nombre non traité</th>
            <th>Raisons de non-traitement</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($instructions as $intitule => $dates) : ?>
            <?php foreach ($dates['dates'] as $date_reception => $details) : ?>
                <tr>
                    <td><?= htmlspecialchars($intitule) ?></td>
                    <td><?= htmlspecialchars($date_reception) ?></td>
                    <td><?= $details['nombre_total'] ?></td>
                    <td>
                        <?php foreach ($details['provenances'] as $provenance => $nombre) : ?>
                            <?= htmlspecialchars($provenance) ?>: <?= $nombre ?><br>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?= $details['nombre_traite'] ?>
                        <span class="percentage">(<?= round(($details['nombre_traite'] / $details['nombre_total']) * 100, 2) ?>%)</span>
                    </td>
                    <td>
                        <?= $details['nombre_non_traite'] ?>
                        <span class="percentage">(<?= round(($details['nombre_non_traite'] / $details['nombre_total']) * 100, 2) ?>%)</span>
                    </td>
                    <td>
                        <?php foreach ($details['raisons_non_traitement'] as $raison => $provenances) : ?>
                            <?= htmlspecialchars($raison) ?>: <?= implode(', ', $provenances) ?><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>
