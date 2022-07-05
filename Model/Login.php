<?php

require_once('Database.php');

session_start();

/** This class manages the login to the website */
class Login
{
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * This function authorizes the user into the database provided
     * there's no incorrect details input.
     * The function checks if the entered username exists in the database,
     * thus checking the entered password with the hashed password in the database
     * through PHP in-built function.
     * Redirects the user back to login page displaying relevant error
     * message provided there are errors.
     */
    public function authorizeUser() {
        $conn = Database::connect();

        $stmt = $conn->prepare("SELECT password from users WHERE username = ?");

        $stmt->bind_param("s", $this->username);

        if (!$stmt->execute()) {
            $stmt = null;
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/login.php");
            exit();
        }

        $result = $stmt->get_result();

        if (!$result->num_rows > 0)
        {
            $stmt = null;
            $_SESSION['error'] = "Username not found!";
            header("Location: ../Controller/login.php");
            exit();
        }


        $dataAssoc = $result->fetch_assoc();

        $passwordCheck = password_verify($this->password, $dataAssoc["password"]);


        if ($passwordCheck == false) {
            $stmt = null;
            $_SESSION['error'] = "Incorrect password!";
            header("Location: ../Controller/login.php");
            exit();
        }

        $result = null;
        $dataAssoc = null;
        $stmt = null;
        $conn->close();


        $_SESSION['user_loggedIn'] = $this->username;
        session_write_close();

        header("Location: ../Controller/home.php");
        exit();
    }

}