<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facturations";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données.']));
}

$id = $_POST['id'];
$source = $_POST['source'];

if ($source === 'facture') {
    $sql = "DELETE FROM factures WHERE id = ?";
} elseif ($source === 'client') {
    $sql = "DELETE FROM clients WHERE id = ?";
} elseif ($source === 'devis') {
    $sql = "DELETE FROM devis WHERE id = ?";
} else {
    echo json_encode(['success' => false, 'message' => 'Source invalide.']);
    exit;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Échec de la suppression.']);
}

$stmt->close();
$conn->close();
?>
