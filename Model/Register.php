<?php

require_once('Database.php');
require_once('RegisterUser.php');

/** This class manages the validity checks done on the registration form */
class Register
{
    private $fullName;
    private $username;
    private $email;
    private $password;

    public function __construct($fullName, $username, $email, $password) {
        $this->fullName = $fullName;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * This function uses the other method and checks if all the form input is valid
     * Redirects user appropriately and outputs the relevant error message
     * If no invalid data, the RegisterUser class method createUser to create the user
     */
    public function registerUser() {
        if ($this->checkUsername() == false) {
            // return appropriate error message
            $_SESSION['error'] = "Invalid username!";
            header("location: ../Controller/register.php");
            exit();
        }
        if ($this->checkPassword() == false) {
            // return appropriate error message
            $_SESSION['error'] = "Invalid Password!";
            header("location: ../Controller/register.php");
            exit();
        }
        if ($this->checkEmail() == false) {
            // return appropriate error message
            $_SESSION['error'] = "Invalid email!";
            header("location: ../Controller/register.php");
            exit();
        }
        if ($this->isAlreadyTaken() == true) {
            // return appropriate error message
            $_SESSION['error'] = "Username / Email already taken!";
            header("location: ../Controller/register.php");
            exit();
        }

        // Creates the user if no error has occurred
        RegisterUser::createUser($this->fullName, $this->email, $this->username, $this->password);
    }

    /**
     * @return bool
     * This function checks if the entered username is valid depending on the preg conditions:
     * Username should only consist of letters and/or numbers
     * Returns appropriate boolean value
     */
    private function checkUsername(): bool
    {
        if(!preg_match("/^[a-zA-Z0-9]*$/", $this->username))
        {
            $isValid = false;
        }
        else
        {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * @return bool
     * Checks if the email entered is valid based on a built-in function in php
     * The email is first sanitized and then checked for validity
     * Returns appropriate boolean value
     */
    private function checkEmail(): bool
    {
        $email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $isValid = false;
        }
        else
        {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * @return bool
     * This function checks if the password entered is of appropriate length
     * Returns appropriate boolean value
     */
    private function checkPassword(): bool
    {
        if(strlen($this->password) < 6)
        {
            $isValid = false;
        }
        else
        {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     * @return bool|void
     * This function check if the Username/Email entered is already taken
     * The database is checked, by first preparing statements and checking
     * if username/email already exists
     * Returns appropriate boolean value
     */
    private function isAlreadyTaken(){
        $conn = Database::connect();

        $stmt = $conn->prepare("SELECT COUNT(userID) AS count FROM users WHERE username = ? OR email = ?");

        // Attach variables to the prepared SQL statement
        $stmt->bind_param("ss", $this->username, $this->email);

        if (!$stmt->execute()) {
            $stmt = null;
            $_SESSION['error'] = "Statement failed!";
            header("location: ../Controller/register.php");
            exit();
        }

        // Getting object of result from query
        $result = $stmt->get_result()->fetch_object();

        // Using object to get Alias value used in query
        if ($result->count > 0) {
            $taken = true;
        } else {
            $taken = false;
        }

        $conn->close();

        return $taken;

    }
}