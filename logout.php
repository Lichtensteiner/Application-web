<?php
// Démarrer la session
session_start();

// Détruire toutes les sessions
session_unset();
session_destroy();

// Rediriger vers la page de connexion
header("Location: ../../index.php");
exit();
?>
