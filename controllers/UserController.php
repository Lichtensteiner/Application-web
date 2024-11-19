<?php
// /controllers/UserController.php
include_once '../config/db.php';
include_once '../models/User.php';

class UserController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function index() {
        $user = $this->userModel->getAllUsers();
        include '../views/users.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $this->userModel->addUser($name, $email, $role);
            header("Location: dashboard.php"); // Redirige vers la page des utilisateurs
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
            $id = $_POST['userId'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $this->userModel->updateUser($id, $name, $email, $role);
            header("Location: index.php"); // Redirige vers la page des utilisateurs
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'])) {
            $id = $_POST['userId'];
            $this->userModel->deleteUser($id);
            header("Location: index.php"); // Redirige vers la page des utilisateurs
            exit;
        }
    }
}
?>
