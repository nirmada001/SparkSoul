<?php
require 'functions/functions.php';
session_start(); // Start the session

// Establish Database Connection
$conn = connect();

// Initialize $userName and $userId with default values
$userName = "";
$userId = null;

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userName = getUserName($conn, $userId);

    // Execute the Python code and capture the output
    $output = exec('python C:\xampp\htdocs\Sparksoul\personality_prediction.py');

    // Insert the data into the personality_type table
    $insertQuery = "INSERT INTO personality_type (user_id, personality_type) VALUES ('$userId', '$output')";
    
    // Execute the SQL query
    mysqli_query($conn, $insertQuery);
} else {
    // Handle the case when the user is not logged in
    // You can redirect them to the login page, for example.
    // header("location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Personality Type</title>
    <link rel="stylesheet" type="text/css" href="resources/css/personality.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="profile-info">
        <h2>Welcome, <?php echo $userName; ?>!</h2>
        <!-- Display the Python script output -->
        <p>Your personality type: </p>
        <div><?php echo $output; ?></div>
    </div>

    <a class="arrow-button" href="index.php">Next</a>
</body>
</html>

