<?php 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$username = "root"; // ou votre nom d'utilisateur
$password = ""; // ou votre mot de passe
$dbname = "facturations";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    echo "Connexion réussie.";
} catch (mysqli_sql_exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}



// Vérifier si un ID de facture est passé via l'URL
if (isset($_GET['id'])) {
    $facture_id = (int) $_GET['id']; // Convertir en entier

    // Vérifier si l'ID est valide
    if ($facture_id <= 0) {
        die("ID de facture non valide.");
    }

    // Préparer la requête SQL pour supprimer la facture
    $sql = "DELETE FROM factures WHERE id = ?";

    // Préparer et exécuter la requête
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $facture_id); // Lier le paramètre id
        $stmt->execute();

        // Vérifier si la suppression a réussi
        if ($stmt->affected_rows > 0) {
            // Rediriger vers la page des factures après la suppression
            header("Location: Factures.php?message=deleted");
            exit();
        } else {
            // Si la facture n'a pas été supprimée
            echo "Erreur : La facture n'a pas pu être supprimée ou n'existe pas.";
        }

        // Fermer la requête
        $stmt->close();
    } else {
        echo "Erreur dans la préparation de la requête : " . $conn->error; // Afficher l'erreur
    }
} else {
    // Si aucun ID n'a été passé via l'URL
    echo "Aucune facture spécifiée.";
}

// Fermer la connexion à la base de données
$conn->close();
?>
