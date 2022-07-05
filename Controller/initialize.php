<?php

// Session is started and appropriate classes are required
session_start();
require_once('../Model/Friend.php');
require_once('../Model/UsersDataSet.php');

$view = new stdClass();

// Data set of ALL users in the database
$usersDataSet = new UsersDataSet();
$view->usersDataSet = $usersDataSet->fetchAllUsers();

// If user is logged-in, then only is it possible to find userID of logged-in user.
if (isset($_SESSION['user_loggedIn'])) {

    // Getting data of all users, and finding the userID of logged-in user
    foreach ($view->usersDataSet as $userData) {
        if ($userData->getUsername() == $_SESSION['user_loggedIn']) {
            $uId = $userData->getUserID();
        }
    }

    // Getting the number of friend requests the user has received currently
    $friend = new Friend();
    $numRequest = Friend::getRequestCount($uId);

    $view->requestsCount = $numRequest;

}