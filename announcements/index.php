<?php
session_start();
if (isset($_SESSION['user_username'])) {
  $title = '';
  if (isset($_SESSION['sex'])) {
    if ($_SESSION['sex'] == 'Male') {
      $title = 'Mr.';
    } elseif ($_SESSION['sex'] == 'Female') {
      $title = 'Ma\'am';
    }
  }
}
require "../../includes/user/userAnnouncementController.php";
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WBDPRMS - Announcements</title>
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
        <!-- CONTENT start -->

        <?php
        while ($announcement = mysqli_fetch_assoc($result)) {
          $announcement_title = htmlspecialchars($announcement['announcement_title']);
          $announcement_description = htmlspecialchars($announcement['announcement_description']); 
          $announcement_date = htmlspecialchars($announcement['created_at']); 
        ?>
        <div class="row my-3">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <div class="card">
                <?php
                  if (!empty($announcement_title)){
                    ?>
                <div class="card-header text-center">
                <h3 class="h3"><?= ucfirst($announcement_title) ?></h3>
                <hr>
                </div>
                <?php
                  }
                ?>
                <div class="card-body">
                  <p class="text-description fs-5"><?= ucfirst($announcement_description) ?></p>
                </div>
                <div class="card-footer text-body-secondary"> 
                  <small>
                  <?= date("F j, Y, g:i a", strtotime($announcement_date)) // Format the date ?>
                  </small>
                </div>
            </div>
          </div>
          <div class="col-md-2"></div>
      </div>
      <?php
      }
      ?>
        <!-- CONTENT end -->
      </div>
      <?php include '../../templates/footer.php'; ?>
    </div>
  </div>
</body>

</html>