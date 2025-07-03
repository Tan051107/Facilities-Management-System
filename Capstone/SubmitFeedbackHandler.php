<?php
if ($_SERVER["REQUEST_METHOD"] != "POST" 
    || !isset($_POST["email"]) 
    || !isset($_POST["userID"]) 
    || !isset($_POST["contact"])
    || !isset($_POST["feedbackType"])
    || !isset($_POST["description"])) {
    header("Location: submitFeedback.php");
    exit();
}
include "connection.php";
$email = htmlspecialchars($_POST["email"]);
$userID = htmlspecialchars($_POST["userID"]);
$contact = htmlspecialchars($_POST["contact"]);
$feedbackType = htmlspecialchars($_POST["feedbackType"]);
$description = htmlspecialchars($_POST["description"]);
$currentDateTime = date("Y-m-d H:i:s");

$query = "INSERT INTO feedback (FeedbackType, Description, UserID, Date_Time) 
            VALUES ('$feedbackType', '$description', '$userID', '$currentDateTime')";
if (mysqli_query($connection, $query)) {
    mysqli_close($connection);
    echo "<script>
            alert('Feedback Submitted Successfully');
            window.location.href = 'submitFeedback.php';
        </script>";
    exit();

} else {
    $error = mysqli_error($connection);
    echo "<script>
            alert('Feedback Submission Failed: $error');
            window.location.href = 'submitFeedback.php';
        </script>";
    exit();
}


?>