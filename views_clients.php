<?php
include './config/db.php'; // Inclure le fichier de connexion

// Récupérer l'ID du client depuis l'URL
$clientId = $_GET['id'] ?? null;

if (!$clientId) {
    die("Client non spécifié.");
}

// Récupérer les informations du client
$sqlClient = "SELECT * FROM clients WHERE id = ?";
$stmtClient = $pdo->prepare($sqlClient);
$stmtClient->execute([$clientId]);
$client = $stmtClient->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    die("Client non trouvé.");
}

// Récupérer les factures du client
$sqlFactures = "SELECT id, numero_facture, total_ht, total_ttc, date_facturation, mode_paiement, statut_facture FROM factures WHERE client_id = ?";
$stmtFactures = $pdo->prepare($sqlFactures);
$stmtFactures->execute([$clientId]);
$factures = $stmtFactures->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les devis du client
$sqlDevis = "SELECT id, numero_devis, total_ht, total_ttc, date_creation, articles FROM devis WHERE client_id = ?";
$stmtDevis = $pdo->prepare($sqlDevis);
$stmtDevis->execute([$clientId]);
$devis = $stmtDevis->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Client</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./asset/Client.css">
    <link rel="shortcut icon" href="/img/Image1.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            color: #333;
        }
        h2 {
            margin-top: 30px;
            color: #4CAF50;
        }
        .section-title {
            color: #333;
            font-size: 1.5em;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 8px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .icon {
            margin-right: 8px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #1e88e5;
        }
    </style>
</head>
<body>
    <h1>Résumé des activités du client : <?= htmlspecialchars($client['nom']) ?></h1>

    <!-- Informations générales -->
    <div class="section">
        <h2 class="section-title"><i class="fas fa-info-circle icon"></i> Informations générales</h2>
        <table>
            <tr><th>Email</th><td><?= htmlspecialchars($client['email']) ?></td></tr>
            <tr><th>Téléphone</th><td><?= htmlspecialchars($client['telephone']) ?></td></tr>
            <tr><th>Entreprise</th><td><?= htmlspecialchars($client['entreprise']) ?></td></tr>
            <tr><th>Adresse</th><td><?= htmlspecialchars($client['adresse']) ?></td></tr>
        </table>
    </div>

    <!-- Section Factures -->
    <div class="section">
        <h2 class="section-title"><i class="fas fa-file-invoice-dollar icon"></i> Factures</h2>
        <?php if ($factures): ?>
            <table>
                <thead>
                    <tr>
                        <th>Numéro Facture</th>
                        <th>Total HT</th>
                        <th>Total TTC</th>
                        <th>Date de Facturation</th>
                        <th>Mode de Paiement</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($factures as $facture): ?>
                        <tr>
                            <td><?= htmlspecialchars($facture['numero_facture'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars(number_format($facture['total_ht'] ?? 0, 2)) ?> XAF</td>
                            <td><?= htmlspecialchars(number_format($facture['total_ttc'] ?? 0, 2)) ?> XAF</td>

                            <td><?= htmlspecialchars($facture['date_facturation'] ?? 'Non spécifiée') ?></td>
                            <td><?= htmlspecialchars($facture['mode_paiement'] ?? 'Non spécifié') ?></td>
                            <td><?= htmlspecialchars($facture['statut_facture'] ?? 'Non spécifié') ?></td>
                            <td><a href="views_facture.php?id=<?= $facture['id'] ?>" class="btn btn-view"><i class="fas fa-eye"></i> Voir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune facture trouvée pour ce client.</p>
        <?php endif; ?>
    </div>
    <!-- Section Devis -->
<div class="section">
    <h2 class="section-title"><i class="fas fa-file-invoice icon"></i> Devis</h2>
    <?php if ($devis): ?>
        <table>
            <thead>
                <tr>
                    <th>Numéro Devis</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                    <th>Date de Création</th>
                    <th>Articles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($devis as $dev): ?>
                    <tr>
                        <td>Devis #<?= htmlspecialchars($dev['numero_devis'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars(number_format($dev['total_ht'] ?? 0, 2)) ?> XAF</td>
                        <td><?= htmlspecialchars(number_format($dev['total_ttc'] ?? 0, 2)) ?> XAF</td>
                        <td><?= htmlspecialchars($dev['date_creation'] ?? 'Non spécifiée') ?></td>
                        <td><?= htmlspecialchars($dev['articles'] ?? 'Aucun article') ?></td>
                        <td><a href="views_devis.php?id=<?= $dev['id'] ?>" class="btn btn-view"><i class="fas fa-eye"></i> Voir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun devis trouvé pour ce client.</p>
    <?php endif; ?>
</div>


    <!-- Bouton Retour -->
    <a href="Client.php"><i class="fas fa-arrow-left"></i> Retour à la liste des clients</a>
</body>
</html>
