<?php

require_once('initialize.php');

$view->pageTitle = 'User Profile';

if (isset($_SESSION['user_loggedIn'])) {
    $friendsList = Friend::getFriends($uId);

// If user finds his own profile
    if ($_GET['usr'] == $uId) {
        header("Location: ../Controller/profile.php");
    }

    if (Friend::alreadyRequested($uId, $_GET['usr']) == true) {

        if (Friend::isRequestSender($uId, $_GET['usr']) == true) {
            $view->isRequestSender = true;
        } else {
            $view->isRequestSender = false;
        }

    } elseif (Friend::checkIfFriend($uId, $_GET['usr']) == true) {
        $view->isFriend = true;
    }

}
require_once('../View/user_profile.phtml');
