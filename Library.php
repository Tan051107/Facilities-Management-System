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

    $stmt->close();

    $Status = 'All';
    $selectedValue = 'All';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedValue = $_POST['selectedValue'];

        switch ($selectedValue) {
            case 'Reserved':
                $query = "SELECT * FROM library WHERE Status = 'Reserved'";
                $Status = 'Reserved';
                break;
            case 'Borrowed':
                $query = "SELECT * FROM library WHERE Status = 'Borrowed'";
                $Status = 'Borrowed';
                break;
            case 'All':
            default:
                $query = "SELECT * FROM library";
                $Status = 'All';
                break;
        }

        $result = mysqli_query($connection, $query);
        $allBooks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $query = "SELECT * FROM library";
        $result = mysqli_query($connection, $query);
        $allBooks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    $technologyQuery = "SELECT * FROM library WHERE Genre = 'Technology'";
    $technologyResult = mysqli_query($connection, $technologyQuery);
    $techBooks = mysqli_fetch_all($technologyResult, MYSQLI_ASSOC);

    $historyQuery = "SELECT * FROM library WHERE Genre = 'History'";
    $historyResult = mysqli_query($connection, $historyQuery);
    $historyBooks = mysqli_fetch_all($historyResult, MYSQLI_ASSOC);

    $scienceQuery = "SELECT * FROM library WHERE Genre = 'Science'";
    $scienceResult = mysqli_query($connection, $scienceQuery);
    $scienceBooks = mysqli_fetch_all($scienceResult, MYSQLI_ASSOC);

    $socialScienceQuery = "SELECT * FROM library WHERE Genre = 'Social Sciences'";
    $socialScienceResult = mysqli_query($connection, $socialScienceQuery);
    $socialScienceBooks = mysqli_fetch_all($socialScienceResult, MYSQLI_ASSOC);

    $artLiteratureQuery = "SELECT * FROM library WHERE Genre = 'Arts & Literature'";
    $artLiteratureResult = mysqli_query($connection, $artLiteratureQuery);
    $artLiteratureBooks = mysqli_fetch_all($artLiteratureResult, MYSQLI_ASSOC);

    $genreImages = [
        'Technology' => 'TechBook.png',
        'History' => 'HistoryBook.png',
        'Science' => 'ScienceBook.png',
        'Social Sciences' => 'SocialScienceBook.png',
        'Arts & Literature' => 'Art&LiteratureBook.png'
    ];

    $totalBooksQuery = "SELECT COUNT(*) AS total FROM library";
    $totalBooksResult = mysqli_query($connection, $totalBooksQuery);
    $totalBooks = mysqli_fetch_assoc($totalBooksResult)['total'];

    $reservedBooksQuery = "SELECT COUNT(*) AS total FROM library WHERE Status = 'Reserved'";
    $reservedBooksResult = mysqli_query($connection, $reservedBooksQuery);
    $reservedBooks = mysqli_fetch_assoc($reservedBooksResult)['total'];

    $borrowedBooksQuery = "SELECT COUNT(*) AS total FROM library WHERE Status = 'Borrowed'";
    $borrowedBooksResult = mysqli_query($connection, $borrowedBooksQuery);
    $borrowedBooks = mysqli_fetch_assoc($borrowedBooksResult)['total'];

    function getStatusClass($status) {
        switch ($status) {
            case 'Reserved':
                return 'reserved';
            case 'Borrowed':
                return 'borrowed';
            case 'Available':
                return 'available';
            default:
                return ''; 
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="Library.css?v=<?php echo time(); ?>">
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
                    <a href="Library.php" class="Main-page">Library</a>
                    <a href="FeedbackList.php">Feedback Management</a>
                    <a href="CreateAcc.php">Account Creation</a>
                    <a href="MakeAnnouncement.php">Announcement</a>
                <?php elseif ($userType === 'Student'): ?>
                    <a href="StudentTimetable.php">Timetable</a>
                    <a href="Library.php" class="Main-page">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php elseif ($userType ==='Lecturer'): ?>
                    <a href="LecturerTimetable.php">Timetable</a>
                    <a href="Library.php" class="Main-page">Library</a>
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
                    <a href="Library.php" class="Main-page">Library</a>
                    <a href="FeedbackList.php">Feedback Management</a>
                    <a href="CreateAcc.php">Account Creation</a>
                    <a href="MakeAnnouncement.php">Announcement</a>
                <?php elseif ($userType === 'Student'): ?>
                    <a href="StudentTimetable.php">Timetable</a>
                    <a href="Library.php" class="Main-page">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php elseif ($userType ==='Lecturer'): ?>
                    <a href="LecturerTimetable.php">Timetable</a>
                    <a href="Library.php" class="Main-page">Library</a>
                    <a href="ChooseBookingType.php">Facility Reservation</a>
                    <a href="BusSchedule.php">Transport Service</a>
                    <a href="submitFeedback.php">Feedback</a>
                <?php else: ?>
                    <a href="Logout.php">Logout</a>
                <?php endif; ?>
            </nav>
    </header>
    
    <div class="LibraryContainer">
        <form id="bookForm" method="POST" action="">
            <div class="bookStatus">
                <h3>Total of Book: <?php echo $totalBooks; ?></h3>
                <label class="theme-toggle">
                    <input type="radio" name="bookStatus" value="All" <?php if (isset($selectedValue) && $selectedValue === 'All') echo 'checked'; ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="bookStatus">
                <h3>Total of reserved book: <?php echo $reservedBooks; ?></h3>
                <label class="theme-toggle">
                    <input type="radio" name="bookStatus" value="Reserved" <?php if (isset($selectedValue) && $selectedValue === 'Reserved') echo 'checked'; ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="bookStatus">
                <h3>Total of borrowed book: <?php echo $borrowedBooks; ?></h3>
                <label class="theme-toggle">
                    <input type="radio" name="bookStatus" value="Borrowed" <?php if (isset($selectedValue) && $selectedValue === 'Borrowed') echo 'checked'; ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <input type="hidden" name="selectedValue" id="selectedValue">
        </form>


        <div class="searchBar">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search..." onkeyup="filterBooks()">
                <button type="submit"><i class='bx bx-search'></i></button>
                <div id="dropdown" class="dropdown-content"></div>
            </div>
        </div>

        <section class="Library">
        <div class="BookRow">
            <h5><?php echo $Status; ?></h5>
        </div>
        <div class="container swiper">
            <div class="wrapper">
                <div class="card-list swiper-wrapper">
                    <?php foreach ($allBooks as $book): 
                        $imageFileName = isset($genreImages[$book['Genre']]) ? $genreImages[$book['Genre']] : 'DefaultBook.png';
                    ?>
                    <?php
                        $statusClass = getStatusClass($book['Status']);
                    ?>
                        <div class="card swiper-slide" data-bookid="<?php echo $book['BookID']; ?>">
                            <div class="card-image">
                                <img src="img/<?php echo $imageFileName; ?>" alt="<?php echo $book['Title']; ?>">
                                <p class="card-tag <?php echo $statusClass; ?>"><?php echo htmlspecialchars($book['Status']); ?></p>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title"><?php echo $book['Title']; ?></h3>
                                <p class="card-text"><?php echo $book['Description']; ?></p>
                                <div class="card-footer">
                                    <div class="card-profile">
                                        <img src="img/BookAuthor.png" alt="<?php echo $book['Author']; ?>">
                                        <div class="card-profile-info">
                                            <span class="card-profile-name"><?php echo $book['Author']; ?></span>
                                            <span class="card-profile-role">Author</span>
                                        </div>
                                    </div>
                                    <a href="Book Details.php?bookId=<?php echo $book['BookID']; ?>" class="card-button">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-pagination"></div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <div class="BookRow">
            <h5>Technology</h5>
        </div>
        <div class="container swiper">
            <div class="wrapper">
                <div class="card-list swiper-wrapper">
                    <?php foreach ($techBooks as $book): 
                        $imageFileName = isset($genreImages[$book['Genre']]) ? $genreImages[$book['Genre']] : 'DefaultBook.png';
                    ?>
                    <?php
                        $statusClass = getStatusClass($book['Status']);
                    ?>
                        <div class="card swiper-slide" data-bookid="<?php echo $book['BookID']; ?>">
                            <div class="card-image">
                                <img src="img/<?php echo $imageFileName; ?>" alt="<?php echo $book['Title']; ?>">
                                <p class="card-tag <?php echo $statusClass; ?>"><?php echo htmlspecialchars($book['Status']); ?></p>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title"><?php echo $book['Title']; ?></h3>
                                <p class="card-text"><?php echo $book['Description']; ?></p>
                                <div class="card-footer">
                                    <div class="card-profile">
                                        <img src="img/BookAuthor.png" alt="<?php echo $book['Author']; ?>">
                                        <div class="card-profile-info">
                                            <span class="card-profile-name"><?php echo $book['Author']; ?></span>
                                            <span class="card-profile-role">Author</span>
                                        </div>
                                    </div>
                                    <a href="Book Details.php?bookId=<?php echo $book['BookID']; ?>" class="card-button">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-pagination"></div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <div class="BookRow">
            <h5>Social Sciences</h5>
        </div>
        <div class="container swiper">
            <div class="wrapper">
                <div class="card-list swiper-wrapper">
                    <?php foreach ($socialScienceBooks as $book): 
                        $imageFileName = isset($genreImages[$book['Genre']]) ? $genreImages[$book['Genre']] : 'DefaultBook.png';
                    ?>
                    <?php
                        $statusClass = getStatusClass($book['Status']);
                    ?>
                        <div class="card swiper-slide" data-bookid="<?php echo $book['BookID']; ?>">
                            <div class="card-image">
                                <img src="img/<?php echo $imageFileName; ?>" alt="<?php echo $book['Title']; ?>">
                                <p class="card-tag <?php echo $statusClass; ?>"><?php echo htmlspecialchars($book['Status']); ?></p>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title"><?php echo $book['Title']; ?></h3>
                                <p class="card-text"><?php echo $book['Description']; ?></p>
                                <div class="card-footer">
                                    <div class="card-profile">
                                        <img src="img/BookAuthor.png" alt="<?php echo $book['Author']; ?>">
                                        <div class="card-profile-info">
                                            <span class="card-profile-name"><?php echo $book['Author']; ?></span>
                                            <span class="card-profile-role">Author</span>
                                        </div>
                                    </div>
                                    <a href="Book Details.php?bookId=<?php echo $book['BookID']; ?>" class="card-button">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-pagination"></div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <div class="BookRow">
            <h5>History</h5>
        </div>
        <div class="container swiper">
            <div class="wrapper">
                <div class="card-list swiper-wrapper">
                    <?php foreach ($historyBooks as $book): 
                        $imageFileName = isset($genreImages[$book['Genre']]) ? $genreImages[$book['Genre']] : 'DefaultBook.png';
                    ?>
                    <?php
                        $statusClass = getStatusClass($book['Status']);
                    ?>
                        <div class="card swiper-slide" data-bookid="<?php echo $book['BookID']; ?>">
                            <div class="card-image">
                                <img src="img/<?php echo $imageFileName; ?>" alt="<?php echo $book['Title']; ?>">
                                <p class="card-tag <?php echo $statusClass; ?>"><?php echo htmlspecialchars($book['Status']); ?></p>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title"><?php echo $book['Title']; ?></h3>
                                <p class="card-text"><?php echo $book['Description']; ?></p>
                                <div class="card-footer">
                                    <div class="card-profile">
                                        <img src="img/BookAuthor.png" alt="<?php echo $book['Author']; ?>">
                                        <div class="card-profile-info">
                                            <span class="card-profile-name"><?php echo $book['Author']; ?></span>
                                            <span class="card-profile-role">Author</span>
                                        </div>
                                    </div>
                                    <a href="Book Details.php?bookId=<?php echo $book['BookID']; ?>" class="card-button">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-pagination"></div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <div class="BookRow">
            <h5>Sciences</h5>
        </div>
        <div class="container swiper">
            <div class="wrapper">
                <div class="card-list swiper-wrapper">
                    <?php foreach ($scienceBooks as $book): 
                        $imageFileName = isset($genreImages[$book['Genre']]) ? $genreImages[$book['Genre']] : 'DefaultBook.png';    
                    ?>
                    <?php
                        $statusClass = getStatusClass($book['Status']);
                    ?>
                        <div class="card swiper-slide" data-bookid="<?php echo $book['BookID']; ?>">
                            <div class="card-image">
                                <img src="img/<?php echo $imageFileName; ?>" alt="<?php echo $book['Title']; ?>">
                                <p class="card-tag <?php echo $statusClass; ?>"><?php echo htmlspecialchars($book['Status']); ?></p>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title"><?php echo $book['Title']; ?></h3>
                                <p class="card-text"><?php echo $book['Description']; ?></p>
                                <div class="card-footer">
                                    <div class="card-profile">
                                        <img src="img/BookAuthor.png" alt="<?php echo $book['Author']; ?>">
                                        <div class="card-profile-info">
                                            <span class="card-profile-name"><?php echo $book['Author']; ?></span>
                                            <span class="card-profile-role">Author</span>
                                        </div>
                                    </div>
                                    <a href="Book Details.php?bookId=<?php echo $book['BookID']; ?>" class="card-button">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="swiper-pagination"></div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <div class="BookRow">
            <h5>Arts & Literature</h5>
        </div>
        <div class="container swiper">
            <div class="wrapper">
                <div class="card-list swiper-wrapper">
                    <?php foreach ($artLiteratureBooks as $book): 
                        $imageFileName = isset($genreImages[$book['Genre']]) ? $genreImages[$book['Genre']] : 'DefaultBook.png';    
                    ?>
                    <?php
                        $statusClass = getStatusClass($book['Status']);
                    ?>
                        <div class="card swiper-slide" data-bookid="<?php echo $book['BookID']; ?>">
                            <div class="card-image">
                                <img src="img/<?php echo $imageFileName; ?>" alt="<?php echo $book['Title']; ?>">
                                <p class="card-tag <?php echo $statusClass; ?>"><?php echo htmlspecialchars($book['Status']); ?></p>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title"><?php echo $book['Title']; ?></h3>
                                <p class="card-text"><?php echo $book['Description']; ?></p>
                                <div class="card-footer">
                                    <div class="card-profile">
                                        <img src="img/BookAuthor.png" alt="<?php echo $book['Author']; ?>">
                                        <div class="card-profile-info">
                                            <span class="card-profile-name"><?php echo $book['Author']; ?></span>
                                            <span class="card-profile-role">Author</span>
                                        </div>
                                    </div>
                                    <a href="Book Details.php?bookId=<?php echo $book['BookID']; ?>" class="card-button">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="Library.js?v=<?php echo time(); ?>"></script>
    <script src="Navbar.js?v=<?php echo time(); ?>"></script>
</body>
</html>