<?php
// Connexion à la base de données
$host = 'localhost'; // Votre hôte, généralement localhost
$user = 'root';      // Votre utilisateur de base de données
$password = '';      // Votre mot de passe
$dbname = 'facturations'; // Votre nom de base de données

$conn = new mysqli($host, $user, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifiez si l'ID de la facture est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les détails de la facture
    $query = "SELECT * FROM factures WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $facture = $result->fetch_assoc();
        } else {
            echo "Facture introuvable.";
            exit;
        }

        $stmt->close();
    } else {
        echo "Erreur de préparation : " . $conn->error;
    }
}

// Traitement de la soumission du formulaire
if (isset($_POST['update'])) {
    $type_facture = $_POST['type_facture'];
    $date_facturation = $_POST['date_facturation'];
    $date_echeance = $_POST['date_echeance'];
    $description = $_POST['description'];
    $numero_facture = $_POST['numero_facture'];
    $total_ht = $_POST['total_ht'];
    $tva = $_POST['tva'];
    $total_ttc = $_POST['total_ttc'];
    $notes = $_POST['notes'];
    $mode_paiement = $_POST['mode_paiement'];
    $statut_facture = $_POST['statut_facture'];

    // Mise à jour de la facture
    $queryUpdate = "
        UPDATE factures
        SET
            type_facture = ?,
            date_facturation = ?,
            date_echeance = ?,
            description = ?,
            numero_facture = ?,
            total_ht = ?,
            tva = ?,
            total_ttc = ?,
            notes = ?,
            mode_paiement = ?,
            statut_facture = ?
        WHERE id = ?";

        if ($stmt = $conn->prepare($queryUpdate)) {
            $stmt->bind_param("sssssssssssi", $type_facture, $date_facturation,
            $date_echeance,  $description, $numero_facture, $total_ht, $tva, $total_ttc, $notes, $mode_paiement, $statut_facture, $id);

        if ($stmt->execute()) {
            // Redirige vers la page Facture avec un message de succès
            header("Location: Factures.php?message=Facture mise à jour avec succès&id=" . $id);
            exit;
        } else {
            echo "Erreur lors de la mise à jour : " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erreur de préparation : " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Facture</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./asset/factures.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <style>
        .container { max-width: 800px; margin: 0 auto; padding: 20px; background: #f9f9f9; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-container { text-align: right; }
        .btn { background: #007bff; color: #fff; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modifier la Facture #<?= htmlspecialchars($facture['numero_facture'] ?? '') ?></h1>
        
        <form method="post">

        <div class="form-group">
    <label for="type_facture">Type de Facture</label>
    <select name="type_facture" id="type_facture" required>
        <option value="Classique" <?= ($facture['type_facture'] == 'Classique') ? 'selected' : '' ?>>Facture Classique</option>
        <option value="Proforma" <?= ($facture['type_facture'] == 'Proforma') ? 'selected' : '' ?>>Facture Proforma</option>
        <option value="Acompte" <?= ($facture['type_facture'] == 'Acompte') ? 'selected' : '' ?>>Facture Acompte</option>
        <option value="solde" <?= ($facture['type_facture'] == 'Acompte') ? 'selected' : '' ?>>Facture de Solde</option>
        <option value="récapitulative"<?= ($facture['type_facture'] == 'Acompte') ? 'selected' : '' ?>>Facture Rectificative</option>
        <option value=" avoir"<?= ($facture['type_facture'] == 'Acompte') ? 'selected' : '' ?>>Facture d'Avoir</option>
        <option value="cloture" <?= ($facture['type_facture'] == 'Acompte') ? 'selected' : '' ?>>Facture de Cloture</option>
        <!-- Ajoutez d'autres types de factures si nécessaire -->
    </select>
            </div>
            <div class="form-group">
                <label for="date_facturation">Date de Facturation</label>
                <input type="date" name="date_facturation" id="date_facturation" value="<?= htmlspecialchars($facture['date_facturation'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="date_echeance">Date d'Échéance</label>
                <input type="date" name="date_echeance" id="date_echeance" value="<?= htmlspecialchars($facture['date_echeance'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" name="description" id="description" value="<?= htmlspecialchars($facture['description'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="numero_facture">Numéro de Facture</label>
                <input type="text" name="numero_facture" id="numero_facture" value="<?= htmlspecialchars($facture['numero_facture'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="total_ht">Total HT</label>
                <input type="number" name="total_ht" id="total_ht" value="<?= htmlspecialchars($facture['total_ht'] ?? '0.00') ?>" required>
            </div>
            <div class="form-group">
                <label for="tva">TVA (%)</label>
                <input type="number" step="0.01" name="tva" id="tva" value="<?= htmlspecialchars($facture['tva'] ?? '0.00') ?>" required>
            </div>
            <div class="form-group">
                <label for="total_ttc">Total TTC</label>
                <input type="number" step="0.01" name="total_ttc" id="total_ttc" value="<?= htmlspecialchars($facture['total_ttc'] ?? '0.00') ?>" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" rows="4"><?= htmlspecialchars($facture['notes'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="mode_paiement">Mode de Paiement</label>
                <select name="mode_paiement" id="mode_paiement" required>
                    <option value="Carte de crédit" <?= ($facture['mode_paiement'] == 'Carte de crédit') ? 'selected' : '' ?>>Carte de crédit</option>
                    <option value="Virement bancaire" <?= ($facture['mode_paiement'] == 'Virement bancaire') ? 'selected' : '' ?>>Virement bancaire</option>
                    <option value="Chèque" <?= ($facture['mode_paiement'] == 'Chèque') ? 'selected' : '' ?>>Chèque</option>
                    <option value="Espèces" <?= ($facture['mode_paiement'] == 'Espèces') ? 'selected' : '' ?>>Espèces</option>
                    <option value="Portefeuille num" <?= ($facture['mode_paiement'] == 'Portefeuille num') ? 'selected' : '' ?>>Portefeuille num</option>
                </select>
            </div>
            <div class="form-group">
                <label for="statut_facture">Statut de la Facture</label>
                <select name="statut_facture" id="statut_facture" required>
                    <option value="Payée" <?= ($facture['statut_facture'] == 'Payée') ? 'selected' : '' ?>>Payée</option>
                    <option value="Attente" <?= ($facture['statut_facture'] == 'Attente') ? 'selected' : '' ?>>Attente</option>
                    <option value="En Cours" <?= ($facture['statut_facture'] == 'En Cours') ? 'selected' : '' ?>>En Cours</option>
                    <option value="En Retard" <?= ($facture['statut_facture'] == 'En retard') ? 'selected' : '' ?>>Retard</option>
                    <option value="Annulé" <?= ($facture['statut_facture'] == 'Annulé') ? 'selected' : '' ?>>Annulé</option>
                    
                </select>
            </div>

            <div class="btn-container">
                <button type="submit" name="update" class="btn">Enregistrer</button>
                <a href="Factures.php" class="btn">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        // Ajouter une confirmation avant la soumission du formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir modifier les informations de la facture ?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
