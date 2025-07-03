<?php
    session_start();
    include 'connection.php';
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

    $adminid = $_SESSION['AdminID'];

    $query1 = "SELECT * FROM admin_details WHERE AdminID = ?";
    $getinfo = $connection->prepare($query1);
    $info_details = [];

    if ($getinfo) {
        $getinfo->bind_param("s", $adminid);
        $getinfo->execute();
        $result = $getinfo->get_result();
        while ($row = $result->fetch_assoc()) {

            $info_details[] = [
                "id" => $row["AdminID"],
                "name" => $row["Name"],
                "pic" => $row["ProfilePic"]
            ];
        } 
        $getinfo->close();
    } else {
        echo "Error preparing the query: " . $connection->error;
    }

    $info_json = json_encode($info_details);

    $query2 = "SELECT * FROM feedback WHERE Progress = 'Pending' ORDER BY TicketID DESC LIMIT 3";
    $getfeedback = $connection->prepare($query2);
    $feedback_details = [];

    if ($getfeedback) {
        $getfeedback->execute();
        $result = $getfeedback->get_result();
        while ($row = $result->fetch_assoc()) {

            $feedback_details[] = [
                "id" => $row["TicketID"],
                "type" => $row["FeedbackType"],
                "desc" => $row["Description"],
                "userid" => $row["UserID"],
                "Date" => $row["Date_Time"]
            ];
        } 
        $getfeedback->close();
    } else {
        echo "Error preparing the query: " . $connection->error;
    }

    $feedback_json = json_encode($feedback_details);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="AdminHomePage.css?v=<?php echo time(); ?>">
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

    <section class="Homepage-container">
        <div class="greeting" id="greetingMessage"></div>

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
                    <a href="CreateAcc.php">Account Creation</a>
                    <a href="FeedbackList.php">Feedback Management</a>
                    <a href="IntakeTable.php">Timetable Management</a>
                    <a href="Library.php">Library Management</a>
                </div>
            </div>

            <div class="feedback">
                <div class="feedbacktitle">
                    <h2>Recent Feedback</h2>
                </div>
                <div class="recent-feedback">
                    <div class="feedback-wrapper">
                        <ul id="feedback-list"></ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        const adminData = <?php echo json_encode($info_details); ?>;
        const feedbackData = <?php echo json_encode($feedback_details); ?>;
    </script>
    
    <script src="AdminHomePage.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>