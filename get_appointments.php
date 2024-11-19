<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = ""; // Remplacez par votre mot de passe
$dbname = "facturations"; // Remplacez par le nom de votre base de données

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les rendez-vous
$sql = "SELECT appointments.*, clients.name AS client_name 
        FROM appointments 
        JOIN clients ON appointments.client_id = clients.id";
$result = $conn->query($sql);

$appointments = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}
$conn->close();

// Retourner les données en JSON
header('Content-Type: application/json');
echo json_encode($appointments);
?>
