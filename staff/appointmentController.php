<?php

session_start();
if (!isset($_SESSION['staff_username'])) {
  header("Location: ../index.php");
  exit();
}

// Determine the title based on gender
$title = '';
if (isset($_SESSION['sex'])) {
  $title = $_SESSION['sex'] == 'Male' ? 'Mr.' : 'Ma\'am';
}

require ('../../includes/conn.php');

// Fetch approved appointments only
$queryApprovedAppointments = "SELECT a.*, 
                            u.user_firstName, 
                            u.user_lastName, 
                            u.user_midInitial, 
                            u.user_username, 
                            u.user_sex, 
                            u.user_birthDate, 
                            u.user_contactNumber, 
                            u.user_address 
                        FROM tbl_appointment a 
                        JOIN tbl_user u ON a.user_id = u.user_id 
                        WHERE a.appointment_status = 'Approved'
                        ORDER BY a.appointment_date ASC";

$resultApprovedAppointments = $mysqli->query($queryApprovedAppointments);

$currentDate = new DateTime();