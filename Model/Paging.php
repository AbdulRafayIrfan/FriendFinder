<?php

require_once('Database.php');

/** This class manages the paging of the list of users displayed */
class Paging
{
    // Constant value of results per page
    private static $users_per_page = 10;

    /**
     * @param $page
     * @return array|void
     * The parameter page is used to find the limit values for the SQL statement
     * $limit variable is calculated using the formula to get the appropriate number
     * of pages.
     * For eg: page 2 as param, $limit would be : (2 - 1) * 10 = 10
     * SQL statement would use LIMIT 10,10, this would show results 11-20 for page 2.
     * Page 3 would be LIMIT 20,10. Showing results 20-30 and so on.
     */
    public static function getAllPaged($page)
    {
        $limit = ($page - 1) * Paging::$users_per_page;

        $conn = Database::connect();

        $stmt = $conn->prepare("SELECT * FROM users LIMIT ?,?");

        $stmt->bind_param("ii", $limit, Paging::$users_per_page);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statements failed!";
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


}