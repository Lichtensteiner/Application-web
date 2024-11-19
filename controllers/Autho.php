<?php
// auth.php

// Inclure le fichier de configuration de la base de données
include '../config/db.php';

// Fonction pour vérifier les permissions de l'utilisateur
function hasPermission($permission) {
    global $conn;

    // Récupérer le rôle de l'utilisateur depuis la session
    $userRole = $_SESSION['user_role'] ?? null;

    // Vérifier si l'utilisateur a un rôle
    if ($userRole === null) {
        return false; // Aucun rôle, donc aucune permission
    }

    // Préparer et exécuter la requête pour vérifier la permission
    $stmt = $conn->prepare("SELECT 1 FROM role_permission WHERE role_id = ? AND permission = ?");
    $stmt->bind_param("is", $userRole, $permission);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0; // Retourne true si la permission est trouvée
}

// Fonction pour rediriger en cas d'accès interdit
function requirePermission($permission) {
    if (!hasPermission($permission)) {
        header("Location: no_permission.php");
        exit();
    }
}
?>
