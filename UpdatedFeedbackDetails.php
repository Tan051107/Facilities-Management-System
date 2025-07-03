<?php
session_start();
include "connection.php";
include "getNotifications.php";

$userType = '';
if (isset($_SESSION['AdminID'])) {
    $userId = $_SESSION['AdminID'];
    $userType = 'Admin';
} elseif (isset($_SESSION['StudentID'])) {
    $userId = $_SESSION['StudentID'];
    $userType = 'Student';
} elseif (isset($_SESSION['LecturerID'])) {
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

$ticketID = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket'])) {
    $_SESSION['ticketID'] = $_POST['ticket'];
    $ticketID = $_SESSION['ticketID'];

    $insertViewSql = "INSERT INTO viewed_feedback (TicketID, UserID, UserType) VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE TicketID = TicketID";
    $insertStmt = $connection->prepare($insertViewSql);
    $insertStmt->bind_param("iss", $ticketID, $userId, $userType);
    $insertStmt->execute();
    $insertStmt->close();

    $sql = "SELECT * FROM feedback WHERE TicketID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $ticketID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Feedback not found.";
        exit;
    } else {
        $feedback = $result->fetch_assoc();
    }
} else {
    echo "No feedback selected.";
    exit;
}

if ($ticketID === null) {
    echo "No Ticket ID found.";
    exit;
}

$getFeedback = "
    SELECT 
        f.TicketID,
        f.FeedbackType,
        f.Description,
        f.UserID,
        f.Progress,
        f.Date_Time,
        COALESCE(s.Name, l.Name) AS Name,
        COALESCE(s.Email, l.Email) AS Email,
        s.StudentID,
        l.LecturerID
    FROM feedback f
    LEFT JOIN Student_Details s ON f.UserID = s.StudentID
    LEFT JOIN Lecturer_Details l ON f.UserID = l.LecturerID
    WHERE f.TicketID = ?
";

$FeedbackStmt = $connection->prepare($getFeedback);
$FeedbackStmt->bind_param("i", $ticketID);
$FeedbackStmt->execute();
$FeedbackResult = $FeedbackStmt->get_result();
$Feedback = $FeedbackResult->fetch_assoc();

if (!$Feedback) {
    echo "No feedback found for this Ticket ID.";
    exit;
}

$FeedbackStmt->close();
$connection->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UniSphere</title>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="UpdatedFeedbackDetails.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="Navbar.css?v=<?php echo time(); ?>">
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
                    <a href="IntakeTable.php">Create Timtable</a>
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
                    <a href="IntakeTable.php">Create Timtable</a>
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

    <div class="feedback-box">
        <h2>User Feedback</h2>

        <div class="row">
            <div class="column">
                <label>
                    <?php 
                        if (!empty($Feedback['StudentID'])) {
                            echo "Student ID";
                        } elseif (!empty($Feedback['LecturerID'])) {
                            echo "Lecturer ID";
                        } else {
                            echo "User ID";
                        }
                    ?>
                </label>
                <div class="input-box">
                    <?php 
                        if (!empty($Feedback['StudentID'])) {
                            echo htmlspecialchars($Feedback['StudentID']);
                        } elseif (!empty($Feedback['LecturerID'])) {
                            echo htmlspecialchars($Feedback['LecturerID']);
                        } else {
                            echo "Unknown User";
                        }
                    ?>
                </div>
            </div>
            <div class="column">
                <label>Email</label>
                <div class="input-box"><?php echo htmlspecialchars($Feedback['Email']); ?></div>
            </div>
        </div>

        <div class="row">
            <div class="column">
                <label>Name</label>
                <div class="input-box"><?php echo htmlspecialchars($Feedback['Name']); ?></div>
            </div>
            <div class="column">
                <label>Feedback Type</label>
                <div class="input-box"><?php echo htmlspecialchars($Feedback['FeedbackType']); ?></div>
            </div>
        </div>

        <div>
            <div class="description-label">Description:</div>
            <div class="description-box"><?php echo htmlspecialchars($Feedback['Description']); ?></div>
        </div>

        <div class="latest-progress">
            <h3>Your ticket is now: <?php echo htmlspecialchars($Feedback['Progress']);?></h3>
        </div>

        <div class="button-group">
            <button onclick="window.location.href='Announcements.php'" class="back-button">← Back</button>
        </div>
    </div>


    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>
