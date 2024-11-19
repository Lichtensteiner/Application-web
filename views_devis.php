<?php
// Connexion à la base de données
$host = 'localhost';
$db = 'facturations';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}

// Récupérer l'identifiant du devis
$idDevis = $_GET['id'];

// Préparer et exécuter la requête pour récupérer les données du devis
$queryDevis = "
    SELECT d.*, c.nom AS client_nom, c.entreprise AS entreprise_nom
    FROM devis d
    JOIN clients c ON d.client_id = c.id
    WHERE d.id = ?";
$stmt = $conn->prepare($queryDevis);
$stmt->execute([$idDevis]);
$devis = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifiez si le devis existe
if (!$devis) {
    die("Erreur : Devis introuvable.");
}

// Affichage des détails du devis
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Devis #<?= htmlspecialchars($devis['id']) ?></title>
    <link rel="shortcut icon" href="./img/Image1.png">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Style de base, vous pouvez personnaliser le design ici */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #333;
        }
        .section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Détails du Devis #<?= htmlspecialchars($devis['id']) ?></h2>
        
        <div class="section">
            <h3>Informations Générales</h3>
            <table>
                <tr>
                    <th>Date de création</th>
                    <th>Date de validité</th>
                    <th>Client</th>
                    <th>Entreprise</th>
                    <th>TVA</th>
                    <th>Total HT</th>
                    <th>Total TTC</th>
                    <th>Statut devis</th>
                    <th>Notes</th>
                </tr>
                <tr>
                    <td><?= isset($devis['date_creation']) ? htmlspecialchars(date("d/m/Y", strtotime($devis['date_creation']))) : '' ?></td>
                    <td><?= isset($devis['date_validite']) ? htmlspecialchars(date("d/m/Y", strtotime($devis['date_validite']))) : '' ?></td>
                    <td><?= htmlspecialchars($devis['client_nom']) ?></td>
                    <td><?= htmlspecialchars($devis['entreprise_nom']) ?></td>
                    <td><?= htmlspecialchars($devis['tva']) ?> %</td>
                    <td><?= isset($devis['total_ht']) ? htmlspecialchars($devis['total_ht']) : 'Non spécifié' ?> XAF</td>
                    <td><?= isset($devis['total_ttc']) ? htmlspecialchars($devis['total_ttc']) : 'Non spécifié' ?> XAF</td>
                    <td><?= isset($devis['statut_devis']) ? htmlspecialchars($devis['statut_devis']) : 'Non spécifié' ?> </td>
                    <td><?= isset($devis['notes']) ? nl2br(htmlspecialchars($devis['notes'])) : 'Aucune note.' ?></td>
                </tr>
            </table>
        </div>
        
        <div class="section">
    <h3>Produits / Services</h3>
    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Récupérer les produits/services associés au devis
            $queryProduits = "SELECT description, quantity, unit_price, total FROM devis_articles WHERE devis_id = ?";
            $stmtProduits = $conn->prepare($queryProduits);
            $stmtProduits->execute([$idDevis]);
            while ($produit = $stmtProduits->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($produit['description']) . "</td>";
                echo "<td>" . htmlspecialchars($produit['quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($produit['unit_price']) . " XAF</td>";
                echo "<td>" . htmlspecialchars($produit['total']) . " XAF</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

        
        <div class="section" style="text-align: center;">
            <button onclick="window.print()">Imprimer le Devis</button>
            <button onclick="window.location.href='mailto:?subject=Devis%20#<?= htmlspecialchars($devis['id']) ?>&body=Voici%20le%20devis%20#<?= htmlspecialchars($devis['id']) ?>.'">Envoyer par Email</button>
        </div>
    </div>
</body>
</html>
