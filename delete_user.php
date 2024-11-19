<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "facturations";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupération des données envoyées
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

// Suppression de l'utilisateur
$sql = "DELETE FROM utilisateurs WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
}

$conn->close();
?>
