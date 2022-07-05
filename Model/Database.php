<?php
/**     MySQLi Connection  **/
class Database
{
    /**
     * @return mysqli
     */
    public static function connect() {
        $host = 'localhost';
        $username = 'abdul';
        $password = '';
        $database = 'friendfinder_db';

        // creating connection
        $conn = new mysqli($host, $username, $password, $database);
        return $conn;


    }

}