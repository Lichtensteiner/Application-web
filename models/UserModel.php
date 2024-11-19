<?php
class UserModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getUserByUsername($username) {
        $query = $this->db->prepare("SELECT * FROM utilisateurs WHERE username = :username");
        $query->bindParam(":username", $username);
        $query->execute();
        return $query->fetch();
    }

    public function createUser($username, $email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = $this->db->prepare("INSERT INTO utilisateurs (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $query->bindParam(":username", $username);
        $query->bindParam(":email", $email);
        $query->bindParam(":password", $hashedPassword);
        $query->bindParam(":role", $role);
        return $query->execute();
    }
}
?>
