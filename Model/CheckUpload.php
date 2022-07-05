<?php

require_once('DbImages.php');


/** Class to validate the uploaded profile image */

class CheckUpload
{
    private $file;

    public function __construct($file) {
        $this->file = $file;
    }

    /**
     * @return bool
     * Uses both the checks to give a final return value depending on the validity
     * of the upload
     */
    public function isUploadValid(): bool {
        // If file has valid extension and is within the size limits
        if ($this->isValidExt() == true && $this->isValidSize() == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool|void
     * Checks if the uploaded image complies with the valid extensions:-
     * jpg, jpeg, png
     * And returns a valid value appropriately
     */
    private function isValidExt() {
        // Allowed file extensions
        $allowed = array('jpg', 'jpeg', 'png');

        $fileName = $this->file['name'];

        $fileError = $this->file['error'];

        $fileExtArray = explode('.', $fileName);

        $fileExt = strtolower(end($fileExtArray));

        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0 ) {
                return true;
            } else {
                $_SESSION['error'] = "Error uploading file!";
                header("Location: ../Controller/profile.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Invalid file type!";
            header("Location: ../Controller/profile.php");
            exit();
        }
    }

    /**
     * @return bool|void
     * Check if the uploaded image is a valid size: within 1 MB
     * And returns a value appropriately
     */
    private function isValidSize() {

        $fileSize = $this->file['size'];

        // Checks if file size less than 1 MB
        if ($fileSize < 999999) {
            return true;
        } else {
            $_SESSION['error'] = "File size too large! Make sure it's under 1 Mb";
            header("Location: ../Controller/profile.php");
            exit();
        }
    }
}