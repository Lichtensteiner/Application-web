<?php
include './config/db.php';

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    // Supprimer le client
    $sql = "DELETE FROM clients WHERE id = ?";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute([$clientId]);
        header('Location: Client.php');
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
} else {
    die("Aucun client spécifié.");
}
?>
