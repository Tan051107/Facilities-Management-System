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

    $selectData = "SELECT * FROM program_information";
    $data = mysqli_query($connection, $selectData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="IntakeTable.css?v=<?php echo time(); ?>">
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
    
    <div class="IntakeTable">

        <div class="searchBar">
        <input type="text" id="searchInput" placeholder="Search Intake..." onkeyup="filterTable()"/>
        </div>
    
        <div class="table-container">
            <div class="table-header">
                <div class="column">Intake</div>
                <div class="column">Name</div>
                <div class="column">Subject Count</div>
                <div class="column">Semester</div>
            </div>
            
            <div class="table-body">
                <?php
                    while ($row = mysqli_fetch_assoc($data)) {

                        $CountSubject = "SELECT COUNT(*) AS SubjectCount FROM student_timetable 
                        WHERE IntakeCode='" . $row['IntakeCode'] . "' AND ModuleCode LIKE '%L-1%'";

                        $count = mysqli_query($connection, $CountSubject);
                        $TotalSubject = mysqli_fetch_assoc($count);

                        echo '<form action="AdminTimetable.php" method="POST" class="table-form">
                                <input type="hidden" name="intake" value="' . $row['IntakeCode'] . '">
                                <button type="submit" class="table-row">
                                        <div class="column" data-label="Intake">' . $row["IntakeCode"] . '</div>
                                        <div class="column" data-label="Name">' . $row["ProgramName"] . '</div>
                                        <div class="column" data-label="Subject Count">' . $TotalSubject['SubjectCount'] . '</div>
                                        <div class="column" data-label="Semester">' . $row["Semester"] . '</div>
                                </button>
                            </form>';
                     }
                ?>

            </div>
        </div>
    </div>  

    <script src="IntakeTable.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>