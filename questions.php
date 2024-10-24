<?php 
require 'functions/functions.php';
session_start(); // Start the session

// Establish Database Connection
$conn = connect();

// Initialize $userName with a default value
$userName = "";

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userName = getUserName($conn, $userId);
} else {
    // Handle the case when the user is not logged in
    // You can redirect them to the login page, for example.
    // header("location: login.php");
}


// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve the user ID and form responses
    $user_id = $_POST['user_id'];
    $responses = [];
    for ($i = 1; $i <= 5; $i++) {
        $responses["O$i"] = $_POST["O$i"];
        $responses["C$i"] = $_POST["C$i"];
        $responses["E$i"] = $_POST["E$i"];
        $responses["A$i"] = $_POST["A$i"];
        $responses["N$i"] = $_POST["N$i"];
    }

    // Establish a database connection here

    // Insert the user's responses into the database
    $query = "INSERT INTO personality_test (user_id, O1, O2, O3, O4, O5, C1, C2, C3, C4, C5, E1, E2, E3, E4, E5, A1, A2, A3, A4, A5, N1, N2, N3, N4, N5) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare($query);
    $bindParams = array_merge([$user_id], array_values($responses));
    $stmt->bind_param(str_repeat("i", count($bindParams)), ...$bindParams);

    if ($stmt->execute()) {
        // Data successfully inserted
        header("Location: result.php");
        exit(); // Stop script execution after the redirection
    } else {
        // Handle database insertion error
        echo "<p style='color: red;'>Error: Data could not be recorded. Please try again.</p>";
        // You can display an error message or log the error
    }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Personality Test</title>
  <link rel="stylesheet" type="text/css" href="resources/css/questions.css?v=<?php echo time(); ?>">
</head>
<body>
  <h1>Personality Test</h1>
  <p class="p1">Please rate each of the following questions on a scale of 1 to 5, with 1 being strongly disagree and 5 being strongly agree.</p>

<form action="" method="post">

    <input type="hidden" name="user_id" value="1">
    <p class="p1">Welcome, <?php echo $userName; ?>!, answer the following questions to identify your personality.</p>
            <!--Openness to Experience-->

    <h2>Openness to Experience</h2>

    <p>I am curious about new things.</p>
    <select name="O1">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <p>I am imaginative and creative.</p>
    <select name="O2">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <p>I am interested in learning new things.</p>
    <select name="O3">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <p>I am open to new experiences.</p>
    <select name="O4">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <p>I am appreciative of art and beauty.</p>
    <select name="O5">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <!--Conscientiousness-->

    <h2>Conscientiousness</h2>

    <p>I am organized and efficient.</p>
    <select name="C1">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <p>I am able to delay gratification.</p>
    <select name="C2">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <p>I am persistent in completing tasks.</p>
    <select name="C3">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <p>I am careful and thorough in my work.</p>
    <select name="C4">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

    <p>I am able to set and achieve goals.</p>
    <select name="C5">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
    </select>

        <!--Extraversion-->

        <h2>Extraversion</h2>

        <p>I enjoy spending time with other people.</p>
        <select name="E1">
            <option value="1">Strongly disagree</option>
            <option value="2">Disagree</option>
            <option value="3">Neither agree nor disagree</option>
            <option value="4">Agree</option>
            <option value="5">Strongly agree</option>
        </select>
    
        <p>I am outgoing and sociable.</p>
        <select name="E2">
            <option value="1">Strongly disagree</option>
            <option value="2">Disagree</option>
            <option value="3">Neither agree nor disagree</option>
            <option value="4">Agree</option>
            <option value="5">Strongly agree</option>
        </select>
    
        <p>I am assertive and self-confident.</p>
        <select name="E3">
            <option value="1">Strongly disagree</option>
            <option value="2">Disagree</option>
            <option value="3">Neither agree nor disagree</option>
            <option value="4">Agree</option>
            <option value="5">Strongly agree</option>
        </select>
    
        <p>I am talkative and expressive.</p>
        <select name="E4">
            <option value="1">Strongly disagree</option>
            <option value="2">Disagree</option>
            <option value="3">Neither agree nor disagree</option>
            <option value="4">Agree</option>
            <option value="5">Strongly agree</option>
        </select>
    
        <p>I am energized by social interaction.</p>
        <select name="E5">
            <option value="1">Strongly disagree</option>
            <option value="2">Disagree</option>
            <option value="3">Neither agree nor disagree</option>
            <option value="4">Agree</option>
            <option value="5">Strongly agree</option>
        </select>

        <!--Agreeableness-->

        <h2>Agreeableness</h2>

        <p>I am generally helpful and cooperative.</p>
        <select name="A1">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <p>I am considerate of others' feelings.</p>
        <select name="A2">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <p>I am forgiving when others make mistakes.</p>
        <select name="A3">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <p>I am willing to compromise to avoid conflict.</p>
        <select name="A4">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <p>I like to get to know people before I trust them.</p>
        <select name="A5">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <!--Neuroticism-->

        <h2>Neuroticism</h2>

        <p>I am easily worried or anxious.</p>
        <select name="N1">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <p>I experience mood swings.</p>
        <select name="N2">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <p>I am sensitive to criticism.</p>
        <select name="N3">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <p>I am likely to feel depressed or down.</p>
        <select name="N4">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

        <p>I have difficulty controlling my emotions.</p>
        <select name="N5">
        <option value="1">Strongly disagree</option>
        <option value="2">Disagree</option>
        <option value="3">Neither agree nor disagree</option>
        <option value="4">Agree</option>
        <option value="5">Strongly agree</option>
        </select>

    <br/>
        <button type="submit" name="submit">Submit</button>
</form>

<!-- <?php
  // Check if the form is submitted
  if (isset($_POST['submit'])) {
    // Retrieve the user ID and form responses
    $user_id = $_POST['user_id'];
    $responses = [];
    for ($i = 1; $i <= 5; $i++) {
      $responses["O$i"] = $_POST["O$i"];
      $responses["C$i"] = $_POST["C$i"];
      $responses["E$i"] = $_POST["E$i"];
      $responses["A$i"] = $_POST["A$i"];
      $responses["N$i"] = $_POST["N$i"];
    }

    // Establish a database connection here

    // Insert the user's responses into the database
    $query = "INSERT INTO personality_test (user_id, O1, O2, O3, O4, O5, C1, C2, C3, C4, C5, E1, E2, E3, E4, E5, A1, A2, A3, A4, A5, N1, N2, N3, N4, N5) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare($query);
    $bindParams = array_merge([$user_id], array_values($responses));
    $stmt->bind_param(str_repeat("i", count($bindParams)), ...$bindParams);

    if ($stmt->execute()) {
        // Data successfully inserted
        // Redirect to the result page
        header("Location: result_page.php");
        exit(); // Ensure that no more output is sent to the current page
    } else {
        // Handle database insertion error
        echo "<p style='color: red;'>Error: Data could not be recorded. Please try again.</p>";
        // You can display an error message or log the error
    }
  }
?> -->





</body>
</html>
