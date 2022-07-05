<?php

require_once('Database.php');
session_start();

/** This class manages the registration of the user into the database */
class RegisterUser
{

    /**
     * @param $fullName
     * @param $email
     * @param $username
     * @param $password
     * Takes in values entered in the register form as parameters provided they are valid
     * Prepares a statement and creates a user record into the database from the parameters
     * Redirects the user to the home page on successful registration
     */
    public static function createUser($fullName ,$email, $username, $password) {
        $conn = Database::connect();

        $stmt = $conn->prepare('INSERT INTO users (full_name, email, username, password, profile_image) VALUES (?, ?, ?, ?, ?); ');

        // Hash password before inserting into database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // The image path for the default profile image
        $defaultImgPath = "../Images/profile-default.png";

        // Attaching values to prepared SQL statement
        $stmt->bind_param("sssss", $fullName, $email, $username, $hashedPassword, $defaultImgPath);


        if(!$stmt->execute()) {
            $stmt = null;
            $_SESSION['error'] = "Statement failed!";
            header("location: ../Controller/register.php");
            exit();
        }

        $stmt = null;

        $conn->close();

        // Start session and direct user to home page
        $_SESSION['user_loggedIn'] = $username;
        header("Location: ../Controller/home.php");
    }
}