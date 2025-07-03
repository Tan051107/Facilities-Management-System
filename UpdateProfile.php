<?php
session_start();
include 'connection.php';

$studentID = $_SESSION['StudentID'];

if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profilePic']['tmp_name'];
    $fileName = $_FILES['profilePic']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = $studentID . '_' . uniqid() . '.' . $fileExtension;
    $uploadDir = 'img/';
    $destPath = $uploadDir . $newFileName;

    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    if (in_array($fileExtension, $allowedExtensions)) {
        if (move_uploaded_file($fileTmpPath, $destPath)) {

            $query = "UPDATE student_details SET ProfilePic = ? WHERE StudentID = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("ss", $newFileName, $studentID);
            $stmt->execute();
            $stmt->close();
            echo "success";
            exit();
        } else {
            echo "Error moving the uploaded file.";
        }
    } else {
        echo "File type not allowed.";
    }
} else {
    echo "No file uploaded or upload error.";
}
?>
