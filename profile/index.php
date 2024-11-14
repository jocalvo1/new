<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_username'])) {
  $title = ($_SESSION['sex'] == 'Male') ? 'Mr.' : (($_SESSION['sex'] == 'Female') ? 'Ma\'am' : '');
}

// Include the user profile controller
include '../../includes/user/userProfileController.php';
include '../../includes/conn.php';

// Query to fetch appointments with associated user data
$queryAllAppointments = "SELECT a.*, 
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
                        ORDER BY a.appointment_date DESC";

$resultAllAppointments = $mysqli->query($queryAllAppointments);

// Fetch appointments into an array
$appointments = $resultAllAppointments->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WBDPRMS - Profile</title>
  <?php include '../../templates/links.php'; ?>
</head>

<body>
  <div class="wrapper">
    <?php include '../../templates/sidebar.php'; ?>
    <div class="main-panel">
      <!-- TOPNAV Start -->
      <?php include '../../templates/topnav.php'; ?>
      <!-- TOPNAV End -->

      <div class="content">
        <div class="row">
          <!-- User Profile Section -->
          <div class="col-md-4">
            <div class="card card-user">
              <div class="image">
                <img src="../../pictures/background.jpg" alt="Background">
              </div>
              <div class="card-body">
                <div class="author">
                  <img class="avatar border-gray" src="<?php echo htmlspecialchars($userPhoto); ?>" alt="User Photo" onerror="this.onerror=null; this.src='../../pictures/default.jpg';">
                  <h5 class="title text-info"><?php echo htmlspecialchars($fullName); ?></h5>
                </div>
                <p class="description text-center text-dark">
                  <strong>Address:</strong> <?php echo htmlspecialchars($userAddress); ?>
                </p>
                <p class="description text-center text-dark">
                  <strong>Date of Birth:</strong> <?php echo htmlspecialchars($userBirthDate); ?>
                </p>
                <p class="description text-center text-dark">
                  <strong>Contact Number:</strong> <?php echo htmlspecialchars($userContactNumber); ?>
                </p>

              </div>
              <div class="card-footer">
                <hr>
                <div class="button-container">
                  <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                      <a href="./update.php?user_id={$row['user_id']}" style="font-size: 1rem">Update Profile</a>
                    </div>
                    <div class="col-3"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <div style="display: flex; justify-content: center;" class="pt-4">
                  <h4 class="card-title">Appointment Record</h4>
                </div>
                <hr>
              </div>
              <div class="card-body">
                <ul class="list-unstyled">
                  <?php if (!empty($appointments)): ?>
                    <?php foreach ($appointments as $appointment): ?>
                      <li>
                        <div class="row">
                          <div class="col-1"></div>
                          <div class="col-8">
                            <?php
                              $formattedDate = date("F j, Y", strtotime($appointment['appointment_date']));
                              echo htmlspecialchars($formattedDate);
                            ?>
                            <br />
                            <small class="text-danger">
                              <?php
                                $status = htmlspecialchars($appointment['appointment_status']);
                                $statusColor = ($status == 'Declined') ? 'red' : (($status == 'Approved') ? 'green' : 'blue');
                                echo "<span style='color: {$statusColor};'>{$status}</span>";
                              ?>
                            </small>
                          </div>
                          <div class="col-3 text-right">
                            <button class="btn btn-sm btn-outline-info btn-round btn-icon">
                              <a href="#" data-toggle="modal" data-target="#viewAppointmentModal<?php echo $appointment['appointment_id']; ?>" title="View"><i class="fa fa-eye"></i></a>
                            </button>
                          </div>
                        </div>
                      </li>

                      <!-- Modal for Viewing Appointment Details -->
                      <div class="modal fade" id="viewAppointmentModal<?php echo $appointment['appointment_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title"><strong>Appointment Date:</strong> <?php echo htmlspecialchars($formattedDate); ?></h5>
                              <button type="button" class="close btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="fa fa-xmark"></i></span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <p><strong>Full Name:</strong> <?php echo $appointment['user_firstName'] . ' ' . $appointment['user_midInitial'] . ' ' . $appointment['user_lastName']; ?></p>
                              <p><strong>Appointment Reason:</strong> <?php echo htmlspecialchars($appointment['appointment_reason']); ?></p>
                              <p><strong>Appointment Status:</strong> <?php echo htmlspecialchars($status); ?></p>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <li>No appointments found.</li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- FOOTER Start -->
      <?php include '../../templates/footer.php'; ?>
      <!-- FOOTER End -->
    </div>
  </div>
</body>

</html>
