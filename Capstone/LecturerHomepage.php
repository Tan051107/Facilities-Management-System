<?php
    session_start();
    include 'connection.php';
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

    $lecturerid = $_SESSION['LecturerID'];

    $query1 = "SELECT * FROM lecturer_details WHERE LecturerID = ?";
    $getinfo = $connection->prepare($query1);
    $info_details = [];

    if ($getinfo) {
        $getinfo->bind_param("s", $lecturerid);
        $getinfo->execute();
        $result = $getinfo->get_result();
        while ($row = $result->fetch_assoc()) {

            $info_details[] = [
                "id" => $row["LecturerID"],
                "name" => $row["Name"],
                "pic" => $row["ProfilePic"],
            ];
        } 
        $getinfo->close();
    } else {
        echo "Error preparing the query: " . $connection->error;
    }

    $info_json = json_encode($info_details);

    $query2 = "(
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
            WHERE lt.LecturerID = ?
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
        WHERE lt.LecturerID = ?
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
        WHERE lt.LecturerID = ?
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
        WHERE lt.LecturerID = ?
    )";

    $stmt = mysqli_prepare($connection, $query2);
    mysqli_stmt_bind_param($stmt, 'ssss', 
        $lecturerid,
        $lecturerid,
        $lecturerid,
        $lecturerid
    );
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    $HolidaySelect = "SELECT * FROM holiday_schedule";
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
    <link rel="stylesheet" href="LecturerHomePage.css?v=<?php echo time(); ?>">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Navbar.css?v=<?php echo time(); ?>">
    <link rel="icon" href="img/UniSphere.png" type="image/png">
</head>
<body class="lightmode homepage">
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
                    <a href="IntakeTable.php">Create Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="FeedbackList.php">Feedback Management</a>
                    <a href="CreateAcc.php">Account Creation</a>
                    <a href="MakeAnnouncement.php">Announcement</a>
                <?php elseif ($userType === 'Student'): ?>
                    <a href="StudentTimetable.php">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php elseif ($userType ==='Lecturer'): ?>
                    <a href="LecturerTimetable.php">Timetable</a>
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
                    <a href="IntakeTable.php">Create Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="FeedbackList.php">Feedback Management</a>
                    <a href="CreateAcc.php">Account Creation</a>
                    <a href="MakeAnnouncement.php">Announcement</a>
                <?php elseif ($userType === 'Student'): ?>
                    <a href="StudentTimetable.php">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php elseif ($userType ==='Lecturer'): ?>
                    <a href="LecturerTimetable.php">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php else: ?>
                    <a href="Logout.php">Logout</a>
                <?php endif; ?>
            </nav>
    </header>
    
    <div class="Homepage-container">
        <div class="greeting" id="greetingMessage">Welcome!</div>

        <div class="container">
            <div class="wrapper">
                <div class="wrapper-holder">
                    <div id="slider-img-1"></div>
                    <div id="slider-img-2"></div>
                    <div id="slider-img-3"></div>
                </div>
            </div>
            <div class="button-holder">
                <span class="button" data-slide="0"></span>
                <span class="button" data-slide="1"></span>
                <span class="button" data-slide="2"></span>
            </div>
        </div>

        <div class="function">
            <div class="quickaccess">
                <div class="quicktitle">
                    <h2>Quick Access</h2>
                </div>

                <div class="quickbut">
                    <a href="ChooseBookingType.php">Book Facility</a>
                    <a href="submitFeedback.php">Feedback</a>
                    <a href="LecturerTimetable.php">View Timetable</a>
                    <a href="Library.php">Library</a>
                </div>
            </div>

            <div class="schedule">
                <div class="scheduletitle">
                    <h2>Today's Schedule</h2>
                </div>
                <div class="today-schedule">
                    <div class="schedule-wrapper">
                        <ul id="schedule-list"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const lecturerData = <?php echo json_encode($info_details); ?>;
        const scheduleData = <?php echo json_encode($data); ?>;
        const holidayData = <?php echo json_encode($holidayRecord); ?>;
    </script>
    <script src="LecturerHomePage.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>