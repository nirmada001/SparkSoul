<?php 
require 'functions/functions.php';
session_start();
ob_start();
// Check whether user is logged on or not
if (!isset($_SESSION['user_id'])) {
    header("location:index.php");
}
// Establish Database Connection
$conn = connect();
?>

<?php
if(isset($_GET['id']) && $_GET['id'] != $_SESSION['user_id']) {
    $current_id = $_GET['id'];
    $flag = 1;
} else {
    $current_id = $_SESSION['user_id'];
    $flag = 0;
}


// Retrieve User Data
$userId = $_SESSION['user_id'];
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $userId");
$userData = mysqli_fetch_assoc($userQuery);

$imageData = base64_encode($userData['u_image']);
$userDate = $userData['user_birthdate'];
$userNick = $userData['user_nickname'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Social Network</title>
    <link rel="stylesheet" type="text/css" href="resources/css/profile.css?v=<?php echo time(); ?>">
   
</head>
<body>
    <div class="">

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

        <div class="right-bar">
            <div class="right-user-info">
                <h2>Details: </h2>
                <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" alt="User Profile Image">
                <h2></h2>
                <p>Name:<p><?php echo $userData['user_firstname'] . ' ' . $userData['user_lastname']; ?></p></p>
                <p>Birthdate:<?php echo $userDate ?> </p>
                <p>Nickname: <?php echo $userNick ?></p>
                
            </div>
    
            <!-- Add other items to the left bar as needed -->
        </div>
        
        <h1>Profile</h1>

        <div class="container">
        <?php
        $postsql;
        if($flag == 0) { // Your Own Profile       
            $postsql = "SELECT posts.post_caption, posts.post_time, users.user_firstname, users.user_lastname,
                                posts.post_public, users.user_id, users.user_gender, users.user_nickname,
                                users.user_birthdate, users.user_hometown, users.user_status, users.user_about, 
                                posts.post_id
                        FROM posts
                        JOIN users
                        ON users.user_id = posts.post_by
                        WHERE posts.post_by = $current_id
                        ORDER BY posts.post_time DESC";
            $profilesql = "SELECT users.user_id, users.user_gender, users.user_hometown, users.user_status, users.user_birthdate,
                                 users.user_firstname, users.user_lastname
                          FROM users
                          WHERE users.user_id = $current_id";
            $profilequery = mysqli_query($conn, $profilesql);
        } else { // Another Profile ---> Retrieve User data and friendship status
            $profilesql = "SELECT users.user_id, users.user_gender, users.user_hometown, users.user_status, users.user_birthdate,
                                    users.user_firstname, users.user_lastname, userfriends.friendship_status
                            FROM users
                            LEFT JOIN (
                                SELECT friendship.user1_id AS user_id, friendship.friendship_status
                                FROM friendship
                                WHERE friendship.user1_id = $current_id AND friendship.user2_id = {$_SESSION['user_id']}
                                UNION
                                SELECT friendship.user2_id AS user_id, friendship.friendship_status
                                FROM friendship
                                WHERE friendship.user1_id = {$_SESSION['user_id']} AND friendship.user2_id = $current_id
                            ) userfriends
                            ON userfriends.user_id = users.user_id
                            WHERE users.user_id = $current_id";
            $profilequery = mysqli_query($conn, $profilesql);
            $row = mysqli_fetch_assoc($profilequery);
            mysqli_data_seek($profilequery,0);
            if(isset($row['friendship_status'])){ // Either a friend or requested as a friend
                if($row['friendship_status'] == 1){ // Friend
                    $postsql = "SELECT posts.post_caption, posts.post_time, users.user_firstname, users.user_lastname,
                                        posts.post_public, users.user_id, users.user_gender, users.user_nickname,
                                        users.user_birthdate, users.user_hometown, users.user_status, users.user_about, 
                                        posts.post_id
                                FROM posts
                                JOIN users
                                ON users.user_id = posts.post_by
                                WHERE posts.post_by = $current_id
                                ORDER BY posts.post_time DESC";
                }
                else if($row['friendship_status'] == 0){ // Requested as a Friend
                    $postsql = "SELECT posts.post_caption, posts.post_time, users.user_firstname, users.user_lastname,
                                        posts.post_public, users.user_id, users.user_gender, users.user_nickname,
                                        users.user_birthdate, users.user_hometown, users.user_status, users.user_about, 
                                        posts.post_id
                                FROM posts
                                JOIN users
                                ON users.user_id = posts.post_by
                                WHERE posts.post_by = $current_id AND posts.post_public = 'Y'
                                ORDER BY posts.post_time DESC";
                }
            } else { // Not a friend
                $postsql = "SELECT posts.post_caption, posts.post_time, users.user_firstname, users.user_lastname,
                                    posts.post_public, users.user_id, users.user_gender, users.user_nickname,
                                    users.user_birthdate, users.user_hometown, users.user_status, users.user_about, 
                                    posts.post_id
                            FROM posts
                            JOIN users
                            ON users.user_id = posts.post_by
                            WHERE posts.post_by = $current_id AND posts.post_public = 'Y'
                            ORDER BY posts.post_time DESC";
            }
        }
        $postquery = mysqli_query($conn, $postsql);    
        if($postquery){
            // Posts
            $width = '40px'; 
            $height = '40px';
            if(mysqli_num_rows($postquery) == 0){ // No Posts
                if($flag == 0){ // Message shown if it's your own profile
                    echo '<div class="post">';
                    echo 'You don\'t have any posts yet';
                    echo '</div>';
                } else { // Message shown if it's another profile other than you.
                    echo '<div class="post">';
                    echo 'There is no public posts to show.';
                    echo '</div>';
                }
                
            } else {
                while($row = mysqli_fetch_assoc($postquery)){
                    include 'includes/post.php';
                }
                
            }  
        }
        ?>
        </div>
        
    </div>
</body>



<script>
function showPath(){
    var path = document.getElementById("selectedFile").value;
    path = path.replace(/^.*\\/, "");
    document.getElementById("path").innerHTML = path;
}
function validateNumber(){
    var number = document.getElementById("phonenum").value;
    var required = document.getElementsByClassName("required");
    if(number == ""){
        required[0].innerHTML = "You must type Your Number.";
        return false;
    } else if(isNaN(number)){
        required[0].innerHTML = "Phone Number must contain digits only."
        return false;
    }
    return true;
}
</script>
</html>
<?php include 'functions/upload.php'; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // A form is posted
    if (isset($_POST['request'])) { // Send a Friend Request
        $sql3 = "INSERT INTO friendship(user1_id, user2_id, friendship_status)
                 VALUES ({$_SESSION['user_id']}, $current_id, 0)";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        }
    } else if(isset($_POST['remove'])) { // Remove
        $sql3 = "DELETE FROM friendship
                 WHERE ((friendship.user1_id = $current_id AND friendship.user2_id = {$_SESSION['user_id']})
                 OR (friendship.user1_id = {$_SESSION['user_id']} AND friendship.user2_id = $current_id))
                 AND friendship.friendship_status = 1";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        }
    } else if(isset($_POST['phone'])) { // Add a Phone Number to Your Profile
        $sql3 = "INSERT INTO user_phone(user_id, user_phone) VALUES ({$_SESSION['user_id']},{$_POST['number']})";
        $query3 = mysqli_query($conn, $sql3);
        if(!$query3){
            echo mysqli_error($conn);
        } 
    }
    sleep(4);
}
?>
