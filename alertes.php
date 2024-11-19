<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facturations";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtenir la date actuelle
$date_actuelle = date('Y-m-d');

// Requête pour récupérer les factures dont la date d'échéance est proche ou dépassée
$sql = "SELECT * FROM factures WHERE date_echeance <= CURDATE()";  // Factures dont la date d'échéance est aujourd'hui ou passée
$result = $conn->query($sql);

$alertes = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date_echeance = $row['date_echeance'];
        
        // Factures qui ont déjà dépassé la date d'échéance
        if ($date_echeance < $date_actuelle) {
            $alertes[] = ['type' => 'rouge', 'details' => $row, 'source' => 'facture'];
        }
        // Factures dont la date d'échéance est proche (moins de 7 jours)
        elseif (strtotime($date_echeance) <= strtotime($date_actuelle . ' +7 days')) {
            $alertes[] = ['type' => 'vert', 'details' => $row, 'source' => 'facture'];
        }
    }
}

// Requête pour les clients
$sql_clients = "SELECT id, entreprise, email, created_at FROM clients";
$result_clients = $conn->query($sql_clients);

if ($result_clients->num_rows > 0) {
    while ($client = $result_clients->fetch_assoc()) {
        $alertes[] = ['type' => 'vert', 'details' => $client, 'source' => 'client'];
    }
}

// Requête pour récupérer les devis valides
$sql_devis = "SELECT id, numero_devis, total_TTC, date_validite FROM devis WHERE date_validite >= CURDATE()";
$result_devis = $conn->query($sql_devis);

if ($result_devis->num_rows > 0) {
    while ($devis = $result_devis->fetch_assoc()) {
        $alertes[] = ['type' => 'bleu', 'details' => $devis, 'source' => 'devis'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./asset/alerte.css">
    <link rel="stylesheet" href="./asset/alertes.css">
    <link rel="shortcut icon" href="./img/Image1.png">
    <link rel="stylesheet" href="./asset/delete.css">
    <title>Système d'Alerte</title>
</head>
<body>
    <h1><i class="fas fa-bell"></i> Système d'Alerte</h1>
    <?php
        include 'composant/nav.php';
    ?>
    <div class="alert-container">
        <?php foreach ($alertes as $alerte): ?>
            <div class="alert-card <?= $alerte['type'] ?>">
                <div class="alert-icon">
                    <?php if ($alerte['type'] == 'rouge'): ?>
                        <i class="fas fa-exclamation-circle" style="color: #dc3545;"></i>
                    <?php elseif ($alerte['type'] == 'vert'): ?>
                        <i class="fas fa-user-plus" style="color: #28a745;"></i>
                    <?php elseif ($alerte['type'] == 'bleu'): ?>
                        <i class="fas fa-file-alt" style="color: #007bff;"></i>
                    <?php endif; ?>
                </div>

                <div class="alert-details">
                    <?php if ($alerte['source'] == 'facture'): ?>
                        <strong>Facture N°: <?= $alerte['details']['numero_facture'] ?></strong><br>
                        <span>Total TTC: <?= $alerte['details']['total_ttc'] ?> XAF</span><br>
                        <span>Date d'échéance: <?= $alerte['details']['date_echeance'] ?></span><br>
                    <?php elseif ($alerte['source'] == 'client'): ?>
                        <strong>Nouveau client: <?= $alerte['details']['entreprise'] ?></strong><br>
                        <span>Email: <?= $alerte['details']['email'] ?></span><br>
                        <span>Date d'enregistrement: <?= $alerte['details']['created_at'] ?></span><br>
                    <?php elseif ($alerte['source'] == 'devis'): ?>
                        <strong>Devis N°: <?= $alerte['details']['numero_devis'] ?></strong><br>
                        <span>Total TTC: <?= $alerte['details']['total_TTC'] ?> XAF</span><br>
                        <span>Date de validité: <?= $alerte['details']['date_validite'] ?></span><br>
                    <?php endif; ?>
                </div>
                <div class="alert-actions">
                    <button class="delete-alert" data-id="<?= $alerte['details']['id'] ?>" data-source="<?= $alerte['source'] ?>">Supprimer</button>
                </div>
                <div class="alert-actions">
                    <button class="btn <?= $alerte['type'] ?>">
                    <?= $alerte['type'] == 'rouge'
                        ? '<span style="color: #dc3545;">Échéance dépassée</span>'
                        : ($alerte['type'] == 'vert'
                            ? '<span style="color: #28a745;">Nouveau client</span>'
                            : '<span style="color: #007bff;">Devis valide</span>')
                    ?>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".delete-alert").on("click", function() {
                const alertId = $(this).data("id");
                const alertSource = $(this).data("source");

                if (confirm("Êtes-vous sûr de vouloir supprimer cette alerte ?")) {
                    $.ajax({
                        url: 'delete_alert.php',
                        method: 'POST',
                        data: { id: alertId, source: alertSource },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.success) {
                                alert("Alerte supprimée avec succès !");
                                location.reload();
                            } else {
                                alert("Erreur : " + result.message);
                            }
                        },
                        error: function() {
                            alert("Erreur de connexion.");
                        }
                    });
                }
            });
        });
    </script>

</html>
