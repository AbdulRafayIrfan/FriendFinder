<?php

require_once('initialize.php');

$view->pageTitle = 'Friends';

if (isset($_SESSION['user_loggedIn'])) {

    // Gets the friends list of the user through Friend class
    $view->friendsList = Friend::getFriends($uId);
}


require_once('../View/friends_list.phtml');
