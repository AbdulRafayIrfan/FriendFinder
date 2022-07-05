<?php

require_once("../Model/Friend.php");
require_once("../Controller/initialize.php");

// Supress warning so it doesn't append to json response
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

// Check if its GET request and that the userID is not empty
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Get userID of logged-in user
    // Getting data of all users, and finding the userID of logged-in user
    foreach ($view->usersDataSet as $userData) {
        if ($userData->getUsername() == $_SESSION['user_loggedIn']) {
            $uId = $userData->getUserID();
        }
    }

    // Initialize class constructor
    $f = new Friend();

    $friendsArray = Friend::getFriendsJSON($uId);

    // Return the search results in JSON format to be used by XHR request
    echo $friendsArray;
}
