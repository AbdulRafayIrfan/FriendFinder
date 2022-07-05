<?php
/**         Data of each user on the database       */
class UserData
{
    protected $_id, $_fullName, $_email, $_username, $_password, $_photo, $_latitude, $_longitude, $last_updated;

    public function __construct($dbRow)
    {
        $this->_id = $dbRow['userID'];
        $this->_fullName = $dbRow['full_name'];
        $this->_email = $dbRow['email'];
        $this->_username = $dbRow['username'];
        $this->_password = $dbRow['password'];
        $this->_photo = $dbRow['profile_image'];
        $this->_latitude = $dbRow['latitude'];
        $this->_longitude = $dbRow['longitude'];
        $this->last_updated = $dbRow['last_updated'];
    }

    /**
     * @return id
     */
    public function getUserID()
    {
        return $this->_id;
    }

    /**
     * @return fullName
     */
    public function getFullName()
    {
        return $this->_fullName;
    }

    /**
     * @return email
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @return username
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return password
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return photo
     */
    public function getPhoto()
    {
        return $this->_photo;
    }

    /**
     * @return latitude
     */
    public function getLatitude()
    {
        return $this->_latitude;
    }

    /**
     * @return longitude
     */
    public function getLongitude()
    {
        return $this->_longitude;
    }

    /**
     * @return last_updated
     */
    public function getLastUpdated()
    {
        return $this->last_updated;
    }
}