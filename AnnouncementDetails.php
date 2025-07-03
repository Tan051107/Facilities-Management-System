<?php
    session_start();
    include "connection.php";

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

    if (isset($_GET['id'])) {
        $announcementId = $_GET['id'];

        $insertViewSql = "INSERT INTO announcement_views (AnnouncementID, UserID, UserType) VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE AnnouncementID = AnnouncementID"; // Prevent duplicates
        $insertStmt = $connection->prepare($insertViewSql);
        $insertStmt->bind_param("sss", $announcementId, $userId, $userType);
        $insertStmt->execute();
        $insertStmt->close();

        $sql = "SELECT * FROM announcement_table WHERE AnnouncementID = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $announcementId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die("Announcement not found.");
        } else {
            $announcement = $result->fetch_assoc();
        }
    } else {
        die("No announcement selected.");
    }

    $stmt->close();
    $connection->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="AnnouncementDetails.css?v=<?php echo time(); ?>">
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/UniSphere.png" type="image/png">
</head>
<body class="lightmode">

    <iframe src="Announcements.php"></iframe>
    <div class="overlay"></div>

    <div class="announcement-container">
        <h2><?= htmlspecialchars($announcement['Title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($announcement['Message'])) ?></p>
        <span><?= date("F j, Y", strtotime($announcement['Date'])) ?></span>
        <br><br>
        <a href="Announcements.php" class="back-button">← Back to Home</a>
    </div>
</body>
</html>