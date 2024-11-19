<?php
// Connexion à la base de données
$conn = new PDO('mysql:host=localhost;dbname=facturations', 'root', '');

// Récupérer le nombre de factures échues
$queryFactures = $conn->prepare("SELECT COUNT(*) as total FROM factures WHERE date_echeance < NOW()");
$queryFactures->execute();
$facturesEchues = $queryFactures->fetch(PDO::FETCH_ASSOC)['total'];

// Récupérer le nombre de devis expirés
$queryDevis = $conn->prepare("SELECT COUNT(*) as total FROM devis WHERE date_validite < NOW()");
$queryDevis->execute();
$devisExpirés = $queryDevis->fetch(PDO::FETCH_ASSOC)['total'];

// Retourner le total des notifications
$totalNotifications = $facturesEchues + $devisExpirés;
echo json_encode(['total' => $totalNotifications]);
?>
