<?php
// Establish Connection to Database
function connect() {
    static $conn;
    if ($conn === NULL){ 
        $conn = mysqli_connect('localhost','root','','socialnetwork');
    }
    return $conn;
}

function getUserName($conn, $userId) {
    $query = "SELECT user_firstname FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['user_firstname'];
    } else {
        return "User not found";
    }
}

?>