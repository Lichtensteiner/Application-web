<?php
// /models/User.php
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addUser($name, $email, $role) {
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $role]);
    }

    public function updateUser($id, $name, $email, $role) {
        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$name, $email, $role, $id]);
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
