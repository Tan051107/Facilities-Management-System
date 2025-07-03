<?php
    session_start();
    include "connection.php";

    $error = '';
    $success = '';

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
        $bookId = intval($_POST['bookId']);
        $newStatus = $_POST['status'];

        $updateQuery = "UPDATE library SET Status = ? WHERE BookID = ?";
        
        if ($stmt = mysqli_prepare($connection, $updateQuery)) {
            mysqli_stmt_bind_param($stmt, "si", $newStatus, $bookId);
            
            if (mysqli_stmt_execute($stmt)) {
                $success = "Status updated successfully!";
            } else {
                $error = "Error updating status: " . mysqli_error($connection);
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Error preparing statement: " . mysqli_error($connection);
        }
    }

    if (isset($_GET['bookId'])) {
        $bookId = intval($_GET['bookId']);

        $bookDetailsQuery = "SELECT * FROM library WHERE BookID = $bookId";
        $bookDetailsResult = mysqli_query($connection, $bookDetailsQuery);

        if ($bookDetailsResult && mysqli_num_rows($bookDetailsResult) > 0) {
            $bookDetails = mysqli_fetch_assoc($bookDetailsResult);
        } else {
            $error = "Book not found.";
        }
    } else {
        $error = "No book ID provided.";
    }

    $BookID = $bookDetails['BookID'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="Book Details.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/UniSphere.png" type="image/png">
</head>
<body class="lightmode">

    <iframe src="Library.php"></iframe>
    <div class="overlay"></div>
    
    <div class="book-details">
        <a href="Library.php" class="close-btn"><i class='bx bx-chevron-left-circle'></i></a>
        <div class="book-info">
            <div class="info-groups">
                <div class="left-group">
                    <div class="info-row">
                        <label>BookID:</label>
                        <div class="BookID-Container">
                            <div class="BookID-text"><?php echo htmlspecialchars($bookDetails['BookID']); ?></div>
                             <i class='bx bx-info-circle' title="View More" onclick="SearchBook(<?php echo $BookID; ?>)"></i>
                        </div>
                    </div>

                    <div class="info-row">
                        <label>Title:</label>
                        <p><?php echo htmlspecialchars($bookDetails['Title']); ?></p>
                    </div>
                    <div class="info-row">
                        <label>Author:</label>
                        <p><?php echo htmlspecialchars($bookDetails['Author']); ?></p>
                    </div>
                </div>

                <div class="right-group">
                    <div class="info-row">
                        <label>Genre:</label>
                        <p><?php echo htmlspecialchars($bookDetails['Genre']); ?></p>
                    </div>
                    <div class="info-row">
                        <label>Status:</label>
                        <form method="POST" action="">
                            <input type="hidden" name="bookId" value="<?php echo htmlspecialchars($bookDetails['BookID']); ?>">
                            <select id="statusSelect" name="status" onchange="this.form.submit()" <?php echo ($userType !== 'Admin') ? 'disabled' : ''; ?>> 
                                <option value="Available" <?php echo ($bookDetails['Status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                <option value="Reserved" <?php echo ($bookDetails['Status'] == 'Reserved') ? 'selected' : ''; ?>>Reserved</option>
                                <option value="Borrowed" <?php echo ($bookDetails['Status'] == 'Borrowed') ? 'selected' : ''; ?>>Borrowed</option>
                            </select>
                        </form>
                    </div>
                </div>
                </div>

            <p class="description-title"><strong>Summary</strong></p>
            <p class="description-text">
                <?php echo htmlspecialchars($bookDetails['Description']); ?>
            </p>

        </div>
    </div>
    <script src="Book Details.js?v=<?php echo time(); ?>"></script>
</body>
</html>