<?php

session_start();

require_once('../Model/CheckUpload.php');
require_once('../Model/DbImages.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['submit'])) {

        // File from the file submit form
        $file = $_FILES['FILE'];

        // To avoid any error, make sure file name is set and not empty
        if (isset($_FILES['FILE']['name']) && !empty($_FILES['FILE']['name'])) {

            $checkUpload = new CheckUpload($file);

            // If file is valid, upload the file and make changes in Database
            if ($checkUpload->isUploadValid() == true) {

                // Each image will be stored with username as the file name (username is set in the session)
                $newFilePath = "../Uploads/" . $_SESSION['user_loggedIn'];

                // Check if file is uploaded and moved to Uploads directory
                if (move_uploaded_file($_FILES['FILE']['tmp_name'], $newFilePath)) {

                    $dbImages = new DbImages();
                    $dbImages->addProfileImage($newFilePath, $_SESSION['user_loggedIn']);

                    // Refresh page
                    header("Location: ../Controller/profile.php");

                } else {
                    $_SESSION['error'] = "Invalid file!";
                    header('Location: profile.php');
                    exit();
                }
            }

        } else {
            $_SESSION['error'] = "Invalid file!";
            header('Location: profile.php?invalidFile');
            exit();
    }

    }
}