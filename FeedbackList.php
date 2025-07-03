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

    if (isset($_SESSION['status_updated'])) {
        echo "<script>alert('" . $_SESSION['status_updated'] . "');</script>";
        unset($_SESSION['status_updated']); 
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket'])) {
        $_SESSION['ticketID'] = $_POST['ticket'];
        header("Location: ManageFeedback.php");
        exit;
    }

    $getAllFeedback = "SELECT TicketID, FeedbackType, Description, UserID, Progress, Date_Time FROM feedback ORDER BY Date_Time ASC";
    $FeedbackResult = mysqli_query($connection, $getAllFeedback);
    $FeedbackList = mysqli_fetch_all($FeedbackResult, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="FeedbackList.css?v=<?php echo time(); ?>">
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
                    <a href="FeedbackList.php" class="Main-page">Feedback Management</a>
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
                    <a href="FeedbackList.php" class="Main-page">Feedback Management</a>
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

    <div class="FeedbackContainer">
        <div class="ListHeader">
            <div class="column">User ID</div>
            <div class="column">Type</div>
            <div class="column">Description</div>
            <div class="column">Submission Date</div>
            <div class="column">Status</div>
        </div>
        <div class="FeedbackList">
            <?php if (empty($FeedbackList)): ?>
                <p>No Feedback Available</p>
            <?php else: ?>
                <?php foreach ($FeedbackList as $Feedback): ?>
                <form action="" method="POST" class="feedback-form">
                    <input type="hidden" name="ticket" value="<?php echo htmlspecialchars($Feedback['TicketID']); ?>">
                    <button type="submit" class="ListContent">
                        <div class="column ID"><?php echo $Feedback['UserID']; ?></div>
                        <div class="column Type"><?php echo $Feedback['FeedbackType']; ?></div>
                        <?php
                            $desc = $Feedback['Description'];
                            $shortDesc = mb_strimwidth($desc, 0, 20, "...");
                        ?>
                        <div class="column Des"><?php echo htmlspecialchars($shortDesc); ?></div>
                        <div class="column Date"><?php echo $Feedback['Date_Time']; ?></div>
                        <?php
                            $progress = $Feedback['Progress'];
                            $statusClass = '';
                            if ($progress === 'Pending') {
                                $statusClass = 'status-pending';
                            } elseif ($progress === 'Processing') {
                                $statusClass = 'status-processing';
                            } elseif ($progress === 'Finished') {
                                $statusClass = 'status-finished';
                            }
                        ?>
                        <div class="column Status <?php echo $statusClass; ?>"><?php echo $progress; ?></div>
                    </button>
                </form>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="MobileListHeader">
            <div class="title">User Feedback List</div>
        </div>
        <div class="MobileFeedbackList">
            <?php if (empty($FeedbackList)): ?>
                <p>No Feedback Available</p>
            <?php else: ?>
                <?php foreach ($FeedbackList as $Feedback): ?>
                <form action="" method="POST" class="feedback-form">
                    <input type="hidden" name="ticket" value="<?php echo htmlspecialchars($Feedback['TicketID']); ?>">
                    <button type="submit" class="ListContent">
                        <div class="column ID"><span class="label">User ID: </span><?php echo $Feedback['UserID']; ?></div>
                        <div class="column Type"><span class="label">Feedback Type: </span><?php echo $Feedback['FeedbackType']; ?></div>
                        <?php
                            $desc = $Feedback['Description'];
                            $shortDesc = mb_strimwidth($desc, 0, 20, "...");
                        ?>
                        <div class="column Des"><span class="label">Description: </span><?php echo htmlspecialchars($shortDesc); ?></div>
                        <div class="column Date"><span class="label">Date: </span><?php echo $Feedback['Date_Time']; ?></div>
                        <?php
                            $progress = $Feedback['Progress'];
                            $statusClass = '';
                            if ($progress === 'Pending') {
                                $statusClass = 'status-pending';
                            } elseif ($progress === 'Processing') {
                                $statusClass = 'status-processing';
                            } elseif ($progress === 'Finished') {
                                $statusClass = 'status-finished';
                            }
                        ?>
                        <div class="column Status">
                            <span class="label">Status: </span>
                            <span class="<?php echo $statusClass; ?>"><?php echo $Feedback['Progress']; ?></span>
                        </div>
                    </button>
                </form>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>