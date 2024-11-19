<?php
if (isset($_GET['id'])) {
    $alert_id = $_GET['id'];
    $conn = new mysqli("localhost", "root", "", "facturations");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM alertes WHERE id = ?");
    $stmt->bind_param("i", $alert_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $alert = $result->fetch_assoc();

    if ($alert) {
        echo json_encode(["status" => "success", "message" => $alert['message']]);
    } else {
        echo json_encode(["status" => "error", "message" => "Alerte non trouvée"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "ID d'alerte manquant."]);
}
?>
