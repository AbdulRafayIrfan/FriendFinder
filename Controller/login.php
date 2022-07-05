<?php

// To report MySQL errors
$driver = new mysqli_driver();
$driver->report_mode = MYSQLI_REPORT_STRICT;

$view = new stdClass();
$view->pageTitle = 'Login';

require_once('../Model/Login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {

        $uname = $_POST['username'];
        $pswd = $_POST['password'];

        // New instance of Login, with username and password entered
        $login = new Login($uname, $pswd);

        // Method to check if username and password are correct
        $login->authorizeUser();

    }
}

require_once('../View/login.phtml');