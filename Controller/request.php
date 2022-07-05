<?php

require_once('initialize.php');

$view->pageTitle = 'Requests';

// Friend requests only obtained if user is logged-in
if (isset($_SESSION['user_loggedIn'])) {

    $view->requestsCount = $numRequest;

    // If there are any friend requests
    if ($numRequest > 0) {
        // Gets the details of each user who has friend requested the user
        $view->allRequestDetails = Friend::getRequestDetails($uId);
    }

}

require_once('../View/request_page.phtml');
