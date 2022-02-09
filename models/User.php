<?php
session_start();
class User {

    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $email;


    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        $query = "SELECT id FROM"
            . $this->table_name .
            " WHERE username = ?";

        if($stmt = $this->conn->prepare($query)) {
            $stmt->bindParam("s", $this->username);

            if($stmt->execute()) {
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    return false;
                }

                $stmt->close();
            }
        }

        // Prepare an insert statement
        $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

        if($stmt = $this->conn->prepare($query)) {
            $stmt->bindParam("sss", $this->username, $this->password, $this->email);

            if($stmt->execute()) {
                return true;
            } else {
                return false;
            }

        }
    }

    public function login() {
        $query = "SELECT id, username, password, email FROM users WHERE username = ? AND password = ?";

        if($stmt = $this->conn->prepare($query)) {
            $stmt->bindParam("ss", $this->username, $this->password);

            if($stmt->execute()) {
                $stmt->store_result();

                if($stmt->fetch()) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["username"] = $this->username;
                    header("location: index.php");
                    return true;
                } else {
                    return false;
                }


            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>