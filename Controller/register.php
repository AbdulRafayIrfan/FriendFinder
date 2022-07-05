<?php

require_once('../Model/Register.php');

$view = new stdClass();
$view->pageTitle = 'Register';

// To report MySQL errors
$driver = new mysqli_driver();
$driver->report_mode = MYSQLI_REPORT_STRICT;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit']) && empty($_POST['spamInput'])) {
        //Getting data from register form
        $fullName = $_POST['fullname'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // creates instance of register class, sending it values from register form
        $register = new Register($fullName, $username, $email, $password);

        // Call RegisterUser method, which registers the user into the database
        $register->registerUser();
    }
}

require_once('../View/register.phtml');