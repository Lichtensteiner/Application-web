<?php
require_once 'controllers/ClientController.php';
require_once 'models/Client.php';
require_once 'controller/DevisController.php';

$controller = $_GET['controller'];
$action = $_GET['action'];

switch($controller) {
    case 'Client':
        $clientController = new ClientController();
        if ($action == 'ajouterClient') {
            $clientController->ajouterClient();
        }
        break;
    // Autres contrôleurs...
}
?>
