<?php

require_once ('Database.php');
require_once ('UserData.php');

/**       Setting data from database to UserData    */
class UsersDataSet
{
    public function fetchAllUsers()
    {
        $sql = "SELECT * FROM users";
        $result = Database::connect()->query($sql);
        $numRows = $result->num_rows;
        if ($numRows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = new UserData($row);
            }
            return $data;
        }
    }
}