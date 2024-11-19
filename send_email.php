<?php
require 'PHPMailer/PHPMailerAutoload.php'; // Assurez-vous que PHPMailer est inclus

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'facturations';
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Erreur de connexion : ' . $conn->connect_error);
}

$factureId = $_GET['id'] ?? null;

if (!$factureId) {
    die("Facture non spécifiée.");
}

$queryFacture = "
    SELECT f.*, c.email AS client_email
    FROM factures f
    JOIN clients c ON f.client_id = c.id
    WHERE f.id = ?";

$stmt = $conn->prepare($queryFacture);
$stmt->bind_param("i", $factureId);
$stmt->execute();
$result = $stmt->get_result();

$facture = $result->fetch_assoc();

if (!$facture) {
    die("Facture non trouvée.");
}

// Configurer PHPMailer
$mail = new PHPMailer;
$mail->setFrom('votre_email@example.com', 'Votre Nom'); // Votre adresse email
$mail->addAddress($facture['client_email']); // L'adresse email du client
$mail->Subject = 'Votre Facture #' . htmlspecialchars($facture['numero_facture']);
$mail->Body    = 'Bonjour, voici votre facture: ' . 'http://votre_site.com/facture.php?id=' . htmlspecialchars($facture['id']);
$mail->isHTML(true);

if(!$mail->send()) {
    echo 'Erreur lors de l\'envoi: ' . $mail->ErrorInfo;
} else {
    echo 'Facture envoyée avec succès!';
}

$conn->close();
?>
