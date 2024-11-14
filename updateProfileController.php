<?php
session_start();
include_once '../conn.php';

if (isset($_POST['updateProfile'])) {
  // Ensure the user is logged in
  if (!isset($_SESSION['user_username'])) {
    header("Location: ../index.php");
    exit();
  }

  // Retrieve and sanitize the input fields
  $userId = $_SESSION['user_username'];  // Assuming the user ID is stored in session
  $user_firstName = trim(ucwords($_POST['user_firstName']));
  $user_midInitial = strtoupper(trim($_POST['user_midInitial']));
  $user_lastName = trim(ucwords($_POST['user_lastName']));
  $user_suffix = !empty($_POST['user_suffix']) ? trim($_POST['user_suffix']) : null;
  $user_birthDate = trim($_POST['user_birthDate']);
  $user_contactNumber = trim($_POST['user_contactNumber']);
  $user_sex = trim($_POST['user_sex']);
  $user_address = trim($_POST['user_address']);

  // Check if user is uploading a new photo
  $target_dir = "../../pictures/user/"; // Set the target directory
  $uploadOk = 1;
  $user_photo = null;

  // Retrieve the current user's photo from the database (if no new photo uploaded, use the old one)
  $sql = "SELECT user_photo FROM tbl_user WHERE user_username = ?";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param("s", $userId);
  $stmt->execute();
  $stmt->bind_result($currentPhoto);
  $stmt->fetch();
  $stmt->close();

  // If the user uploaded a new photo, process it
  if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] === UPLOAD_ERR_OK) {
    // Get the file details
    $file_name = basename($_FILES["user_photo"]["name"]);
    $target_file = $target_dir . $userId . "_" . time() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_extensions = array("jpg", "jpeg", "png");

    // Validate the file type
    if (!in_array($imageFileType, $allowed_extensions)) {
      $_SESSION['error'] = 'Only JPG, JPEG, and PNG files are allowed.';
      $uploadOk = 0;
    }

    // Validate file size (limit to 3MB)
    if ($_FILES["user_photo"]["size"] > 3000000) {
      $_SESSION['error'] = 'Your file is too large. Maximum allowed size is 3MB.';
      $uploadOk = 0;
    }

    // If the upload is successful
    if ($uploadOk == 1) {
      if (!move_uploaded_file($_FILES["user_photo"]["tmp_name"], $target_file)) {
        $_SESSION['error'] = 'Sorry, there was an error uploading your photo.';
        header("Location: ../../user/editProfile.php");
        exit();
      } else {
        // Store the path of the uploaded file
        $user_photo = $target_file;

        // Optionally, delete the old photo from the server if it's replaced
        if (!empty($currentPhoto) && file_exists($currentPhoto)) {
          unlink($currentPhoto);
        }
      }
    } else {
      header("Location: ../../user/profile/update.php");
      exit();
    }
  } else {
    // If no new photo uploaded, retain the current photo
    $user_photo = $currentPhoto;
  }

  // Update the user's information in the database
  $sql = "UPDATE tbl_user
          SET user_firstName = ?, user_midInitial = ?, user_lastName = ?, user_suffix = ?, user_birthDate = ?, user_contactNumber = ?, user_sex = ?, user_address = ?, user_photo = ?
          WHERE user_username = ?";

  if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param(
      "ssssssssss",
      $user_firstName,
      $user_midInitial,
      $user_lastName,
      $user_suffix,
      $user_birthDate,
      $user_contactNumber,
      $user_sex,
      $user_address,
      $user_photo,
      $userId
    );

    // Execute the query
    if ($stmt->execute()) {
      $_SESSION['success'] = 'Profile updated successfully!';
      header("Location: ../../user/profile/index.php");
      exit();
    } else {
      $_SESSION['error'] = 'Error: ' . $stmt->error;
      header("Location: ../../user/profile/update.php");
      exit();
    }

    $stmt->close();
  } else {
    $_SESSION['error'] = 'Error: ' . $mysqli->error;
    header("Location: ../../user/profile/update.php");
    exit();
  }

  $mysqli->close();
}
