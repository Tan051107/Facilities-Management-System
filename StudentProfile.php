<?php
    session_start();
    include 'connection.php';
    include "getNotifications.php";

    $userType = '';
    if (isset($_SESSION['StudentID'])) {
        $userId = $_SESSION['StudentID'];
        $userType = 'Student';
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

    $studentid = $_SESSION['StudentID'];
    $query1 = "SELECT 
                sd.StudentID,
                sd.Name,
                sd.Email,
                sd.ProfilePic,
                sd.DateOfBirth,
                sd.Country,
                sd.IntakeCode,
                pi.ProgramName,
                pi.EducationLevel
                FROM student_details sd
                JOIN program_information pi ON sd.IntakeCode = pi.IntakeCode
                WHERE sd.StudentID = ?";
    $getinfo = $connection->prepare($query1);
    $info_details = [];

    if ($getinfo) {
        $getinfo->bind_param("s", $studentid);
        $getinfo->execute();
        $result = $getinfo->get_result();
        while ($row = $result->fetch_assoc()) {
            $info_details[] = [
                "id" => $row["StudentID"],
                "name" => $row["Name"],
                "email" => $row["Email"],
                "pic" => $row["ProfilePic"],
                "dob" => $row["DateOfBirth"],
                "country" => $row["Country"],
                "intake" => $row["IntakeCode"],
                "program" => $row["ProgramName"],
                "level" => $row["EducationLevel"]
            ];
        } 
        $getinfo->close();
    } else {
        echo "Error preparing the query: " . $connection->error;
    }

    $info_json = json_encode($info_details);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="StudentProfile.css?v=<?php echo time(); ?>">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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

    <div id="ProfilePage"> 
        <div id="profilerole">
            <h1>Student Profile</h1>
        </div>
        <div class="profile-container">
            <div class="profile-pic">
                <form action="UpdateProfile.php" method="POST" enctype="multipart/form-data">
                    <div class="profile-pic-wrapper" style="position: relative; display: inline-block;">
                        <label for="profileUpload">
                            <img id="profileImage" src="img/profile.png" alt="Profile Picture">
                            <div class="edit-icon"><i class='bx bx-pencil'></i></div>
                        </label>
                        <input type="file" name="profilePic" id="profileUpload" accept="image/*" style="display: none;">
                    </div>
                    <p id="uploadError">
                        Invalid file type. Please upload a JPG, PNG, or JPEG image.
                    </p>
                </form>
                <p class="name-email"><strong>Name</strong><br><small>Email</small></p>
            </div>
            <div class="profile-details">
                <h2>Student Details</h2>
                <p><strong>Student ID:</strong> TPXXXXXX</p>
                <p><strong>Course:</strong> Diploma in Mobile Legends</p>
                <p><strong>Date of Birth:</strong> 30-02-2000</p>
                <p><strong>Country:</strong> Malaysia</p>
                <p><strong>Intake Code:</strong> XXXXXXXX</p>
            </div>
        </div>
    </div>
    <script>
        const studentData = <?php echo json_encode($info_details); ?>;
    </script>
    <script src="StudentProfile.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>