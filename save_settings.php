<?php
// Inclure la connexion à la base de données
include 'facturations'; // Assurez-vous d'avoir une connexion à votre base de données

// Vérifiez si la requête est en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données envoyées
    $companyName = $_POST['companyName'];
    $currency = $_POST['currency'];
    $language = $_POST['language'];
    $invoicePrefix = $_POST['invoicePrefix'];
    $quotePrefix = $_POST['quotePrefix'];
    $paymentTerms = $_POST['paymentTerms'];
    $paymentReminderDays = $_POST['paymentReminderDays'];
    $quoteExpirationDays = $_POST['quoteExpirationDays'];
    $backupFrequency = $_POST['backupFrequency'];
    $backupRetention = $_POST['backupRetention'];
    $userEmail = $_POST['userEmail'];
    $userPassword = $_POST['userPassword']; // Assurez-vous de gérer le mot de passe en toute sécurité

    // Requête d'insertion ou de mise à jour des paramètres
    $sql = "UPDATE settings SET
        company_name = ?,
        currency = ?,
        language = ?,
        invoice_prefix = ?,
        quote_prefix = ?,
        payment_terms = ?,
        payment_reminder_days = ?,
        quote_expiration_days = ?,
        backup_frequency = ?,
        backup_retention = ?,
        user_email = ?,
        user_password = ? WHERE id = 1"; // Remplacez 1 par l'identifiant approprié

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $companyName, $currency, $language, $invoicePrefix, $quotePrefix, $paymentTerms, $paymentReminderDays, $quoteExpirationDays, $backupFrequency, $backupRetention, $userEmail, $userPassword);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
