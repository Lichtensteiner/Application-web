<?php
session_start();
include '../config/db.php'; // Assure-toi d'inclure correctement le fichier de configuration

$response = ['success' => false, 'message' => 'Inscription échouée.'];

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['registerUsername'];
        $email = $_POST['registerEmail'];
        $password = password_hash($_POST['registerPassword'], PASSWORD_DEFAULT);
        $role = $_POST['registerRole'];

        if ($pdo) { // Vérifie que la connexion est bien établie
            // Vérification de l'unicité du nom d'utilisateur
            $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE username = :username');
            $stmt->execute(['username' => $username]);

            if ($stmt->rowCount() > 0) {
                $response['message'] = 'Nom d\'utilisateur déjà pris.';
            } else {
                // Insertion de l'utilisateur
                $stmt = $pdo->prepare('INSERT INTO utilisateurs (username, email, password, role) VALUES (:username, :email, :password, :role)');
                if ($stmt->execute(['username' => $username, 'email' => $email, 'password' => $password, 'role' => $role])) {
                    $response['success'] = true;
                    $response['message'] = 'Inscription réussie pour ' . $username . ' avec le rôle de ' . $role . '!';
                }
            }
        } else {
            $response['message'] = 'Erreur de connexion à la base de données.';
        }
    }
} catch (PDOException $e) {
    $response['message'] = 'Erreur de serveur : ' . $e->getMessage();
}

echo json_encode($response);
?>
