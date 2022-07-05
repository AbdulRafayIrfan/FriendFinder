<?php

// Session started and appropriate classes required
session_start();
require_once('../Model/Friend.php');
require_once('../Model/UsersDataSet.php');

// If request and id GET values set
if (isset($_GET['request']) && isset($_GET['id'])) {

    // New friend instantiated
    $friend = new Friend();

    // Friend ID is in the GET value 'id'
    $friendID = $_GET['id'];

    // Loops through all Users to get UserID of the logged-in user
    $DataSet = new UsersDataSet();
    $allUsersData = $DataSet->fetchAllUsers();
    foreach ($allUsersData as $userData) {
        if ($userData->getUsername() == $_SESSION['user_loggedIn']) {
            $userID = $userData->getUserID();
        }
    }

    // Appropriate friend methods called depending on GET values
    if ($_GET['request'] == 'accept') {

        Friend::acceptRequest($userID, $friendID);

    } elseif ($_GET['request'] == 'ignore') {

        Friend::ignoreRequest($userID, $friendID);

    } elseif ($_GET['request'] == 'unfriend') {

        Friend::unfriendUser($userID, $friendID);

    } elseif ($_GET['request'] == 'send') {

        Friend::sendRequest($userID, $friendID);
    }

}
