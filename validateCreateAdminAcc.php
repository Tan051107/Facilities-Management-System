<?php
    include 'connection.php';
    ob_clean();
    header('Content-Type: text/plain'); 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $isValid = true;
        $name = $_POST['admin-name'];
        $email = $_POST['admin-email'];
        $id = $_POST['admin-id'];
        $password = $_POST['admin-password'];
        $check_admin_email = "SELECT * from admin_details WHERE Email='$email'";
        $admin_details = mysqli_query($connection,$check_admin_email); 

        if (mysqli_num_rows($admin_details)>=1){
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
            $query = "INSERT INTO admin_details (`AdminID`, `Name`, `Email`, `Password`, `ProfilePic`)
                       VALUES ('$id', '$name', '$email', '$hashedPassword', 'profile.png')";

            if (mysqli_query($connection, $query)) {
                echo "success";
                exit;
            } else {
                die('Fail to update database.' . mysqli_error($connection));
            }

            }
        
    }
    mysqli_close($connection);
?>