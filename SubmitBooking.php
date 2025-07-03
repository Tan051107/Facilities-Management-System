<?php
    session_start();
    include "connection.php";

    if ($_SERVER["REQUEST_METHOD"] != "POST" 
        || !isset($_POST["court"]) 
        || !isset($_POST["timeslot"]) 
        || !isset($_POST["date"])
        || !isset($_POST["day"])) {
        header("Location: ChooseBookingType.php");
        exit();
    }

$courtID = htmlspecialchars($_POST["court"]);
$timeslot = htmlspecialchars($_POST["timeslot"]);
$date = htmlspecialchars($_POST["date"]);
$day = htmlspecialchars($_POST["day"]);

$tableMap = [
    1 => ['table' => 'booking_slot1', 'slot_column' => 'Slot1_ID', 'book_col' => 'BookTableSlot1'],
    2 => ['table' => 'booking_slot2', 'slot_column' => 'Slot2_ID', 'book_col' => 'BookTableSlot2'],
    3 => ['table' => 'booking_slot3', 'slot_column' => 'Slot3_ID', 'book_col' => 'BookTableSlot3'],
    4 => ['table' => 'booking_slot4', 'slot_column' => 'Slot4_ID', 'book_col' => 'BookTableSlot4'],
];

$table = $tableMap[$timeslot]['table'];
$slotColumn = $tableMap[$timeslot]['slot_column'];
$bookCol = $tableMap[$timeslot]['book_col'];

$sqlSelect = "SELECT $slotColumn AS SlotID FROM $table WHERE FacilityID = ? AND Day = ?";

$stmtSelect = $connection->prepare($sqlSelect);
$stmtSelect->bind_param("ss", $courtID, $day);
$stmtSelect->execute();
$result = $stmtSelect->get_result();

if ($row = $result->fetch_assoc()) {
    $slotID = $row['SlotID'];

    $bookTableSlot1 = null;
    $bookTableSlot2 = null;
    $bookTableSlot3 = null;
    $bookTableSlot4 = null;

    switch ($timeslot) {
        case 1:
            $bookTableSlot1 = $slotID;
            break;
        case 2:
            $bookTableSlot2 = $slotID;
            break;
        case 3:
            $bookTableSlot3 = $slotID;
            break;
        case 4:
            $bookTableSlot4 = $slotID;
            break;
    }
    $sqlInsert = "INSERT INTO bookingtable (BookTableSlot1, BookTableSlot2, BookTableSlot3, BookTableSlot4, UserID, booked_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $connection->prepare($sqlInsert);
    $stmtInsert->bind_param(
        "ssssss",
        $bookTableSlot1,
        $bookTableSlot2,
        $bookTableSlot3,
        $bookTableSlot4,
        $userID,
        $date
    );

    $stmtInsert->execute();
    if ($stmtInsert->affected_rows > 0) {
        echo "<script>
                alert('Booking Successful!');
                window.location.href = 'choosebookingtype.php';
            </script>";
        exit();
    } else {
        echo "<script>
                alert('Booking failed. Please try again.1');
                window.location.href = 'choosebookingtype.php';
            </script>";
        exit();
    }
} else {

    echo "<script>
            alert('Booking failed. Please try again.2');
            window.location.href = 'choosebookingtype.php';
        </script>";
    exit();
    

}

?>