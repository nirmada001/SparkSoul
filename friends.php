<?php 
require 'functions/functions.php';
session_start();
// Check whether user is logged on or not
if (!isset($_SESSION['user_id'])) {
    header("location:index.php");
}
$temp = $_SESSION['user_id'];
session_destroy();
session_start();
$_SESSION['user_id'] = $temp;
ob_start(); 
// Establish Database Connection
$conn = connect();


// Retrieve User Data
$userId = $_SESSION['user_id'];
$userQuery = mysqli_query($conn, "SELECT user_firstname, user_lastname, u_image FROM users WHERE user_id = $userId");
$userData = mysqli_fetch_assoc($userQuery);

$imageData = base64_encode($userData['u_image'])
?>

<!DOCTYPE html>
<html>
<head>
    <title>Social Network</title>
    <link rel="stylesheet" type="text/css" href="resources/css/friends.css?v=<?php echo time(); ?>">
    <style>
    .frame a{
        text-decoration: none;
        color: #4267b2;
    }
    .frame a:hover{
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="usernav">
            <div class="bar">
                <h2 class="logo">
                    sparksoul
                </h2>
            
                <div class="globalsearch">
                    <form method="get" action="search.php" onsubmit="return validateField()"> <!-- Ensure there are no enter escape characters.-->
                        <select name="location">
                            <option value="emails">Emails</option>
                            <option value="names">Names</option>
                            <option value="hometowns">Hometowns</option>
                            <option value="posts">Posts</option>
                        </select><input type="text" placeholder="Search" name="query" id="query"><input type="submit" value="Search" id="querybutton">
                    </form>
                </div>

                <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" alt="User Profile Image">
            </div>
        </div>
        <br /><br />

        <div class="left-bar">

            <div class="user-info">
                <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" alt="User Profile Image">

                <p><?php echo $userData['user_firstname'] . ' ' . $userData['user_lastname']; ?></p>
            </div>

            <?php
                $sql2 = "SELECT COUNT(*) AS count FROM friendship 
                        WHERE friendship.user2_id = {$_SESSION['user_id']} AND friendship.friendship_status = 0";
                $query2 = mysqli_query($conn, $sql2);
                $row = mysqli_fetch_assoc($query2);
            ?>

            <a href="home.php"><i class="fas fa-home"></i> Home</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="friends.php"><i class="fas fa-users"></i> Friends</a>
            <a href="requests.php">Friend Requests (<?php echo $row['count'] ?>)</a>

        </div>

        <h1>Friends</h1>
        <?php
            echo '<center>'; 
            $sql = "SELECT users.user_id, users.user_firstname, users.user_lastname, users.user_gender
                    FROM users
                    JOIN (
                        SELECT friendship.user1_id AS user_id
                        FROM friendship
                        WHERE friendship.user2_id = {$_SESSION['user_id']} AND friendship.friendship_status = 1
                        UNION
                        SELECT friendship.user2_id AS user_id
                        FROM friendship
                        WHERE friendship.user1_id = {$_SESSION['user_id']} AND friendship.friendship_status = 1
                    ) userfriends
                    ON userfriends.user_id = users.user_id";
            $query = mysqli_query($conn, $sql);
            $width = '168px';
            $height = '168px';
            if($query){
                if(mysqli_num_rows($query) == 0){
                    echo '<div class="post">';
                    echo 'You don\'t yet have any friends.';
                    echo '</div>';
                } else {
                    while($row = mysqli_fetch_assoc($query)){
                    echo '<div class="frame">';
                    echo '<center>';
                    include 'includes/profile_picture.php';
                    echo '<br>';
                    echo '<a href="profile.php?id=' . $row['user_id'] . '">' . $row['user_firstname'] . ' ' . $row['user_lastname'] . '</a>';
                    echo '</center>';
                    echo '</div>';
                    }
                }
            }
            echo '</center>';
        ?>
    </div>
</body>
</html>