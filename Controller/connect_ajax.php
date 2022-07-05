<?php

require_once('../Model/Search.php');

// Check if input field search term inside search box is not empty
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["q"])) {

    // Assign the search term to a variable, which can then be used in the "Search" class
    $s = $_GET["q"];

    $search = new Search($s);

    // Check to see if the search term is valid or not
    $search->isValidTerm();

    // Return the search results in JSON format to be used by XHR request
    echo $search->getSearchResultsJSON();
}

