<?php
    include 'connection.php';

    ob_clean();
    header('Content-Type: text/plain'); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $isValid = true;
        $name = $_POST['lecturer-name'];
        $email = $_POST['lecturer-email'];
        $id = $_POST['lecturer-id'];
        $title = $_POST['lecturer-job-title'];
        $dept =  $_POST['lecturer-department'];
        $password = $_POST['lecturer-password'];
        $check_lecturer_email = "SELECT * from  lecturer_details WHERE Email='$email'";
        $lecturer_details = mysqli_query($connection,$check_lecturer_email); 

        if (mysqli_num_rows($lecturer_details)>=1){
            $isValid =false;
            echo "Email has already existed.";
            exit;
        }

        if (!preg_match("/^(?=.*[A-Za-z])[A-Za-z\s]+$/", $name)) {
            $isValid =false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $isValid = false;
        }

        if (!preg_match("/^UMT\d{5}$/", $id)) {
            $isValid =false;
        }

        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])[A-Za-z\d\S]{8,12}$/", $password)) {
            $isValid =false;
        }

        if ($isValid){
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO lecturer_details (`LecturerID`, `Name`, `Email`, `Password`, `ProfilePic`,  `JobTitle`,`Department`)
                       VALUES ('$id', '$name', '$email', '$hashedPassword', 'profile.png','$title','$dept')";

            
            if (mysqli_query($connection, $query)) {
                echo "success";
                exit;
            } else {
                die('Fail to update database.'. mysqli_error($connection));
            }

            }
        
    }
    mysqli_close($connection);
?>