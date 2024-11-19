<?php 

// Activer le rapport d'erreurs pour MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root"; // ou votre nom d'utilisateur
$password = ""; // ou votre mot de passe
$dbname = "facturations";

try {
    // Créer une connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);
    echo "Connexion réussie.";
} catch (mysqli_sql_exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Vérifier si un ID de devis est passé via l'URL
if (isset($_GET['id'])) {
    $devis_id = (int) $_GET['id']; // Convertir en entier

    // Vérifier si l'ID est valide
    if ($devis_id <= 0) {
        die("ID de devis non valide.");
    }

    // Préparer la requête SQL pour supprimer le devis
    $sql = "DELETE FROM devis WHERE id = ?";

    // Préparer et exécuter la requête
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $devis_id); // Lier le paramètre id
        $stmt->execute();

        // Vérifier si la suppression a réussi
        if ($stmt->affected_rows > 0) {
            // Rediriger vers la page des devis après la suppression
            header("Location: Devis.php?message=deleted");
            exit();
        } else {
            // Si le devis n'a pas été supprimé
            echo "Erreur : Le devis n'a pas pu être supprimé ou n'existe pas.";
        }

        // Fermer la requête
        $stmt->close();
    } else {
        echo "Erreur dans la préparation de la requête : " . $conn->error; // Afficher l'erreur
    }
} else {
    // Si aucun ID n'a été passé via l'URL
    echo "Aucun devis spécifié.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
