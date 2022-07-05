<?php

require_once('Database.php');
require_once('UserData.php');

/** This class manages the friend requests / add / remove functionality of the website */
class Friend
{

    protected static $conn;

    public function __construct()
    {
        Friend::$conn = Database::connect();
    }

    /**
     * @param $userID
     * @return array|int|void
     * This function takes in the ID of the user and returns a list of all their friends
     * as UserData objects
     * 0 is returned if the no friends are found
     */
    public static function getFriends($userID)
    {

        $stmt = Friend::$conn->prepare("SELECT * from friends WHERE user_id = ? OR friend_id = ?");

        $stmt->bind_param("ii", $userID, $userID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php");
            exit();
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $friendsResult = [];

            while ($row = $result->fetch_assoc()) {

                $get_friend_stmt = Friend::$conn->prepare("SELECT * FROM users WHERE userID = ?");

                if ($row['user_id'] == $userID) {
                    $get_friend_stmt->bind_param("i", $row['friend_id']);

                } else {
                    $get_friend_stmt->bind_param("i", $row['user_id']);
                }

                if (!$get_friend_stmt->execute()) {
                    $_SESSION['error'] = "Statement failed!";
                    header("Location: ../Controller/friend.php");
                    exit();
                }

                $resultAssoc = $get_friend_stmt->get_result()->fetch_assoc();

                $friendRow = new UserData($resultAssoc);

                array_push($friendsResult, $friendRow);
            }

            return $friendsResult;
        } else {

            // If no results found, return 0
            return 0;

        }


    }

    /**
     * @param $userID
     * @return String|void
     * This function takes in the ID of the user and returns a JSON string
     * containing all the required details for each friend and is then
     * used in updating the live map
     */
    public static function getFriendsJSON($userID)
    {

        $stmt = Friend::$conn->prepare("SELECT * from friends WHERE user_id = ? OR friend_id = ?");

        $stmt->bind_param("ii", $userID, $userID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php");
            exit();
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            // Store UserData object of each friend in array
            $friendsResult = [];

            while ($row = $result->fetch_assoc()) {

                $get_friend_stmt = Friend::$conn->prepare("SELECT userID, username, profile_image, latitude, longitude, UNIX_TIMESTAMP(last_updated) AS last_updated FROM users WHERE userID = ?");

                if ($row['user_id'] == $userID) {
                    $get_friend_stmt->bind_param("i", $row['friend_id']);

                } else {
                    $get_friend_stmt->bind_param("i", $row['user_id']);
                }

                if (!$get_friend_stmt->execute()) {
                    $_SESSION['error'] = "Statement failed!";
                    header("Location: ../Controller/friend.php");
                    exit();
                }

                $resultAssoc = $get_friend_stmt->get_result()->fetch_assoc();

                $friendRow = new UserData($resultAssoc);

                array_push($friendsResult, $friendRow);
            }

            foreach ($friendsResult as $friend => $v1) {
                $json_array[] = array(
                    'latitude' => $v1->getLatitude(),
                    'longitude' => $v1->getLongitude(),
                    'profile_image' => $v1->getPhoto(),
                    'userID' => $v1->getUserID(),
                    'last_updated' => $v1->getLastUpdated(),
                    'username' => $v1->getUsername()
                );
            }

            return json_encode($json_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);

        } else {
            return 0;
        }
    }

    /**
     * @param $userID
     * @param $friendID
     * This function is used to accept the friend request by deleting
     * the request record and creating an appropriate record in the friends table
     */
    public static function acceptRequest($userID, $friendID)
    {

        $delete_stmt = Friend::$conn->prepare("DELETE FROM requests WHERE (request_from = ? AND request_to = ?) OR (request_from = ? AND request_to = ?)");

        $delete_stmt->bind_param("iiii", $userID, $friendID, $friendID, $userID);

        if ($delete_stmt->execute()) {

            $stmt = Friend::$conn->prepare("INSERT INTO friends (user_id, friend_id) VALUES (?,?)");

            $stmt->bind_param("ii", $userID, $friendID);

            $stmt->execute();

            // Refresh page
            header("Location: ../Controller/request.php");
            exit();

        } else {

            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php");
            exit();
        }

    }

    /**
     * @param $userID
     * @param $friendID
     * This function is used if the user ignore a friend request from the user.
     * The appropriate record in the requests table is deleted
     */
    public static function ignoreRequest($userID, $friendID)
    {
        $stmt = Friend::$conn->prepare("DELETE FROM requests WHERE (request_from = ? AND request_to = ?) OR (request_from = ? AND request_to = ?)");

        $stmt->bind_param("iiii", $userID, $friendID, $friendID, $userID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/request.php");
            exit();
        }

        // Refresh page
        header("Location: ../Controller/request.php");
        exit();

    }

    /**
     * @param $userID
     * @param $friendID
     * This function creates a request record in the database when a user sends a friend request
     * to the other user
     */
    public static function sendRequest($userID, $friendID)
    {
        $stmt = Friend::$conn->prepare("INSERT INTO requests (request_from, request_to) VALUES (?,?)");

        $stmt->bind_param("ii", $userID, $friendID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/connect.php");
            exit();
        }

        // Refresh page
        header("Location: ../Controller/connect.php");
        exit();

    }

    /**
     * @param $userID
     * @param $friendID
     * @return bool|void
     * This function is used to check if the users given as parameters are friends or not
     * Returns appropriate boolean value
     */
    public static function checkIfFriend($userID, $friendID)
    {
        $stmt = Friend::$conn->prepare("SELECT * FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");

        $stmt->bind_param("iiii", $userID, $friendID, $friendID, $userID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php?stmtFailed");
            exit();
        }

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $userID
     * @param $friendID
     * This function is used to delete the appropriate record in the friends table
     * when a user unfriends another user
     */
    public static function unfriendUser($userID, $friendID)
    {
        $unfriendStmt = Friend::$conn->prepare("DELETE FROM friends WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)");

        $unfriendStmt->bind_param('iiii', $userID, $friendID, $friendID, $userID);

        if (!$unfriendStmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php");
            exit();
        }

        // Refresh page
        header("Location: ../Controller/friend.php");
        exit();
    }

    /**
     * @param $userID
     * @return void
     * This function is used to get the amount of friend requests a user has
     * This value is displayed in the navigation bar above requests link.
     */
    public static function getRequestCount($userID)
    {
        $stmt = Friend::$conn->prepare("SELECT * FROM requests WHERE request_to = ?");

        $stmt->bind_param("i", $userID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php");
            exit();
        }
        $result = $stmt->get_result();

        return $result->num_rows;
    }

    /**
     * @param $userID
     * @return array|void
     * This function gets the details of all the users who have sent a friend request
     * to the specified user.
     * The user details are returned as UserData objects array
     */
    public static function getRequestDetails($userID)
    {

        $stmt = Friend::$conn->prepare("SELECT userID, full_name, email, username, password, profile_image, latitude, longitude, request_to FROM requests JOIN users ON requests.request_from = users.userID WHERE request_to = ?");

        $stmt->bind_param("i", $userID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php");
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
     * @param $userID
     * @param $friendID
     * @return bool|void
     * This function is used to check if a user has already requested another user for a
     * friend request.
     * Appropriate boolean value returned
     */
    public static function alreadyRequested($userID, $friendID)
    {
        $stmt = Friend::$conn->prepare("SELECT * FROM requests WHERE (request_from = ? AND request_to = ?) OR (request_from = ? AND request_to = ?)");

        $stmt->bind_param("iiii", $userID, $friendID, $friendID, $userID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php");
            exit();
        }

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $userID
     * @param $friendID
     * @return bool|void
     * This function is used to check who is the sender of the friend request
     * Function returns true if the logged-in user is the sender and the other user is the receiver
     * and vice-versa
     */
    public static function isRequestSender($userID, $friendID)
    {
        $stmt = Friend::$conn->prepare("SELECT * FROM requests WHERE request_from = ? AND request_to = ?");

        $stmt->bind_param("ii", $userID, $friendID);

        if (!$stmt->execute()) {
            $_SESSION['error'] = "Statement failed!";
            header("Location: ../Controller/friend.php");
            exit();
        }

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return true;
        } else {
            return false;
        }

    }

}