<?php
include "connection.php";

if (isset($_POST['date']) && isset($_POST['time'])) {
    $dateInput = $_POST['date'];
    $date = new DateTime($dateInput);
    $dayOfWeek = $date->format('l');
    $mysqlDate = $date->format('Y-m-d');
    
    $SlotInput = $_POST['time'];
    
    if ($SlotInput == '08:30 AM - 10:30 AM') {
        $TimetableSlot = 'timetable_slot1';
        $reserveslot = 'TimeTableSlot1';
        $slotID = 'Slot1_ID';
    } elseif ($SlotInput == '10:45 AM - 12:45 PM') {
        $TimetableSlot = 'timetable_slot2';
        $reserveslot = 'TimeTableSlot2';
        $slotID = 'Slot2_ID';
    } elseif ($SlotInput == '01:30 PM - 03:30 PM') {
        $TimetableSlot = 'timetable_slot3';
        $reserveslot = 'TimeTableSlot3';
        $slotID = 'Slot3_ID';
    } else {
        $TimetableSlot = 'timetable_slot4';
        $reserveslot = 'TimeTableSlot4';
        $slotID = 'Slot4_ID';
    }

    $Roomlist = [];

    $AvailableRooms = "SELECT ". $slotID ." FROM ". $TimetableSlot ." WHERE Day = '". $dayOfWeek."' 
        AND ". $slotID ." NOT IN (
            SELECT ". $reserveslot ." FROM student_timetable 
            WHERE ". $reserveslot ." IS NOT NULL
        )
        AND ". $slotID ." NOT IN (
            SELECT ". $reserveslot ." FROM changetimeslot 
            WHERE ChangeDate = '". $mysqlDate ."'
        )";

    $query = mysqli_query($connection, $AvailableRooms);

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            foreach($row as $value) {
                $Roomlist[] = $value;
            }
        }
        echo json_encode(['success' => true, 'rooms' => $Roomlist]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Could not fetch available rooms']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Date and time are required']);
}
?> 