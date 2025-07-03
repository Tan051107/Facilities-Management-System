    <?php
    include "connection.php";

    error_log("Starting update_timetable.php");
    error_log("POST data: " . json_encode($_POST));

    if (isset($_POST['courseCode']) && isset($_POST['originalDate']) && 
        isset($_POST['newDate']) && isset($_POST['newTime']) && isset($_POST['newRoom']) && isset($_POST['newDay'])
        &&isset($_POST['IntakeCode'])) {
        
        $ModuleCode = $_POST['courseCode'];
        $OriginalDate = trim($_POST['originalDate']); 
        $ChangedDate = trim($_POST['newDate']);
        $newTime = $_POST['newTime'];
        $newRoom = $_POST['newRoom'];
        $newDay = $_POST['newDay'];
        $IntakeCode = $_POST['IntakeCode'];

        $slotID = '';
        switch ($newTime) {
            case '08:30 AM - 10:30 AM':
                $slotID = 'TimeTableSlot1';
                $tableName = 'timetable_slot1';
                $ID = 'Slot1_ID';   
                break;
            case '10:45 AM - 12:45 PM':
                $slotID = 'TimeTableSlot2';
                $tableName = 'timetable_slot2';
                $ID = 'Slot2_ID';
                break;
            case '01:30 PM - 03:30 PM':
                $slotID = 'TimeTableSlot3';
                $tableName = 'timetable_slot3';
                $ID = 'Slot3_ID';
                break;
            case '03:45 PM - 05:45 PM':
                $slotID = 'TimeTableSlot4';
                $tableName = 'timetable_slot4';
                $ID = 'Slot4_ID';
                break;
            default:
                echo json_encode(['success' => false, 'error' => 'Invalid time slot']);
                exit;
        }
            $checkExisting = "SELECT COUNT(*) as count FROM changetimeslot 
                            WHERE ChangeDate = '$ChangedDate' AND $slotID = '$newRoom'";

            $existingResult = mysqli_query($connection, $checkExisting);
            $existingCount = mysqli_fetch_assoc($existingResult)['count'];

            if ($existingCount > 0) {
                echo json_encode(['success' => false, 'error' => 'This room is already booked for the selected date']);
                exit;
            }

            $fetchID = "SELECT $ID AS RoomID FROM $tableName WHERE Day = '$newDay' AND $ID = '$newRoom' AND $ID NOT IN (
                SELECT $slotID FROM student_timetable WHERE $slotID IS NOT NULL)";
            
            $result = mysqli_query($connection, $fetchID);
            if (!$result) {
                echo json_encode(['success' => false, 'error' => 'Database error']);
                exit;
            }

            $SlotID_Fetch = mysqli_fetch_assoc($result);
            if (!$SlotID_Fetch) {
                echo json_encode(['success' => false, 'error' => 'No available slot']);
                exit;
            }

            $query = "INSERT INTO changetimeslot (ModuleCode, OriginDate, ChangeDate, $slotID) 
                    VALUES ('$ModuleCode', '$OriginalDate', '$ChangedDate', '" .$SlotID_Fetch['RoomID']."')";

            $result = mysqli_query($connection, $query);
            
            if ($result) {
                echo json_encode(['success' => true, 'redirect' => 'AdminTimetable.php', 'intake' => $IntakeCode]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Update failed']);
            }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing required data']);
    }

    mysqli_close($connection);
    ?> 