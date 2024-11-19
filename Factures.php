<?php
session_start();
// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "facturations");

if ($mysqli->connect_error) {
    die("Connexion échouée : " . $mysqli->connect_error);
}

// Récupérer la liste des clients
$sqlClients = "SELECT id, nom FROM clients";
$resultClients = $mysqli->query($sqlClients);
$clients = [];
if ($resultClients->num_rows > 0) {
    while ($row = $resultClients->fetch_assoc()) {
        $clients[] = $row;
    }
}

// Requête SQL pour récupérer les factures et leurs clients
$sql = "SELECT f.id, f.type_facture, f.description, f.numero_facture, f.date_facturation, f.date_echeance, f.total_ht, f.tva, f.total_ttc, f.notes, f.mode_paiement, f.statut_facture, c.nom AS client_nom
        FROM factures f
        LEFT JOIN clients c ON f.client_id = c.id";




$result = $mysqli->query($sql);



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Factures</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="./asset/devis.css">
    <link rel="stylesheet" href="./asset/boutonFact.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <style>
/* Style des boutons d'action */
.action-button {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem; /* Réduit le padding pour rendre les boutons plus petits */
    font-size: 12px;  /* Réduit la taille de la police */
    border-radius: 0.375rem;
    color: white;
    text-decoration: none;
    margin-right: 0.5rem;
    transition: background-color 0.3s ease;
    cursor: pointer;
}

.btn-edit {
    background-color: #3B82F6;
}

.btn-edit:hover {
    background-color: #2563EB;
}

.btn-delete {
    background-color: #EF4444;
}

.btn-delete:hover {
    background-color: #DC2626;
}

.btn-view {
    background-color: #10B981;
}

.btn-view:hover {
    background-color: #059669;
}

/* Réduire la taille des boutons sur les petits écrans */
@media screen and (max-width: 768px) {
    .action-button {
        padding: 0.25rem 0.5rem;  /* Moins de padding pour les petits écrans */
        font-size: 20px;  /* Réduit encore plus la taille de la police */
    }
}


/* Footer */
footer {
    padding: 20px;
    margin-top: 20px;
    background-color: #3498db;
    color: white;
    text-align: center;
}

/* Make sure all the table headers have consistent styles */
#FacturesTable th {
    font-weight: bold;
    background-color: #3498db;
    color: white;
}

/* Adding responsiveness */
@media screen and (max-width: 768px) {
    #FacturesTable {
        font-size: 12px;
    }
    .action-button {
        padding: 0.25rem 0.5rem;
        font-size: 12px;
    }
}
        #searchBar {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .action-button {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            color: white;
            text-decoration: none;
            margin-right: 0.5rem;
            transition: background-color 0.3s ease;
        }
        .btn-edit { background-color: #3B82F6; }
        .btn-edit:hover { background-color: #2563EB; }
        .btn-delete { background-color: #EF4444; }
        .btn-delete:hover { background-color: #DC2626; }
        .btn-view { background-color: #10B981; }
        .btn-view:hover { background-color: #059669; }
        footer {
            padding: 20px;
            margin-top: 20px;
            color: #ffffff;
            background-color: #3498db;
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <h1><i class="fas fa-file-invoice"></i> Gestion des Factures</h1>
    </header>
    <?php include 'composant/nav.php'; ?>

    <div class="container mx-auto mt-4">
        <input type="text" id="searchBar" placeholder="Rechercher une facture...">
        <table id="FacturesTable" class="min-w-full bg-white">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">N° Facture</th>
                    <th class="border p-2">Description</th>
                    <th class="border p-2">Client</th>
                    <th class="border p-2">Type Facture</th>
                    <th class="border p-2">Date de facturation</th>
                    <th class="border p-2">Date Échéance</th>
                    <th class="border p-2">Montant HT</th>
                    <th class="border p-2">TVA</th>
                    <th class="border p-2">Montant TTC</th>
                    <th class="border p-2">Mode de Paiement</th>
                    <th class="border p-2">Statut</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border p-2"><?= htmlspecialchars($row['numero_facture'] ?? '') ?></td>
                            <td class="border p-2"><?= htmlspecialchars($row['description'] ?? '') ?></td>
                            <td class="border p-2"><?= htmlspecialchars($row['client_nom'] ?? '') ?></td>
                            <td class="border p-2"><?= htmlspecialchars($row['type_facture'] ?? '') ?></td>
                            <td class="border p-2"><?= htmlspecialchars($row['date_facturation'] ?? '') ?></td>
                            <td class="border p-2"><?= htmlspecialchars($row['date_echeance'] ?? 'Date non définie') ?></td>
                            <td class="border p-2"><?= htmlspecialchars($row['total_ht'] ?? '0') ?> XAF</td>
                            <td class="border p-2"><?= htmlspecialchars($row['tva'] ?? '0') ?> %</td>
                            <td class="border p-2"><?= htmlspecialchars($row['total_ttc'] ?? '0') ?> XAF</td>
                            <td class="border p-2"><?= htmlspecialchars($row['mode_paiement'] ?? '') ?></td>
                            <td class="border p-2"><?= htmlspecialchars($row['statut_facture'] ?? '') ?></td>
                            <td class="border p-2">
                                <a href='edit_facture.php?id=<?= $row['id'] ?>' class='action-button btn-edit'>
                                    <i class='fas fa-edit'></i> Edit
                                </a>
                                <a href='delete_facture.php?id=<?= $row['id'] ?>' class='action-button btn-delete'>
                                    <i class='fas fa-trash-alt'></i> Delete
                                </a>
                                <a href='views_facture.php?id=<?= $row['id'] ?>' class='action-button btn-view'>
                                    <i class='fas fa-eye'></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" class="border p-2 text-center">Aucune facture trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>Système de Gestion Commerciale et Facturation, Copyright Ludo_Consulting &copy; 2024</p>
    </footer>
</body>

<script>
    // Fonction de recherche
    document.getElementById("searchBar").addEventListener("input", function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll("#FacturesTable tbody tr");
        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const match = Array.from(cells).some(cell => cell.textContent.toLowerCase().includes(searchTerm));
            row.style.display = match ? "" : "none";
        });
    });
</script>

</html>

<?php
$mysqli->close();
?>
