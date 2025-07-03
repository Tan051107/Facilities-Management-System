<?php
    include "connection.php"; 

    $query = "SELECT * FROM holiday_schedule";
    $holiday_record = mysqli_query($connection, $query); 

    $holiday_list = [];

    while($holiday_info = mysqli_fetch_assoc($holiday_record)) {
        $holiday_list[] = $holiday_info;
    }

    echo json_encode(array_column($holiday_list, 'Date')); 
?>
