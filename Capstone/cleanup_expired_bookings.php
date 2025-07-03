<?php

function deleteExpiredBookings($connection) {
    $today = date('Y-m-d');
    $sql = "DELETE FROM bookingtable WHERE booked_date < ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $stmt->close();
}
?>