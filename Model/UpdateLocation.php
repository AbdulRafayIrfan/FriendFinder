<?php

require_once('Database.php');

/** This class manages the updates of location for users */
class UpdateLocation
{
    /** Lat/Long values which are to be set in the database as per userID */
    /** Alongside the timestamp at which the query occurs */
    private $latitude, $longitude, $uID, $timestamp;

    public function __construct($latitude, $longitude, $uID, $timestamp) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->uID = $uID;
        $this->timestamp = $timestamp;
    }

    /**
     * This function is responsible for updating the location (latlng) as well as
     * updating the timestamp at which the query is executed
     * The timestamp value (secs) is gone through the 'FROM_UNIXTIME' function to
     * convert it to the correct format
     */
    public function updateLocation() {
        $conn = Database::connect();

        $stmt = $conn->prepare("UPDATE users SET latitude = ?, longitude = ?, last_updated = FROM_UNIXTIME(?) WHERE userID = ?");

        $stmt->bind_param("ddii", $this->latitude, $this->longitude, $this->timestamp ,$this->uID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/connect.php");
            exit();
        }
    }

}