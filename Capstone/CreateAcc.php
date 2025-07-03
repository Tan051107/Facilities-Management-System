<?php
    session_start();
    include 'connection.php';
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="Navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="CreateAcc.css?v=<?php echo time(); ?>">
    <script src="ShowUserCreateAcc.js?v=<?php echo time(); ?>"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                    <a href="CreateAcc.php" class="Main-page">Account Creation</a>
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
                    <a href="CreateAcc.php" class="Main-page">Account Creation</a>
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

    <div id="admin-create-acc-section">
        <div class="role-section">
            <a href="#" class="current" onclick="showAdminAcc()">Admin</a>
            <a href="#" onclick="showStudentAcc()">Student</a>
            <a href="#" onclick="showLecturerAcc()">Lecturer</a>
        </div>
        <div class="create-acc-form-container">
            <h2>Create Account</h2>
            <form id="create-admin-acc-form" action="validateCreateAdminAcc.php" method="POST">
                <div class="form-group">
                    <label for="admin-name">Full Name</label>
                    <input type="text" id="admin-name" name="admin-name">
                    <div id="admin-name-error" class="invalid-message"></div>
                </div>

                <div class="form-group">
                    <label for="admin-email">Email</label>
                    <input type="email" id="admin-email" name="admin-email" readonly>
                    <div id="admin-email-error" class="invalid-message"></div>
                </div>

                <div class="form-group">
                    <label for="admin-id">ID</label>
                    <input type="text" id="admin-id" name="admin-id" readonly>
                    <div id="admin-id-error" class="invalid-message"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="admin-password">Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="admin-password" name="admin-password">
                            <span class="material-icons" id="admin-togglePassword" onclick="showHidePassword('admin-password','admin-togglePassword')">visibility_off</span>    
                        </div>
                        <div id="admin-password-error" class="invalid-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="admin-confirmPassword">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="admin-confirmPassword" name="admin-confirmPassword">
                            <span class="material-icons" id="admin-toggleConfirmPassword" onclick="showHidePassword('admin-confirmPassword','admin-toggleConfirmPassword')">visibility_off</span>    
                        </div>
                        <div id="admin-confirmPassword-error" class="invalid-message"></div>
                    </div>
                </div>

                <button type="submit" class="submit-btn" name="admin-submit-btn">Create Account</button>
            </form>
        </div>
    </div>

    <div id="student-create-acc-section">
        <div class="role-section">
            <a href="#" onclick="showAdminAcc()">Admin</a>
            <a href="#" class="current" onclick="showStudentAcc()">Student</a>
            <a href="#" onclick="showLecturerAcc()">Lecturer</a>
        </div>
        <div class="create-acc-form-container">
            <h2>Create Account</h2>
            <form id="create-student-acc-form" action="validateCreateStudentAcc.php" method="POST">
                <div class="form-group">
                    <label for="student-name">Full Name</label>
                    <input type="text" id="student-name" name="student-name"> 
                    <div id="student-name-error" class="invalid-message"></div>
                </div>

                <div class="form-group">
                    <label for="student-email">Email</label>
                    <input type="email" id="student-email" name="student-email" readonly>
                    <div id="student-email-error" class="invalid-message"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student-id">ID</label>
                        <input type="text" id="student-id" name="student-id" readonly>
                        <div id="student-id-error" class="invalid-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="student-intake">Intake Code</label>
                        <select id="student-intake" name="student-intake">
                            <?php
                                $query = 'SELECT * from program_information';
                                $results = mysqli_query($connection,$query);
                                if ($results){
                                    while ($row = mysqli_fetch_assoc($results)){
                                        echo "<option>".  $row['IntakeCode']."</option>";
                                    }
                                }
                                else{
                                    echo "No schedule found";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student-dob">Date of Birth</label>
                        <input type="date" id="student-dob" name="student-dob">
                        <div id="student-dob-error" class="invalid-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="student-gender">Gender</label>
                        <select id="student-gender" name = "student-gender">
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="student-country">Country of Origin</label>
                    <input list="countries" id="student-country" name="student-country" placeholder="Select Your Country"/>
                    <datalist id="countries"></datalist>
                    <div id="student-country-error" class="invalid-message"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="student-password">Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="student-password" name="student-password">
                            <span class="material-icons" id="student-togglePassword" onclick="showHidePassword('student-password','student-togglePassword')">visibility_off</span>    
                        </div>
                        <div id="student-password-error" class="invalid-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="student-confirmPassword">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="student-confirmPassword" name="student-confirmPassword">
                            <span class="material-icons" id="student-toggleConfirmPassword" onclick="showHidePassword('student-confirmPassword','student-toggleConfirmPassword')">visibility_off</span>    
                        </div>
                    <div id="student-confirmPassword-error" class="invalid-message"></div>
                    </div>
                </div>

                <button type="submit" class="submit-btn" name="student-submit-btn">Create Account</button>
            </form>

        </div>
    </div>

    <div id="lecturer-create-acc-section">
        <div class="role-section">
            <a href="#" onclick="showAdminAcc()">Admin</a>
            <a href="#" onclick="showStudentAcc()">Student</a>
            <a href="#" class="current" onclick="showLecturerAcc()">Lecturer</a>
        </div>
        <div class="create-acc-form-container">
            <h2> Create Account</h2>
            <form id="create-lecturer-acc-form" action="validateCreateLecturerAcc.php" method="POST">
                <div class="form-group">
                    <label for="lecturer-name">Full Name</label>
                    <input type="text" id="lecturer-name" name="lecturer-name">
                    <div id="lecturer-name-error" class="invalid-message"></div>
                </div>

                <div class="form-group">
                    <label for="lecturer-email">Email</label>
                    <input type="email" id="lecturer-email" name="lecturer-email" readonly>
                    <div id="lecturer-email-error" class="invalid-message"></div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="lecturer-id">ID</label>
                        <input type="text" id="lecturer-id" name="lecturer-id" readonly>
                        <div id="lecturer-id-error" class="invalid-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="lecturer-job-title">Job Title</label>
                        <select id="lecturer-job-title" name="lecturer-job-title">
                            <option>Lecturer</option>
                            <option>Part Time Lecturer</option>
                            <option>Senior Lecturer</option>
                            <option>Associate Professor</option>
                            <option>Professor</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="lecturer-department">Select Department</label>
                    <select id="lecturer-department" name="lecturer-department">
                        <option>Faculty of Technology</option>
                        <option>Faculty of Computing</option>
                        <option>Faculty of Language</option>
                        <option>Faculty of Academic Research</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="lecturer-password">Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="lecturer-password" name="lecturer-password">
                            <span class="material-icons" id="lecturer-togglePassword" onclick="showHidePassword('lecturer-password','lecturer-togglePassword')">visibility_off</span>    
                        </div>
                        <div id="lecturer-password-error" class="invalid-message"></div>
                    </div>

                    <div class="form-group">
                        <label for="lecturer-confirmPassword">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="lecturer-confirmPassword" name="lecturer-confirmPassword">
                            <span class="material-icons" id="lecturer-toggleConfirmPassword" onclick="showHidePassword('lecturer-confirmPassword','lecturer-toggleConfirmPassword')">visibility_off</span>    
                        </div>
                        <div id="lecturer-confirmPassword-error" class="invalid-message"></div>
                    </div>
                </div>

                <button type="submit" class="submit-btn" name="lecturer-submit-btn">Create Account</button>
            </form>
        </div>
    </div>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
    <script src="ShowUserCreateAcc.js?v=<?php echo time(); ?>"></script>
    <script src="SearchCountry.js?v=<?php echo time(); ?>"></script>
    <script src="ValidateCreateAdminAcc.js?v=<?php echo time(); ?>"></script>
    <script src="ValidateCreateStudentAcc.js?v=<?php echo time(); ?>"></script>    
    <script src="ValidateCreateLecturerAcc.js?v=<?php echo time(); ?>"></script>  
    <script src="ShowHidePassword.js?v=<?php echo time(); ?>"></script>
    <script>
        window.onload = function () {
        showAdminAcc();
        };
    </script>
</body>
</html>
<?php
    mysqli_close($connection);
?>