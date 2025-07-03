<?php
include 'connection.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

function generateUniqueID($connection) {
    $idExists = true;

    while ($idExists) {
        $randomNumber = rand(10000, 99999);
        $newID = "UMT" . $randomNumber;

        $check_adminID_query = "SELECT * FROM admin_details WHERE AdminID = '$newID'";
        $check_lecturerID_query = "SELECT * FROM lecturer_details WHERE LecturerID = '$newID'";
        $check_studentID_query = "SELECT * FROM student_details WHERE StudentID = '$newID'";

        $check_adminID_result = mysqli_query($connection, $check_adminID_query);
        $check_lecturerID_result = mysqli_query($connection, $check_lecturerID_query);
        $check_studentID_result = mysqli_query($connection, $check_studentID_query);

        if (
            mysqli_num_rows($check_adminID_result) === 0 &&
            mysqli_num_rows($check_lecturerID_result) === 0 &&
            mysqli_num_rows($check_studentID_result) === 0
        ) {
            $idExists = false;
        }
    }

    return $newID;
}

$newID = generateUniqueID($connection);

echo json_encode(['newID' => $newID]);
?>


