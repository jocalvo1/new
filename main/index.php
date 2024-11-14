<?php
session_start();
if (!isset($_SESSION['staff_username'])) {
  header("Location: ../index.php");
  exit();
}

// Determine the title based on gender
$title = '';
if (isset($_SESSION['sex'])) {
  if ($_SESSION['sex'] == 'Male') {
    $title = 'Mr.';
  } elseif ($_SESSION['sex'] == 'Female') {
    $title = 'Ma\'am';
  }
}


include '../../includes/conn.php';

// Fetch both announcements and events in descending order
$query = "
  (SELECT 'event' AS type, post_id AS id, '' AS title, post_description AS description, post_image AS image, created_at 
   FROM tbl_post)
  UNION
  (SELECT 'announcement' AS type, announcement_id AS id, announcement_title AS title, announcement_description AS description, NULL AS image, created_at 
   FROM tbl_announcement)
  ORDER BY created_at DESC";
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
  <?php
  include '../../templates/links.php';
  ?>
</head>

<body>
  <div class="wrapper">
    <!-- SIDEBAR Start -->
    <?php
    include '../templates/sidebar.php';
    ?>
    <!-- SIDEBAR End -->
    <div class="main-panel">
      <!-- TOPNAV Start -->
      <?php
      include '../templates/topnav.php';
      ?>
      <!-- TOPNAV End -->
      <div class="content">
        <!-- Your content goes here -->
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
          <div>
            <?php if ($row['type'] === 'event') : ?>
              <!-- Display event -->
              <p><?php echo htmlspecialchars($row['description']); ?></p>
              <?php if ($row['image']) : ?>
                <img src='../../pictures/events/<?php echo htmlspecialchars($row['image']); ?>' alt='Event image' style='width:100%;height:auto;'>
              <?php endif; ?>
            <?php elseif ($row['type'] === 'announcement') : ?>
              <!-- Display announcement -->
              <h3><?php echo htmlspecialchars($row['title']); ?></h3>
              <p><?php echo htmlspecialchars($row['description']); ?></p>
            <?php endif; ?>
          </div>
          <hr>
        <?php endwhile; ?>
      </div>
      <?php
      include '../../templates/footer.php';
      ?>
    </div>
  </div>
</body>

</html>