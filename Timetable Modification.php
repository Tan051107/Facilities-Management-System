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

    $CourseID = isset($_POST['CourseID']) ? $_POST['CourseID'] : "";
    $Name = isset($_POST['Name']) ? $_POST['Name'] : "";
    $Lecturer = isset($_POST['lecturer']) ? $_POST['lecturer'] : "";
    $Date = isset($_POST['Date']) ? $_POST['Date'] : "";
    $Time = isset($_POST['timeSlot']) ? $_POST['timeSlot'] : "";
    $Venue = isset($_POST['Venue']) ? $_POST['Venue'] : "";
    $Day = isset($_POST['Day']) ? $_POST['Day'] : "";
    $IntakeCode = isset($_POST['IntakeCode']) ? $_POST['IntakeCode'] : "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="Timetable Modification.css?v=<?php echo time();?>">
    <link rel="stylesheet" href="Navbar.css?v=<?php echo time();?>">
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
                    <a href="IntakeTable.php" class="Main-page">Create Timtable</a>
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

    <div class="container">
        <h2>Course Session Details</h2>

        <div class="form-container">
            <div class="column left-column">
                <div class="form-row">
                    <label for="courseId">Course ID</label>
                    <input type="text" id="courseId" readonly value="<?php echo $CourseID; ?>">
                </div>
                <div class="form-row">
                    <label for="name">Name</label>
                    <input type="text" id="name" readonly value="<?php echo $Name; ?>">
                </div>
                <div class="form-row">
                    <label for="lecturer">Lecturer</label>
                    <input type="text" id="lecturer" readonly value="<?php echo $Lecturer; ?>">
                </div>
            </div>

            <div class="column right-column">
                <div class="form-row">
                    <label for="date">Date</label>
                    <input type="text" id="date" readonly value="<?php 
                        $dateObj = DateTime::createFromFormat('Y-m-d', $Date);
                        echo $dateObj ? $dateObj->format('j F Y') : $Date;
                    ?>">
                    <input type="hidden" id="originalDate" value="<?php echo $Date; ?>">
                </div>
                <div class="form-row">
                    <label for="time">Time</label>
                    <input type="text" id="time" readonly value="<?php echo $Time; ?>">
                </div>
                <div class="form-row">
                    <label for="venue">Venue</label>
                    <input type="text" id="venue" readonly value="<?php echo $Venue; ?>">
                </div>
                <input type="hidden" id="day" value="<?php echo $Day; ?>">
            </div>
        </div>


        <div class="edit-section">
            <label for="datePicker">Select Date</label>
            <div class="date-wrapper">
                <input type="date" id="datePicker" min="" />
            </div>

            <label for="timeSelect">Select Time</label>
            <select id="timeSelect">
                <option value="">-- Select Time --</option>
                <option>08:30 AM - 10:30 AM</option>
                <option>10:45 AM - 12:45 PM</option>
                <option>01:30 PM - 03:30 PM</option>
                <option>03:45 PM - 05:45 PM</option>
            </select>

            <div id="roomSection">
                <label>Available Room</label>
                <div class="room-options" id="roomOptions"></div>
            </div>

            <button class="update-button">Update Information ✔</button>
        </div>
    </div>

    <script src="Timetable Modification.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>

