<?php

require_once('initialize.php');
require_once('../Model/Paging.php');
require_once('../Model/Search.php');

$view->pageTitle = 'Connect';

// If user types in the search box and submits
if (isset($_POST['search-button']) && !empty($_POST['search-term'])) {

    $search = new Search($_POST['search-term']);

    // Check to see if the search term is valid or not
    $search->isValidTerm();

    // Get the search results and store in variable to be accessed by view
    $view->resultsData = $search->getSearchResults();

} else {

    // Calculating Total Number of pages
    $view->totalPages = ceil(count($view->usersDataSet) / 10);

    if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
    }

    $view->pagedData = Paging::getAllPaged($_GET['page']);

    $view->currentPage = $_GET['page'];
}

require_once('../View/all_users_display.phtml');
