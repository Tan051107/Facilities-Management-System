<?php 
    session_start();
    include "connection.php";
    include "getNotifications.php";

    $userType = '';
    if (isset($_SESSION['LecturerID'])) {
        $userId = $_SESSION['LecturerID'];
        $userType = 'Lecturer';
    } else {
        header("Location: Logout.php");
        exit;
    }

    $profilePage = '';

    if ($userType == 'Admin') {
        $profilePage = 'AdminProfile.php';
    } elseif ($userType == 'Lecturer') {
        $profilePage = 'LecturerProfile.php';
    } elseif ($userType == 'Student') {
        $profilePage = 'StudentProfile.php';
    }

    $profilePic = '';
    if ($userType == 'Admin') {
        $query = "SELECT ProfilePic, Name FROM admin_details WHERE AdminID = ?";
    } elseif ($userType == 'Lecturer') {
        $query = "SELECT ProfilePic, Name FROM lecturer_details WHERE LecturerID = ?";
    } elseif ($userType == 'Student') {
        $query = "SELECT ProfilePic, Name FROM student_details WHERE StudentID = ?";
    }

    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $profilePic = $row['ProfilePic'];
        $userName = $row['Name'];
    } else {
        $profilePic = 'profile.png';
    }

    $LecturerID = $_SESSION['LecturerID'];

    $currentDay = date('l');

    $startOfWeek = date('j F', strtotime('monday this week'));
    $endOfWeek = date('j F', strtotime('friday this week'));

    $currentWeek = "$startOfWeek - $endOfWeek";

    $selectedWeek = $_POST['weekDuration'] ?? $currentWeek;
    $selectedDay = $_POST['selectedDay'] ?? $currentDay;

    $query = "(
        SELECT 
            st.ModuleCode,
            mi.ModuleName,
            ts1.ClassID,
            st.TimeTableSlot1 AS Slot,
            ts1.Day,
            ld.Name AS LecturerName
            FROM student_timetable st
            JOIN timetable_slot1 ts1 ON st.TimetableSlot1 = ts1.slot1_ID
            JOIN lecturer_timetable lt ON st.ModuleCode = lt.ModuleCode
            JOIN lecturer_details ld ON lt.LecturerID = ld.LecturerID
            JOIN module_information mi ON st.ModuleCode = mi.ModuleCode
            WHERE lt.LecturerID = ? AND ts1.Day = ?
    )
    UNION ALL
    (
        SELECT 
            st.ModuleCode,
            mi.ModuleName,
            ts2.ClassID,
            st.TimeTableSlot2 AS Slot,
            ts2.Day,
            ld.Name AS LecturerName
        FROM student_timetable st
        JOIN timetable_slot2 ts2 ON st.TimetableSlot2 = ts2.slot2_ID
        JOIN lecturer_timetable lt ON st.ModuleCode = lt.ModuleCode
        JOIN lecturer_details ld ON lt.LecturerID = ld.LecturerID
        JOIN module_information mi ON st.ModuleCode = mi.ModuleCode
        WHERE lt.LecturerID = ? AND ts2.Day = ?
    )
    UNION ALL
    (
        SELECT 
            st.ModuleCode,
            mi.ModuleName,
            ts3.ClassID,
            st.TimeTableSlot3 AS Slot,
            ts3.Day,
            ld.Name AS LecturerName
        FROM student_timetable st
        JOIN timetable_slot3 ts3 ON st.TimetableSlot3 = ts3.slot3_ID
        JOIN lecturer_timetable lt ON st.ModuleCode = lt.ModuleCode
        JOIN lecturer_details ld ON lt.LecturerID = ld.LecturerID
        JOIN module_information mi ON st.ModuleCode = mi.ModuleCode
        WHERE lt.LecturerID = ? AND ts3.Day = ?
    )
    UNION ALL
    (
        SELECT 
            st.ModuleCode,
            mi.ModuleName,
            ts4.ClassID,
            st.TimeTableSlot4 AS Slot,
            ts4.Day,
            ld.Name AS LecturerName
        FROM student_timetable st
        JOIN timetable_slot4 ts4 ON st.TimetableSlot4 = ts4.slot4_ID
        JOIN lecturer_timetable lt ON st.ModuleCode = lt.ModuleCode
        JOIN lecturer_details ld ON lt.LecturerID = ld.LecturerID
        JOIN module_information mi ON st.ModuleCode = mi.ModuleCode
        WHERE lt.LecturerID = ? AND ts4.Day = ?
    )";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssss', 
        $LecturerID, $selectedDay,
        $LecturerID, $selectedDay,
        $LecturerID, $selectedDay,
        $LecturerID, $selectedDay
    );
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    $startDateStr = trim(explode('-', $selectedWeek)[0]);

    $year = date('Y');
    $startDate = DateTime::createFromFormat('j F Y', $startDateStr . ' ' . $year);

    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
    $dayIndex = array_search($selectedDay, $days);

    if ($startDate && $dayIndex !== false) {
        $targetDate = clone $startDate;
        $targetDate->modify("+$dayIndex day");

        $stringDate = $targetDate->format('Y-m-d'); 
        
    } else {
        alert("Invalid Input");
    }

    $escapedLecturerID = mysqli_real_escape_string($connection, $LecturerID);
    $escapedDay = mysqli_real_escape_string($connection, $selectedDay);
    $escapedDate = mysqli_real_escape_string($connection, $stringDate);

    $cancelquery = "SELECT cts.ModuleCode AS ModuleID, cts.OriginDate AS OriginalDate FROM changetimeslot cts
                    JOIN lecturer_timetable lt ON lt.ModuleCode = cts.ModuleCode
                    WHERE lt.LecturerID = '".$escapedLecturerID."' AND cts.OriginDate = '".$escapedDate."'";

    $cancelfetch = mysqli_query($connection, $cancelquery);

    $canceldetails = [];
    while ($cancellationdetails = mysqli_fetch_assoc($cancelfetch)) {
        $canceldetails[] = $cancellationdetails;
    }

    $replacementquery = "
    SELECT 
        st.ModuleCode, 
        mi.ModuleName, 
        CASE
            WHEN cts.TimeTableSlot1 IS NOT NULL THEN (SELECT ClassID FROM timetable_slot1 WHERE slot1_ID = cts.TimeTableSlot1)
            WHEN cts.TimeTableSlot2 IS NOT NULL THEN (SELECT ClassID FROM timetable_slot2 WHERE slot2_ID = cts.TimeTableSlot2)
            WHEN cts.TimeTableSlot3 IS NOT NULL THEN (SELECT ClassID FROM timetable_slot3 WHERE slot3_ID = cts.TimeTableSlot3)
            WHEN cts.TimeTableSlot4 IS NOT NULL THEN (SELECT ClassID FROM timetable_slot4 WHERE slot4_ID = cts.TimeTableSlot4)
        END as ClassID,
        CASE
            WHEN cts.TimeTableSlot1 IS NOT NULL THEN cts.TimeTableSlot1
            WHEN cts.TimeTableSlot2 IS NOT NULL THEN cts.TimeTableSlot2
            WHEN cts.TimeTableSlot3 IS NOT NULL THEN cts.TimeTableSlot3
            WHEN cts.TimeTableSlot4 IS NOT NULL THEN cts.TimeTableSlot4
        END as Slot,
        ? as Day,
        ld.Name AS LecturerName
        FROM student_timetable st
        JOIN changetimeslot cts ON st.ModuleCode = cts.ModuleCode
        JOIN lecturer_timetable lt ON st.ModuleCode = lt.ModuleCode
        JOIN lecturer_details ld ON lt.LecturerID = ld.LecturerID
        JOIN module_information mi ON st.ModuleCode = mi.ModuleCode
        WHERE lt.LecturerID = ? 
        AND cts.ChangeDate = ?
        AND (
            (cts.TimeTableSlot1 IS NOT NULL AND EXISTS (SELECT 1 FROM timetable_slot1 WHERE slot1_ID = cts.TimeTableSlot1 AND Day = ?))
            OR (cts.TimeTableSlot2 IS NOT NULL AND EXISTS (SELECT 1 FROM timetable_slot2 WHERE slot2_ID = cts.TimeTableSlot2 AND Day = ?))
            OR (cts.TimeTableSlot3 IS NOT NULL AND EXISTS (SELECT 1 FROM timetable_slot3 WHERE slot3_ID = cts.TimeTableSlot3 AND Day = ?))
            OR (cts.TimeTableSlot4 IS NOT NULL AND EXISTS (SELECT 1 FROM timetable_slot4 WHERE slot4_ID = cts.TimeTableSlot4 AND Day = ?))
    )";

    $stmt = mysqli_prepare($connection, $replacementquery);
    mysqli_stmt_bind_param($stmt, 'sssssss', 
        $selectedDay, 
        $escapedLecturerID, 
        $escapedDate,
        $selectedDay,
        $selectedDay,
        $selectedDay,
        $selectedDay
    );
    mysqli_stmt_execute($stmt);
    $fetchreplacement = mysqli_stmt_get_result($stmt);

    $data1 = [];
    while ($replacementdetails = mysqli_fetch_assoc($fetchreplacement)) {
        $data1[] = $replacementdetails;
    }

    $HolidaySelect = "SELECT * FROM holiday_schedule WHERE Date = '". $stringDate ."'";
    $recordfetch = mysqli_query($connection, $HolidaySelect);
    
    $holidayRecord = [];
    while($HolidayInfo = mysqli_fetch_assoc($recordfetch)){
        $holidayRecord[] = $HolidayInfo;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="LecturerTimetable.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="Navbar.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/UniSphere.png" type="image/png">
</head>
<body class="lightmode">

    <div class="background-overlay"></div>

    <header class="header">
        <div class="left-group">
            <div class="Logo hideOnMobile" onclick="showHomepage('<?php echo $userType; ?>')">
                <div class="circle red"></div>
                <div class="circle green"></div>
                <div class="circle blue"></div>
                <div class="umt-text">UMT</div>
            </div>
            <nav class="navbar hideOnMobile">
                <?php if ($userType === 'Admin'): ?>
                    <a href="IntakeTable.php" class="Main-page">Create Timtable</a>
                    <a href="Library.php">Library</a>
                    <a href="FeedbackList.php">Feedback Management</a>
                    <a href="CreateAcc.php">Account Creation</a>
                    <a href="MakeAnnouncement.php">Announcement</a>
                <?php elseif ($userType === 'Student'): ?>
                    <a href="StudentTimetable.php" class="Main-page">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php elseif ($userType ==='Lecturer'): ?>
                    <a href="LecturerTimetable.php" class="Main-page">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php else: ?>
                    <a href="Logout.php">Logout</a>
                <?php endif; ?>
            </nav>
        </div>

        <div class="menu-btn" onclick="showSidebar('.sidebar')">
            <a href="#"><i class='bx bx-menu'></i></a>
        </div>

        <div class="right-group">
            <div class="icon-wrapper notification-wrapper" onclick="goToNotifications()">
                <i class='bx bx-bell'></i>
                <?php if ($notificationCount > 0): ?>
                    <span class="notification-badge">
                        <?php echo ($notificationCount > 99) ? '99+' : $notificationCount; ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="icon-wrapper themeToggle">
                <i id="theme" class='bx bx-sun'></i> 
            </div>
            <a href="Logout.php" class="icon-wrapper">
                <i class='bx bx-arrow-out-right-square-half'></i> 
            </a>
            <a href="<?php echo $profilePage; ?>" class="Profile icon-wrapper">
                <img src="img/<?php echo htmlspecialchars($profilePic); ?>" alt="User Profile">
            </a>
        </div>
    </header>

    <header class="sidebar">
        <div class="close" onclick="hideSidebar('.sidebar')">
            <i class='bx bx-x'></i>
        </div>
        <div class="Logo" onclick="showHomepage('<?php echo $userType; ?>')">
            <div class="circle red"></div>
            <div class="circle green"></div>
            <div class="circle blue"></div>
            <div class="umt-text">UMT</div>
        </div>
        <nav class="navbar">
             <?php if ($userType === 'Admin'): ?>
                    <a href="IntakeTable.php" class="Main-page">Create Timtable</a>
                    <a href="Library.php">Library</a>
                    <a href="FeedbackList.php">Feedback Management</a>
                    <a href="CreateAcc.php">Account Creation</a>
                    <a href="MakeAnnouncement.php">Announcement</a>
                <?php elseif ($userType === 'Student'): ?>
                    <a href="StudentTimetable.php" class="Main-page">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php elseif ($userType ==='Lecturer'): ?>
                    <a href="LecturerTimetable.php" class="Main-page">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php else: ?>
                    <a href="Logout.php">Logout</a>
                <?php endif; ?>
            </nav>
    </header>

    <div class="UserTimetable">
        <form method="POST" id="timetableForm">
            <div class="FilterContainer">
                <div class="intakeCode">
                    <h3><?php echo $LecturerID ?></h3>
                </div>

                <div class="dropdown">
                    <input type="hidden" name="weekDuration" id="selectedWeekInput" value="<?php echo htmlspecialchars($selectedWeek); ?>">
                    <button type="button" onclick="toggleWeekDropdown()" class="dropbtn" id="selectedWeek"><?php echo htmlspecialchars($selectedWeek); ?></button>
                    <div id="Dropdown" class="dropdown-content"></div>
                </div>
            </div>

            <div class="daySelector">
                <input type="hidden" name="selectedDay" id="selectedDayInput" value="<?php echo htmlspecialchars($selectedDay); ?>">
                <?php
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                foreach ($days as $day) {
                    $isSelected = $selectedDay === $day ? ' selected-day' : '';
                    $shortDay = strtoupper(substr($day, 0, 3));
                    echo "<div class='column{$isSelected}' onclick='selectDay(\"$day\")'>{$shortDay}</div>";
                }
                ?>
            </div>
        </form>

        <div class="timetable-container" id="timetable">
            <?php 
            if (!empty($holidayRecord)){ ?>
                <div class="holiday-message" style="text-align: center; padding: 20px; font-size: 24px; color: #fff; border-radius: 10px; margin: 20px;">
                    <i class='bx bx-party' style="font-size: 32px; margin-right: 10px;"></i>
                    Horray, Holiday!!!!! Today is <?php echo htmlspecialchars($holidayRecord[0]['Event']); ?>
                    ♡⸜(ˆᗜˆ˵ )⸝♡
                </div>
            <?php }else{
                foreach ($data as $class){ 
                    $isCancelled = false;
                    foreach ($canceldetails as $cancelinfo){
                        if ($class['ModuleCode'] == $cancelinfo['ModuleID']){
                            $isCancelled = true;
                            break;
                        }    } 
                    
                    
                    if (!$isCancelled){ 
            ?>
                <div class="class-card">
                    <div class="left">
                        <h3><?php echo htmlspecialchars($class['ModuleCode']); ?></h3>
                        <div><?php echo htmlspecialchars($class['ModuleName']); ?></div>
                    </div>
                    <div class="right">
                        <?php 
                            if (str_contains($class['Slot'], "S1")) {
                                $timeDuration = '08:30 A.M - 10:30 A.M';
                            } elseif (str_contains($class['Slot'], "S2")) {
                                $timeDuration = '10:45 A.M - 12:45 P.M';
                            } elseif (str_contains($class['Slot'], "S3")) {
                                $timeDuration = '01:30 P.M - 03:30 P.M';
                            } else {
                                $timeDuration = '03:45 P.M - 05:45 P.M';
                            }
                        ?>
                        <div><i class='bx bx-timer'></i> <?php echo htmlspecialchars($timeDuration); ?></div>
                        <div><i class='bx bx-location-plus'></i> <?php echo htmlspecialchars($class['ClassID']); ?></div>
                        <div><i class='bx bx-user'></i> <?php echo htmlspecialchars($class['LecturerName']); ?></div>
                    </div>
                </div>
            <?php 
                    }
                }
            }

                foreach ($data1 as $replacementInfo){ 
            ?>
                <div class="class-card">
                    <div class="left">
                        <h3><?php echo htmlspecialchars($replacementInfo['ModuleCode']); ?></h3>
                        <div><?php echo htmlspecialchars($replacementInfo['ModuleName']); ?></div>
                        <div><small style="color:rgb(255, 255, 255);">(Rescheduled Class)</small></div>
                    </div>
                    <div class="right">
                        <?php 
                            if (str_contains($replacementInfo['Slot'], "S1")) {
                                $timeDuration = '08:30 A.M - 10:30 A.M';
                            } elseif (str_contains($replacementInfo['Slot'], "S2")) {
                                $timeDuration = '10:45 A.M - 12:45 P.M';
                            } elseif (str_contains($replacementInfo['Slot'], "S3")) {
                                $timeDuration = '01:30 P.M - 03:30 P.M';
                            } else {
                                $timeDuration = '03:45 P.M - 05:45 P.M';
                            }
                        ?>
                        <div><i class='bx bx-timer'></i> <?php echo htmlspecialchars($timeDuration); ?></div>
                        <div><i class='bx bx-location-plus'></i> <?php echo htmlspecialchars($replacementInfo['ClassID']); ?></div>
                        <div><i class='bx bx-user'></i> <?php echo htmlspecialchars($replacementInfo['LecturerName']); ?></div>
                    </div>
                </div>
            <?php }

                if (empty($data) && empty($data1)){ ?>
                    <div class="no-classes">
                        <p>No classes scheduled for this day.</p>
                    </div>
                <?php }?>
        </div>

    <script src="LecturerTimetable.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>
