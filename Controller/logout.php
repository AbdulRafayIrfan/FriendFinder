<?php

// Destroys the current session and unsets the session value
session_start();
session_destroy();
unset($_SESSION['user_loggedIn']);

// Redirect user to login page
header('Location: login.php');