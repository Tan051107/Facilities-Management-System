<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "connection.php";

$userId = '';
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
    $notificationCount = 0;
    return;
}

$unviewedAnnouncementCount = 0;
$announcementQuery = "SELECT AnnouncementID FROM announcement_table 
                      WHERE YEAR(Date) = YEAR(CURDATE()) AND MONTH(Date) = MONTH(CURDATE())";

$annResult = $connection->query($announcementQuery);
if ($annResult && $annResult->num_rows > 0) {
    while ($row = $annResult->fetch_assoc()) {
        $announcementId = $row['AnnouncementID'];
        $checkViewSql = "SELECT 1 FROM announcement_views WHERE AnnouncementID = ? AND UserID = ? AND UserType = ?";
        $stmt = $connection->prepare($checkViewSql);
        $stmt->bind_param("sss", $announcementId, $userId, $userType);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            $unviewedAnnouncementCount++;
        }
    }
}

$feedbackQuery = "SELECT COUNT(*) as count FROM feedback 
                  WHERE UserID = ? 
                  AND Progress IN ('Processing', 'Finished') 
                  AND TicketID NOT IN (SELECT TicketID FROM viewed_feedback WHERE UserID = ?)";

$stmt = $connection->prepare($feedbackQuery);
$stmt->bind_param("ss", $userId, $userId);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$unseenFeedbackCount = $row['count'] ?? 0;

$notificationCount = $unviewedAnnouncementCount + $unseenFeedbackCount;
?>
