<?php 
    session_start();
    include "connection.php";
    include "getNotifications.php";

    $userType = '';
    if (isset($_SESSION['AdminID'])) {
        $userId = $_SESSION['AdminID'];
        $userType = 'Admin';
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

    if (isset($_POST['intake'])) {
        $IntakeCode = $_POST['intake'];
    } else {
        echo "No intake code received.";
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

    $selectedWeek = $_POST['weekDuration'] ?? '2 June - 6 June';
    $selectedDay = $_POST['selectedDay'] ?? 'All Days';

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
        WHERE st.IntakeCode = ? 
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
        WHERE st.IntakeCode = ? 
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
        WHERE st.IntakeCode = ? 
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
        WHERE st.IntakeCode = ? 
    )";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 'ssss', 
        $IntakeCode,
        $IntakeCode,
        $IntakeCode,
        $IntakeCode
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

    $endDate = clone $startDate;
    $endDate->modify('next Friday');

    if ($endDate->diff($startDate)->days > 4) {
        $endDate = clone $startDate;
        $endDate->modify('this Friday');
    }
    
    $endDateString = $endDate ? $endDate->format('Y-m-d') : null;

    $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
    
    if ($selectedDay !== 'All Days') {
        $dayIndex = array_search($selectedDay, $days);
        if ($startDate && $dayIndex !== false) {
            $targetDate = clone $startDate;
            $targetDate->modify("+$dayIndex day");
            $stringDate = $targetDate->format('Y-m-d'); 
        } else {
            alert("Invalid Input");
        }
    } else {
        $stringDate = $startDate ? $startDate->format('Y-m-d') : null;
    }
    
    $escapedIntakeCode = mysqli_real_escape_string($connection, $IntakeCode);
    $escapedDay = mysqli_real_escape_string($connection, $selectedDay);
    $escapedStartDate = mysqli_real_escape_string($connection, $stringDate);
    $escapedEndDate = mysqli_real_escape_string($connection, $endDateString);

    $cancelquery = "SELECT cts.ModuleCode AS ModuleID, cts.OriginDate AS OriginalDate FROM changetimeslot cts
                    JOIN student_timetable st ON st.ModuleCode = cts.ModuleCode
                    WHERE st.IntakeCode = '".$escapedIntakeCode."'";

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
        cts.ChangeDate as Day,
        ld.Name AS LecturerName
    FROM student_timetable st
    JOIN changetimeslot cts ON st.ModuleCode = cts.ModuleCode
    JOIN lecturer_timetable lt ON st.ModuleCode = lt.ModuleCode
    JOIN lecturer_details ld ON lt.LecturerID = ld.LecturerID
    JOIN module_information mi ON st.ModuleCode = mi.ModuleCode
    WHERE st.IntakeCode = ? 
    AND (
        (cts.TimeTableSlot1 IS NOT NULL AND EXISTS (SELECT 1 FROM timetable_slot1 WHERE slot1_ID = cts.TimeTableSlot1))
        OR (cts.TimeTableSlot2 IS NOT NULL AND EXISTS (SELECT 1 FROM timetable_slot2 WHERE slot2_ID = cts.TimeTableSlot2))
        OR (cts.TimeTableSlot3 IS NOT NULL AND EXISTS (SELECT 1 FROM timetable_slot3 WHERE slot3_ID = cts.TimeTableSlot3))
        OR (cts.TimeTableSlot4 IS NOT NULL AND EXISTS (SELECT 1 FROM timetable_slot4 WHERE slot4_ID = cts.TimeTableSlot4))
    )";

    $stmt = mysqli_prepare($connection, $replacementquery);
    mysqli_stmt_bind_param($stmt, 's', 
        $escapedIntakeCode,
    );
    mysqli_stmt_execute($stmt);
    $fetchreplacement = mysqli_stmt_get_result($stmt);

    $data1 = [];
    while ($replacementdetails = mysqli_fetch_assoc($fetchreplacement)) {
        $data1[] = $replacementdetails;
    }

    $HolidaySelect = "SELECT * FROM holiday_schedule WHERE Date Between '". $stringDate ."' AND '". $escapedEndDate ."'";
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
    <link rel="stylesheet" href="StuTimetableInfo.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="Navbar.css?v=<?php echo time(); ?>">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

    <div class="StuTimetable">
        <form id="timetableForm" method="POST">
            <input type="hidden" id="IntakeCode" name="intake" value="<?php echo htmlspecialchars($IntakeCode)?>">
            <input type="hidden" id="selectedWeekInput" name="weekDuration" value="<?php echo htmlspecialchars($selectedWeek); ?>">
            <input type="hidden" id="selectedDayInput" name="selectedDay" value="<?php echo htmlspecialchars($selectedDay); ?>">
            
            <div class="dropdownWrapper">
                <div class="dayDropDownBar">
                    <button type="button" onclick="toggleDayDropdown()" class="dropbtn" id="selectedDay"><?php echo htmlspecialchars($selectedDay); ?></button>
                    <div id="DayDropdown" class="dropdown-content"></div>
                </div>

                <div class="dropDownBar">
                    <button type="button" onclick="toggleWeekDropdown()" class="dropbtn" id="selectedWeek"><?php echo htmlspecialchars($selectedWeek); ?></button>
                    <div id="Dropdown" class="dropdown-content"></div>
                </div>
            </div>
        </form>

        <div class="timetable-container" id="timetable">
            <?php if ($selectedDay == 'All Days'): ?>
                <?php foreach ($days as $index => $day): 
                    $dayDate = clone $startDate;
                    $dayDate->modify("+$index day");
                    $currentDate = $dayDate->format('Y-m-d');
                    
                    $isHoliday = false;
                    foreach ($holidayRecord as $holiday) {
                        if ($holiday['Date'] === $currentDate) {
                            $isHoliday = true;
                            break;
                        }
                    }

                    $dayData = array_filter($data, function($item) use ($day) {
                        return $item['Day'] === $day;
                    });

                    $replacementData = array_filter($data1, function($item) use ($currentDate) {
                        return $item['Day'] === $currentDate;
                    });

                    $dayData = array_filter($dayData, function($item) use ($canceldetails, $currentDate) {
                        foreach ($canceldetails as $cancel) {
                            if ($cancel['ModuleID'] === $item['ModuleCode'] && $cancel['OriginalDate'] === $currentDate) {
                                return false;
                            }
                        }
                        return true;
                    });

                    if (!$isHoliday && (!empty($dayData) || !empty($replacementData))): ?>
                        <div class="day-group">
                            <h2 class="day-header"><?php echo $day; ?>, <?php echo $dayDate->format('j F Y'); ?></h2>
                        </div>
                        <?php 

                        foreach ($dayData as $class): ?>
                            <div class="class-card">
                                <div class="class-info">
                                    <h3><?php echo htmlspecialchars($class['ModuleCode']); ?></h3>
                                    <p class="course-name"><?php echo htmlspecialchars($class['ModuleName']); ?></p>
                                    <div class="SessionInfo">
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
                                        <div class="info-item">
                                            <p><i class='bx bx-timer'></i> <?php echo htmlspecialchars($timeDuration); ?></p>
                                        </div>
                                        <div class="info-item">
                                            <p><i class='bx bx-location-plus'></i> <?php echo htmlspecialchars($class['ClassID']); ?></p>
                                        </div>
                                        <div class="info-item">
                                            <p><i class='bx bx-user'></i> <?php echo htmlspecialchars($class['LecturerName']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="class-actions">
                                    <form action="Timetable Modification.php" method="POST">
                                        <input type="hidden" name="CourseID" value="<?php echo htmlspecialchars($class['ModuleCode']); ?>">
                                        <input type="hidden" name="Name" value="<?php echo htmlspecialchars($class['ModuleName']); ?>">
                                        <input type="hidden" name="timeSlot" value="<?php echo htmlspecialchars($class['Slot']); ?>">
                                        <input type="hidden" name="Venue" value="<?php echo htmlspecialchars($class['ClassID']); ?>">
                                        <input type="hidden" name="lecturer" value="<?php echo htmlspecialchars($class['LecturerName']); ?>">
                                        <input type="hidden" name="Day" value="<?php echo htmlspecialchars($class['Day']); ?>">
                                        <input type="hidden" name="Date" value="<?php echo htmlspecialchars($dayDate->format('Y-m-d')); ?>">
                                        <input type="hidden" name="IntakeCode" value="<?php echo htmlspecialchars($IntakeCode); ?>">
                                        <button type="submit">
                                            <p>Edit <i class='bx bx-pencil'></i></p>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach;

                        foreach ($replacementData as $class): ?>
                            <div class="class-card replacement">
                                <div class="class-info">
                                    <h3><?php echo htmlspecialchars($class['ModuleCode']); ?> (Rescheduled)</h3>
                                    <p class="course-name"><?php echo htmlspecialchars($class['ModuleName']); ?></p>
                                    <div class="SessionInfo">
                                        <div class="info-item">
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
                                            <p><i class='bx bx-timer'></i> <?php echo htmlspecialchars($timeDuration); ?></p>
                                        </div>
                                        <div class="info-item">
                                            <p><i class='bx bx-location-plus'></i> <?php echo htmlspecialchars($class['ClassID']); ?></p>
                                        </div>
                                        <div class="info-item">
                                            <p><i class='bx bx-user'></i> <?php echo htmlspecialchars($class['LecturerName']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                    endif;
                endforeach;
            else:
                $dayDate = clone $startDate;
                $dayIndex = array_search($selectedDay, $days);
                $dayDate->modify("+$dayIndex day");
                $currentDate = $dayDate->format('Y-m-d');

                $isHoliday = false;
                foreach ($holidayRecord as $holiday) {
                    if ($holiday['Date'] === $currentDate) {
                        $isHoliday = true;
                        break;
                    }
                }

                $dayData = array_filter($data, function($item) use ($selectedDay) {
                    return $item['Day'] === $selectedDay;
                });

                $replacementData = array_filter($data1, function($item) use ($currentDate) {
                    return $item['Day'] === $currentDate;
                });

                $dayData = array_filter($dayData, function($item) use ($canceldetails, $currentDate) {
                    foreach ($canceldetails as $cancel) {
                        if ($cancel['ModuleID'] === $item['ModuleCode'] && $cancel['OriginalDate'] === $currentDate) {
                            return false;
                        }
                    }
                    return true;
                });

                if (!$isHoliday && (!empty($dayData) || !empty($replacementData))): ?>
                    <div class="day-group">
                        <h2 class="day-header"><?php echo $selectedDay; ?>, <?php echo $dayDate->format('j F Y'); ?></h2>
                    </div>
                    <?php 

                    foreach ($dayData as $class): ?>
                        <div class="class-card">
                            <div class="class-info">
                                <h3><?php echo htmlspecialchars($class['ModuleCode']); ?></h3>
                                <p class="course-name"><?php echo htmlspecialchars($class['ModuleName']); ?></p>
                                <div class="SessionInfo">
                                    <div class="info-item">
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
                                        <p><i class='bx bx-timer'></i> <?php echo htmlspecialchars($timeDuration); ?></p>
                                    </div>
                                    <div class="info-item">
                                        <p><i class='bx bx-location-plus'></i> <?php echo htmlspecialchars($class['ClassID']); ?></p>
                                    </div>
                                    <div class="info-item">
                                        <p><i class='bx bx-user'></i> <?php echo htmlspecialchars($class['LecturerName']); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="class-actions">
                                <form action="Timetable Modification.php" method="POST">
                                        <input type="hidden" name="CourseID" value="<?php echo htmlspecialchars($class['ModuleCode']); ?>">
                                        <input type="hidden" name="Name" value="<?php echo htmlspecialchars($class['ModuleName']); ?>">
                                        <input type="hidden" name="timeSlot" value="<?php echo htmlspecialchars($class['Slot']); ?>">
                                        <input type="hidden" name="Venue" value="<?php echo htmlspecialchars($class['ClassID']); ?>">
                                        <input type="hidden" name="lecturer" value="<?php echo htmlspecialchars($class['LecturerName']); ?>">
                                        <input type="hidden" name="Day" value="<?php echo htmlspecialchars($class['Day']); ?>">
                                        <input type="hidden" name="Date" value="<?php echo htmlspecialchars($dayDate->format('Y-m-d')); ?>">
                                        <input type="hidden" name="IntakeCode" value="<?php echo htmlspecialchars($IntakeCode); ?>">
                                    <button type="submit">
                                        <p>Edit <i class='bx bx-pencil'></i></p>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach;

                    foreach ($replacementData as $class): ?>
                        <div class="class-card replacement">
                            <div class="class-info">
                                <h3><?php echo htmlspecialchars($class['ModuleCode']); ?> (Rescheduled)</h3>
                                <p class="course-name"><?php echo htmlspecialchars($class['ModuleName']); ?></p>
                                <div class="SessionInfo">
                                    <div class="info-item">
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
                                        <p><i class='bx bx-timer'></i> <?php echo htmlspecialchars($timeDuration); ?></p>
                                    </div>
                                    <div class="info-item">
                                        <p><i class='bx bx-location-plus'></i> <?php echo htmlspecialchars($class['ClassID']); ?></p>
                                    </div>
                                    <div class="info-item">
                                        <p><i class='bx bx-user'></i> <?php echo htmlspecialchars($class['LecturerName']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;
                endif;
            endif; ?>
        </div>
    </div>
    <script src="AdminTimetable.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>