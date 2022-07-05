<?php

require_once('Database.php');
require_once('../Model/UserData.php');

/** This class manages the searching of users */
class Search
{
    /** This is the search term which was entered in the search box */
    private $searchTerm;

    public function __construct($searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    /**
     * Check if the search term is valid by:
     * Checking if the search term contains only space or
     * If the term contains invalid values
     * The user is redirected appropriately in the case of invalid search term
     */
    public function isValidTerm()
    {
        if (ctype_space($this->searchTerm)) {
            $_SESSION['error'] = "Search term contains only spaces!";
            header('Location: ../Controller/connect.php');
            exit();
        }
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $this->searchTerm)) {
            $_SESSION['error'] = "Invalid search! Please use only letters or numbers";
            header('Location: ../Controller/connect.php');
            exit();
        }
    }

    /**
     * @return array|void
     * This function searches for the given search term in the database
     * provided the term is valid
     * It returns the array of results as UserData objects if any found
     */
    public function getSearchResults()
    {
        $conn = Database::connect();

        // Preparing the search term to be used in the database
        $this->searchTerm = "%$this->searchTerm%";

        $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR full_name LIKE ?");

        $stmt->bind_param("ss", $this->searchTerm, $this->searchTerm);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/connect.php");
            exit();
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = new UserData($row);
            }
            return $data;
        }
    }

    /**
     * Comments here!
     */
    public function getSearchResultsJSON() {

        $conn = Database::connect();

        // Preparing the search term to be used in the database
        $this->searchTerm = "%$this->searchTerm%";

        $stmt = $conn->prepare("SELECT * FROM users WHERE username LIKE ? OR full_name LIKE ? LIMIT 15");

        $stmt->bind_param("ss", $this->searchTerm, $this->searchTerm);

        if (!$stmt->execute()) {
            return $stmt->error;
        }

        $result = $stmt->get_result();

        // Get only three required data fields for AJAX live search
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $json_array[] = array (
                  'full_name' => $row['full_name'],
                  'username' => $row['username'],
                  'profile_image' => $row['profile_image'],
                  'userID' => $row['userID']
                );
            }
            return json_encode($json_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);
        }
    }

}