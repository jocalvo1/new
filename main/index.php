<?php
session_start();
if (isset($_SESSION['user_username'])) {
  $title = '';
  if (isset($_SESSION['sex'])) {
    $title = ($_SESSION['sex'] == 'Male') ? 'Mr.' : 'Ma\'am';
  }
}

include '../../includes/conn.php';

// Fetch both announcements and events in descending order
$query = "
  (SELECT 'event' AS type, post_id AS id, post_title AS title, post_description AS description, post_image AS image, created_at AS date
   FROM tbl_post)
  UNION
  (SELECT 'announcement' AS type, announcement_id AS id, announcement_title AS title, announcement_description AS description, NULL AS image, created_at AS date 
   FROM tbl_announcement)
  ORDER BY date DESC";
$result = mysqli_query($mysqli, $query);

// Check for query errors
if (!$result) {
    die("Query failed: " . mysqli_error($mysqli));
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WBDPRMS - Home</title>
  <?php include '../../templates/links.php'; ?>
</head>

<body>
  <div class="wrapper">
    <?php include '../../templates/sidebar.php'; ?>
    <div class="main-panel"> 
      <?php include '../../templates/topnav.php'; ?>
      <div class="content">
        
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <div>
            <?php if ($row['type'] === 'event') : ?>

              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="card">
                      <div class="card-header p-3 bg-secondary">
                        <h4 class="card-title text-light"><?= ucfirst($row['title']); ?></h4>
                        <hr class="border-light">
                        <p class="card-text text-light"><?= ucfirst($row['description']); ?></p>
                      </div>
                      <div class="card-body text-center">
                      <?php
                      if ($row['image']) {
                          echo "<img src='../../pictures/events/{$row['image']}' alt='Post image' class='img-fluid'>";
                      }
                      ?>
                      </div>
                      <div class="card-footer text-body-secondary"> 
                        <small>
                        <?= date("F j, Y, g:i a", strtotime($row['date']))?>
                        </small>
                      </div>
                  </div>
                </div>
                <div class="col-md-2"></div>
              </div>

            <?php elseif ($row['type'] === 'announcement') : ?>
              
              <div class="row my-3">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                    <div class="card">
                        <?php
                          if (!empty($row['title'])){
                            ?>
                        <div class="card-header">
                        <h3 class="h3"><?= ucfirst($row['title']) ?></h3>
                        <hr>
                        </div>
                        <?php
                          }
                        ?>
                        <div class="card-body">
                          <p class="text-description fs-5"><?= ucfirst($row['description']) ?></p>
                        </div>
                        <div class="card-footer text-body-secondary"> 
                          <small>
                          <?= date("F j, Y, g:i a", strtotime($row['date'])) // Format the date ?>
                          </small>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-2"></div>
              </div>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
        
      </div>
      <?php include '../../templates/footer.php'; ?>
    </div>
  </div>
</body>

</html>
