<?php
    session_start();
    include "connection.php";
    include 'cleanup_expired_bookings.php';
    include "getNotifications.php";

    if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["courttype"])) {
        header("Location: ChooseBookingType.php");
        exit(); 
    }
    $courttype = $_POST["courttype"];
    $map = [
        "Badminton Court" => "badminton",
        "Basketball Court" => "basketball",
        "Meeting Room" => "meetingroom",
        "Volleyball Court" => "volleyball"
    ];
    $pageTypeKey = $map[$courttype];


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

    deleteExpiredBookings($connection);

    $query1 = "SELECT booking_slot1.Slot1_ID,booking_slot1.FacilityID,facility_information.Type,booking_slot1.Day FROM booking_slot1 JOIN facility_information ON booking_slot1.FacilityID = facility_information.FacilityID WHERE facility_information.Type = '$courttype'" ;
    $query2 = "SELECT booking_slot2.Slot2_ID,booking_slot2.FacilityID,facility_information.Type,booking_slot2.Day FROM booking_slot2 JOIN facility_information ON booking_slot2.FacilityID = facility_information.FacilityID WHERE facility_information.Type = '$courttype'" ;
    $query3 = "SELECT booking_slot3.Slot3_ID,booking_slot3.FacilityID,facility_information.Type,booking_slot3.Day FROM booking_slot3 JOIN facility_information ON booking_slot3.FacilityID = facility_information.FacilityID WHERE facility_information.Type = '$courttype'" ;
    $query4 = "SELECT booking_slot4.Slot4_ID,booking_slot4.FacilityID,facility_information.Type,booking_slot4.Day FROM booking_slot4 JOIN facility_information ON booking_slot4.FacilityID = facility_information.FacilityID WHERE facility_information.Type = '$courttype'" ;

    $results = mysqli_query($connection,$query1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="ChooseBookingTime.css?v=<?php echo time();?>">
    <link rel="stylesheet" href="Navbar.css?v=<?php echo time();?>">
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
                    <a href="ChooseBookingType.php" class="Main-page">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php elseif ($userType ==='Lecturer'): ?>
                    <a href="LecturerTimetable.php">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php" class="Main-page">Facility Reservation</a>
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
                    <a href="ChooseBookingType.php" class="Main-page">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php elseif ($userType ==='Lecturer'): ?>
                    <a href="LecturerTimetable.php">Timetable</a>
                    <a href="Library.php">Library</a>
                    <a href="ChooseBookingType.php" class="Main-page">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php else: ?>
                    <a href="Logout.php">Logout</a>
                <?php endif; ?>
            </nav>
    </header>

    <div class="container">
        <div class="content-box">
            <h1><?php echo "$courttype"; ?></h1>

            <div class="date-picker">
                <label for="reservation-date" class="date-label">Choose Date:</label>
                <input type="date" id="reservation-date" name="reservation-date" class="date-input">
            </div>

            <h3>Choose Desired Court/Room</h3>
            <div class="court-options">
                <div class="court-btn" onclick="selectCourt(1)" id="court1">Court 1</div>
                <div class="court-btn" onclick="selectCourt(2)" id="court2">Court 2</div>
                <div class="court-btn" onclick="selectCourt(3)" id="court3">Court 3</div>
                <div class="court-btn" onclick="selectCourt(4)" id="court4">Court 4</div>
            </div>
            <div id="time-section" style="display: none;">
                <h3>Choose Time Slot</h3>
                <div class="time-slots">
                    <div class="time-btn" onclick="selectTime(1)" id="time1" data-slot="1">9.00 am - 10.59 am</div>
                    <div class="time-btn" onclick="selectTime(2)" id="time2" data-slot="2">11.00 am - 12.59 pm</div>
                    <div class="time-btn" onclick="selectTime(3)" id="time3" data-slot="3">1.00 pm - 2.59 pm</div>
                    <div class="time-btn" onclick="selectTime(4)" id="time4" data-slot="4">3.00 pm - 4.59 pm</div>
                </div>

                <div class="submit-button">
                    <button class="book-btn" onclick="bookCourt()">Book Room/Court</button>
                </div>
            </div>
            <form id="booking-form" action="SubmitBooking.php" method="POST" style="display:none;">
                <input type="hidden" name="date" id="input-date" value="">
                <input type="hidden" name="court" id="input-court" value="">
                <input type="hidden" name="timeslot" id="input-timeslot" value="">
                <input type="hidden" name="day" id="input-day" value="">
            </form>
        </div>
        
    </div>
    <script>
        let dayOfWeek = "";
        let courtCode = "";
        const dateInput = document.getElementById('reservation-date');
        const inputDay = document.getElementById('input-day');

        const today = new Date();
        const tomorrow = new Date();
        const nextWeek = new Date();
        tomorrow.setDate(today.getDate() + 1)
        nextWeek.setDate(today.getDate() + 8);

        const formatDate = (date) => {
        return date.toISOString().split('T')[0];
        };

        dateInput.min = formatDate(tomorrow);
        dateInput.max = formatDate(nextWeek);
        
        function getDayOfWeek(dateString) {
            const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            const date = new Date(dateString);
            return days[date.getDay()];
        }


        dateInput.addEventListener('change', () => {
            const selectedDate = dateInput.value;
            const selectedDay = new Date(selectedDate).getDay();
            if (selectedDay === 0 || selectedDay === 6) {
                alert("Weekends are not allowed. Please choose a weekday.");
                dateInput.value = ""; 
                inputDate.value = "";
                inputDay.value = "";
                return;
            }
            inputDate.value = selectedDate;
            dayOfWeek = getDayOfWeek(selectedDate);
            inputDay.value = dayOfWeek;
            console.log("Selected date:", selectedDate);
            updateTimeSectionVisibility();
            checkBookedTimes(courtCode, dayOfWeek);
        });

        const pageType = "<?php echo $pageTypeKey; ?>";
        const courtIdMap = {
            badminton: ["BD-01", "BD-02", "BD-03", "BD-04"],
            basketball: ["BK-01", "BK-02", "BK-03", "BK-04"],
            meetingroom: ["MR-01", "MR-02", "MR-03", "MR-04"],
            volleyball: ["VB-01", "VB-02", "VB-03", "VB-04"]
        };
        const inputCourt = document.getElementById('input-court');
        const inputTimeslot = document.getElementById('input-timeslot');
        const inputDate = document.getElementById('input-date');
        let selectedCourt = null;
        let selectedTime = null;
    
        function selectCourt(courtId) {
            selectedCourt = courtId;
            document.querySelectorAll(".court-btn").forEach(btn => btn.classList.remove("selected"));
            document.getElementById(`court${courtId}`).classList.add("selected");
            
            courtCode = courtIdMap[pageType][courtId - 1];

            inputCourt.value = courtCode;
            updateTimeSectionVisibility();
            checkBookedTimes(courtCode, dayOfWeek);
        }

        function updateTimeSectionVisibility() {
            const dateSelected = dateInput.value !== "";
            const courtSelected = selectedCourt !== null;
            const timeSection = document.getElementById("time-section");
            
            if (dateSelected && courtSelected) {
                timeSection.style.display = "block";
            } else {
                timeSection.style.display = "none";
            }
        }

        function checkBookedTimes(courtCode, dayOfWeek) {
            selectedTime = null;
            inputTimeslot.value = "";

            fetch(`CheckBookingSlot.php?facility=${encodeURIComponent(courtCode)}&day=${encodeURIComponent(dayOfWeek)}`)
            .then(res => res.json())
            .then(data => {

                document.querySelectorAll(".time-btn").forEach(btn => {
                    btn.classList.remove("booked");
                    btn.style.pointerEvents = "auto"; 
                    btn.onclick = () => selectTime(parseInt(btn.dataset.slot));
                    btn.classList.remove("selected");
                });
                
                const slotToTimeId = {
                    "BookTableSlot1": "time1",
                    "BookTableSlot2": "time2",
                    "BookTableSlot3": "time3",
                    "BookTableSlot4": "time4"
                };

                for (const [slot, isBooked] of Object.entries(data)) {
                    if (isBooked) {
                        const timeBtn = document.getElementById(slotToTimeId[slot]);
                        if (timeBtn) {
                            timeBtn.classList.add("booked");
                        }
                    }
                }
            })
            .catch(err => console.error("Error fetching booking slots:", err));
        }   



        function selectTime(timeSlot) {
            const btns = document.querySelectorAll(".time-btn");

            btns.forEach(btn => {
                btn.classList.remove("selected");
            });

            const selectedBtn = document.querySelector(`.time-btn[data-slot="${timeSlot}"]`);

            if (selectedBtn.classList.contains("booked")) {
                selectedTime = null;
                inputTimeslot.value = "";
                return;
            }

            selectedBtn.classList.add("selected");
            selectedTime = timeSlot;
            inputTimeslot.value = timeSlot;
        }
    
        function bookCourt() {
            const date = document.getElementById("reservation-date").value;
            if (!selectedCourt || !selectedTime || !date) {
                alert("Please select date, court, and timeslot.");
                return;
            }
            inputDate.value = date;
    
            alert(`Booking confirmed:\nCourt ${selectedCourt}\nTime: ${selectedTime}\nDate: ${date}`);
            document.getElementById("booking-form").submit();
        }
    </script>

    <script src="Navbar.js?v=<?php echo time();?>"></script>
</body>
</html>