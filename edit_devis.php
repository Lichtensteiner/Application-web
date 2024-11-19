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

// Vérifiez si l'ID du devis est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les détails du devis
    $query = "SELECT * FROM devis WHERE id = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $devis = $result->fetch_assoc();
        } else {
            echo "Devis introuvable.";
            exit;
        }

        $stmt->close();
    } else {
        echo "Erreur de préparation : " . $conn->error;
    }
}

// Traitement de la soumission du formulaire
if (isset($_POST['update'])) {
    $date_creation = $_POST['date_creation'];
    $date_validite = $_POST['date_validite'];
    $total_ht = $_POST['total_ht'];
    $tva = $_POST['tva'];
    $total_ttc = $_POST['total_ttc'];
    $statut_devis = $_POST['statut_devis'];
    $notes = $_POST['notes'];
    // Mise à jour du devis
    $queryUpdate = "
    UPDATE devis
    SET
        date_creation = ?,
        date_validite = ?,
        total_ht = ?,
        tva = ?,
        total_ttc = ?,
        notes = ?,
        statut_devis = ?
    WHERE id = ?";



    if ($stmt = $conn->prepare($queryUpdate)) {
        $stmt->bind_param("sssddssi", $date_creation, $date_validite, $total_ht, $tva, $total_ttc, $notes, $statut_devis, $id);
    

        if ($stmt->execute()) {
            echo "Devis mis à jour avec succès.";
            // Redirection après 3 secondes
            header("Refresh:3; url=Devis.php?id=" . htmlspecialchars($devis['id'] ?? ''));
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
    <title>Modifier le Devis</title>
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
        <h1>Modifier le Devis #<?= htmlspecialchars($devis['numero_devis'] ?? '') ?></h1>
        
        <form method="post">
            <div class="form-group">
                <label for="date_creation">Date de Création</label>
                <input type="date" name="date_creation" id="date_creation" value="<?= htmlspecialchars($devis['date_creation'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="date_validite">Date de Validité</label>
                <input type="date" name="date_validite" id="date_validite" value="<?= htmlspecialchars($devis['date_validite'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="numero_devis">Numéro de Devis</label>
                <input type="text" name="numero_devis" id="numero_devis" value="<?= htmlspecialchars($devis['numero_devis'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="total_ht">Total HT</label>
                <input type="number" step="0.01" name="total_ht" id="total_ht" value="<?= htmlspecialchars($devis['total_ht'] ?? '0.00') ?>" required>
            </div>
            <div class="form-group">
                <label for="tva">TVA (%)</label>
                <input type="number" step="0.01" name="tva" id="tva" value="<?= htmlspecialchars($devis['tva'] ?? '0.00') ?>" required>
            </div>
            <div class="form-group">
                <label for="total_ttc">Total TTC</label>
                <input type="number" step="0.01" name="total_ttc" id="total_ttc" value="<?= htmlspecialchars($devis['total_ttc'] ?? '0.00') ?>" required>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" rows="4"><?= htmlspecialchars($devis['notes'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
             <label for="statut_devis">Statut du Devis</label>
                 <select name="statut_devis" id="statut_devis" required>
        <option value="En attente" <?= (isset($devis['statut_devis']) && $devis['statut_devis'] == 'En attente') ? 'selected' : '' ?>>En attente</option>
        <option value="Rejeté" <?= (isset($devis['statut_devis']) && $devis['statut_devis'] == 'rejeté') ? 'selected' : '' ?>>Rejeté</option>
        <option value="Facturé" <?= (isset($devis['statut_devis']) && $devis['statut_devis'] == 'facturé') ? 'selected' : '' ?>>Facturé</option>
        <option value="Expiré" <?= (isset($devis['statut_devis']) && $devis['statut_devis'] == 'expiré') ? 'selected' : '' ?>>Expiré</option>
        <option value="Envoyé" <?= (isset($devis['statut_devis']) && $devis['statut_devis'] == 'envoyé') ? 'selected' : '' ?>>Envoyé</option>
        <option value="En cours" <?= (isset($devis['statut_devis']) && $devis['statut_devis'] == 'en cours') ? 'selected' : '' ?>>En Cours</option>
        <option value="Approuvé" <?= (isset($devis['statut_devis']) && $devis['statut_devis'] == 'approuvé') ? 'selected' : '' ?>>Approuvé</option>
        <option value="Accepté" <?= (isset($devis['statut_devis']) && $devis['statut_devis'] == 'accepté') ? 'selected' : '' ?>>Accepté</option>
                 </select>
            </div>
            <div class="btn-container">
                <button type="submit" name="update" class="btn">Enregistrer</button>
                <a href="./Devis.php?= htmlspecialchars($devis['id'] ?? '') ?>" class="btn">Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>

<?php
// Fermer la connexion
$conn->close();
?>
