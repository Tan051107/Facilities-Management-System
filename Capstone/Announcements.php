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
    
    $getAnnounce = "SELECT * FROM announcement_table 
                    WHERE YEAR(Date) = YEAR(CURDATE()) 
                    AND MONTH(Date) = MONTH(CURDATE()) 
                    ORDER BY Date DESC";

    $result = $connection->query($getAnnounce);
    $announcements = [];
    $unviewedAnnouncements = [];
    $viewedAnnouncements = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $announcementId = $row['AnnouncementID'];

            $checkViewSql = "SELECT 1 FROM announcement_views WHERE AnnouncementID = ? AND UserID = ? AND UserType = ?";
            $viewStmt = $connection->prepare($checkViewSql);
            $viewStmt->bind_param("sss", $announcementId, $userId, $userType);
            $viewStmt->execute();
            $viewResult = $viewStmt->get_result();

            $isViewed = $viewResult->num_rows > 0; 

            if ($isViewed) {
                $viewedAnnouncements[] = $row;
            } else {
                $unviewedAnnouncements[] = $row; 
            }
        }
    } else {
        echo "<p>No announcements found.</p>";
    }

    $getFeedback = "SELECT * FROM feedback 
                    WHERE UserID = ? 
                    AND Progress IN ('Processing', 'Finished') 
                    AND TicketID NOT IN (SELECT TicketID FROM viewed_feedback WHERE UserID = ?)";

    $feedbackStmt = $connection->prepare($getFeedback);
    $feedbackStmt->bind_param("ss", $userId, $userId);
    $feedbackStmt->execute();
    $feedbackResult = $feedbackStmt->get_result();

    $feedbackNotifications = [];
    if ($feedbackResult->num_rows > 0) {
        while ($row = $feedbackResult->fetch_assoc()) {
            $feedbackNotifications[] = $row;
        }
    }

    $getViewedFeedback = "SELECT * FROM feedback 
                        WHERE UserID = ? 
                        AND TicketID IN (SELECT TicketID FROM viewed_feedback WHERE UserID = ?)";

    $viewedFeedbackStmt = $connection->prepare($getViewedFeedback);
    $viewedFeedbackStmt->bind_param("ss", $userId, $userId);
    $viewedFeedbackStmt->execute();
    $viewedFeedbackResult = $viewedFeedbackStmt->get_result();

    $viewedFeedbackNotifications = [];
    if ($viewedFeedbackResult->num_rows > 0) {
        while ($row = $viewedFeedbackResult->fetch_assoc()) {
            $viewedFeedbackNotifications[] = $row;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="Announcements.css?v=<?php echo time(); ?>">
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

    <section class="announcement-widget">
        <div class="announcement-header">
            <h2>Notifications</h2>
        </div>

        <div class="form-group">
            <div class="Notification-type">
                <button type="button" class="type-btn active" onclick="selectType(this)">Latest</button>
                <button type="button" class="type-btn" onclick="selectType(this)">History</button>
            </div>
            <input type="hidden" id="notifyType" name="notifyType" value="Latest">
        </div>

        <div id="unviewedNotifications">
            <?php if (empty($unviewedAnnouncements) && empty($feedbackNotifications)): ?>
                <p class="NotifyText">No Notifications Available</p>
            <?php else: ?>
                <?php foreach ($feedbackNotifications as $feedback): ?>
                    <?php
                    $feedbackId = $feedback['TicketID'];
                    $feedbackDate = date("F j, Y", strtotime($feedback['Date_Time']));
                    ?>
                    <form id="ticketForm<?= $feedbackId ?>" action="UpdatedFeedbackDetails.php" method="POST" style="display: none;">
                        <input type="hidden" name="ticket" id="ticketIDInput<?= $feedbackId ?>" value="<?= $feedbackId ?>">
                    </form>

                    <a href="#" onclick="submitTicketID(<?= $feedbackId ?>)" style="all: unset; cursor: pointer; display: block; width: 100%;">
                        <div class='announcement-item unviewed'>
                            <h4>Feedback Update: <?= htmlspecialchars($feedback['FeedbackType']) ?></h4>
                            <p><?= htmlspecialchars($feedback['Description']) ?></p>
                            <span class='date'><?= htmlspecialchars($feedbackDate) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>


                <?php foreach ($unviewedAnnouncements as $row): ?>
                    <?php
                    $announcementId = $row['AnnouncementID'];
                    $date = date("F j, Y", strtotime($row['Date']));
                    ?>
                    <a href="AnnouncementDetails.php?id=<?= $announcementId ?>" style="all: unset; cursor: pointer; display: block; width: 100%;">
                        <div class='announcement-item unviewed'>
                            <h4><?= htmlspecialchars($row['Title']) ?></h4>
                            <p><?= htmlspecialchars($row['Message']) ?></p>
                            <span class='date'><?= htmlspecialchars($date) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="viewedNotifications" style="display: none;">
            <?php if (empty($viewedAnnouncements) && empty($viewedFeedbackNotifications)): ?>
                <p class="NotifyText">No Notifications Available</p>
            <?php else: ?>
                <?php foreach ($viewedFeedbackNotifications as $feedback): ?>
                    <?php
                    $feedbackId = $feedback['TicketID'];
                    $feedbackDate = date("F j, Y", strtotime($feedback['Date_Time']));
                    ?>
                    <form id="ViewedticketForm<?= $feedbackId ?>" action="UpdatedFeedbackDetails.php" method="POST" style="display: none;">
                        <input type="hidden" name="ticket" id="viewedticketIDInput<?= $feedbackId ?>" value="<?= $feedbackId ?>">
                    </form>
                    <a href="#" onclick="submitViewedTicketID(<?= $feedbackId ?>)" style="all: unset; cursor: pointer; display: block; width: 100%;">
                        <div class='announcement-item viewed'>
                            <h4>Feedback Update: <?= htmlspecialchars($feedback['FeedbackType']) ?></h4>
                            <p><?= htmlspecialchars($feedback['Description']) ?></p>
                            <span class='date'><?= htmlspecialchars($feedbackDate) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php foreach ($viewedAnnouncements as $row): ?>
                    <?php
                    $announcementId = $row['AnnouncementID'];
                    $date = date("F j, Y", strtotime($row['Date']));
                    ?>
                    <a href="AnnouncementDetails.php?id=<?= $announcementId ?>" style="all: unset; cursor: pointer; display: block; width: 100%;">
                        <div class='announcement-item viewed'>
                            <h4><?= htmlspecialchars($row['Title']) ?></h4>
                            <p><?= htmlspecialchars($row['Message']) ?></p>
                            <span class='date'><?= htmlspecialchars($date) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <script>
        function selectType(button) {
            document.querySelectorAll('.type-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            document.getElementById('notifyType').value = button.textContent.trim();

            if (button.textContent.trim() === 'Latest') {
                document.getElementById('unviewedNotifications').style.display = 'block';
                document.getElementById('viewedNotifications').style.display = 'none';
            } else {
                document.getElementById('unviewedNotifications').style.display = 'none';
                document.getElementById('viewedNotifications').style.display = 'block';
            }
        }

        function submitTicketID(feedbackId) {
            var form = document.getElementById('ticketForm' + feedbackId);
            if (form) {
                form.submit(); 
            }
        }

        function submitViewedTicketID(feedbackId) {
            var form = document.getElementById('ViewedticketForm' + feedbackId);
            if (form) {
                form.submit();
            }
        }
    </script>
    <script src="Announcements.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>
