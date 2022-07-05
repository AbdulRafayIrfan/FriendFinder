<?php

require_once('Database.php');

/** This class manages the uploaded image changes to the database */
class DbImages
{

    public function __construct() {
    }

    /**
     * @param $image
     * @param $username
     * The profile image path for the appropriate user is updated for the user record
     */
    public function addProfileImage($image, $username) {

        $conn = Database::connect();

        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE username = ? LIMIT 1");

        $stmt->bind_param("ss", $image, $username);

        if (!$stmt->execute()) {
            $stmt = null;
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/profile.php");
            exit();
        }

        $conn->close();
    }
}