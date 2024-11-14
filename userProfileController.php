<?php
if (session_id() == '') {
  session_start();
}
include_once '../../includes/conn.php'; // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['user_username'])) {
    header("Location: ../../user/index.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['user_username'];

// Query to fetch user details
$sql = "SELECT * FROM tbl_user WHERE user_username = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // Handle case if no user found
        $_SESSION['error'] = 'User not found.';
        header("Location: ../../user/profile/index.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'Database error: ' . $mysqli->error;
    header("Location: ../../user/profile/index.php");
    exit();
}

// Prepare user details
$fullName = trim($row['user_firstName'] . ' ' . $row['user_midInitial'] . ' ' . $row['user_lastName'] .
    (!empty($row['user_suffix']) ? ' ' . $row['user_suffix'] : ''));
$userPhoto = $row['user_photo'] ?: '../../pictures/default.jpg'; // Default photo if not set
$userAddress = $row['user_address'];
$userBirthDate = date("F j, Y", strtotime($row['user_birthDate'])); // Format date
$userContactNumber = $row['user_contactNumber'];

$mysqli->close();
?>
