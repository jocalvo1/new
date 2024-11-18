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

include '../../includes/conn.php';

// Fetch posts from the database
$result = mysqli_query($mysqli, "SELECT * FROM tbl_post ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WBDPRMS - Events</title>
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
        <!-- Content goes here -->




        <?php
          while ($post = mysqli_fetch_assoc($result)) {
              $post_id = $post['post_id'];
              $post_title = $post['post_title'];
              $post_description = $post['post_description'];
              $post_date = $post['created_at'];
        ?>

        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
              <div class="card">
                <div class="card-header bg-secondary">
                  <h4 class="card-title text-light"><?= ucfirst($post_title); ?></h4>
                  <hr class="border-light">
                  <p class="card-light text-light"><?= ucfirst($post_description); ?></p>
                </div>
                <div class="card-body text-center">
                <?php
                if ($post['post_image']) {
                    echo "<img src='../../pictures/events/{$post['post_image']}' alt='Post image' class='img-fluid'>";
                }
                ?>
                </div>
                <div class="card-footer text-body-secondary"> 
                  <small>
                  <?= date("F j, Y, g:i a", strtotime($post_date))?>
                  </small>
                </div>
            </div>
          </div>
          <div class="col-md-2"></div>
        </div>

        <?php      
          }
        ?>



      </div>
      <?php include '../../templates/footer.php'; ?>
    </div>
  </div>
  
</body>

</html>