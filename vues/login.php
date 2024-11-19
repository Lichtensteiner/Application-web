<?php
session_start();
include '../config/db.php'; // Assure-toi d'inclure correctement le fichier de configuration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    error_log("Tentative de connexion: Username: $username, Password: $password");

    if ($pdo) { // Vérifie que la connexion est bien établie
        // Prépare et exécute la requête
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Utilise password_verify pour comparer les mots de passe
            if (password_verify($password, $user['password'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_name'] = $user['username']; // Exemple : Secrétaire, Comptable, etc.
                error_log("Utilisateur connecté avec succès. Redirection...");
                header('Location: dashboard.php'); // Redirection vers le tableau de bord
                exit();
            } else {
                error_log("Mot de passe incorrect.");
                echo "<div class='error-message'>Identifiants invalides.</div>";
            }
        } else {
            error_log("Aucun utilisateur trouvé pour ce nom d'utilisateur.");
            echo "<div class='error-message'>Identifiants invalides.</div>";
        }
    } else {
        error_log("Erreur de connexion à la base de données.");
        echo "<div class='error-message'>Erreur de connexion à la base de données.</div>";
    }
}
?>
