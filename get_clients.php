<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = ""; // Remplacez par votre mot de passe
$dbname = "facturations"; // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Requête pour récupérer les clients
$sql = "SELECT id, nom FROM clients"; // Assurez-vous que ces colonnes existent dans votre table
$result = $conn->query($sql);

$clients = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
}

// Fermez la connexion
$conn->close();

// Retourner les clients en JSON
header('Content-Type: application/json');
echo json_encode($clients);
?>
