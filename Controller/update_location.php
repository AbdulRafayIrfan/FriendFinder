<?php

require_once('initialize.php');
require_once('../Model/UpdateLocation.php');

// Check if request method was post, since that is done via AJAX call
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get and decode json response from request body
    $request_body = file_get_contents('php://input');
    $jsonData = json_decode($request_body);

    // Get userID of logged-in user
    // Getting data of all users, and finding the userID of logged-in user
    foreach ($view->usersDataSet as $userData) {
        if ($userData->getUsername() == $_SESSION['user_loggedIn']) {
            $uId = $userData->getUserID();
        }
    }

    // Send AJAX data to UpdateLocation class to update in database
    $ul = new UpdateLocation($jsonData->latitude, $jsonData->longitude, $uId, $jsonData->timestamp);
    $ul->updateLocation();

}




