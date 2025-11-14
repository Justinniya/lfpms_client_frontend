<?php
session_start();

if (!isset($_SESSION['id'])) {
  header("location: ../index.php");
  exit();
}

$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "lfpms"; // your database name

// Create connection
try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

$user_id = $_SESSION['id'];

// Corrected SQL query
$sql = "SELECT username, email, fname FROM users WHERE userid = :userid";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':userid', $user_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the data if available
if (count($result) > 0) {
  $user = $result[0];
  $username = $user['username'];
  $email = $user['email'];
  $fname = $user['fname'];

  // Echo user information
  echo "Welcome, " . htmlspecialchars($fname) . " (" . htmlspecialchars($username) . ")";
  echo "<br>Email: " . htmlspecialchars($email);
} else {
  echo "No user found!";
}

include '../session.php';
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Local Food Product Management System - Home Page</title>
    <link href="assets/img/dti.png" rel="icon">
    <link href="assets/img/dti.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <style>
      /* Add this to your main.css file */
      .custom-shadow {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }
    </style>

  </head>

  <body class="service-details-page">
    <header id="header" class="header fixed-top d-flex align-items-center">
      <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
          <img src="assets/img/dti.png" alt="">
          <h1 class="sitename" style="color: #1e04a1;">LFPMS</h1>
        </a>
        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#"><?php echo "Welcome, " . htmlspecialchars($fname); ?></a></li>
            <li><a href="customer_index.php">Home</a></li>
            <li><a href="scanner.php">Scan QR <i style="font-size:20px;" class="fa-solid fa-expand"></i></a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
      </div>
    </header>

    <main class="main">
      <div class="container mt-5">
        <?php

        if (isset($_GET['id'])) {
          $id = $_GET['id'];

          try {
            $query = "SELECT * FROM `products` INNER JOIN users ON users.userid = products.msme_id WHERE product_id = :id";
            $statement = $conn->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            $Data = $statement->fetch(PDO::FETCH_ASSOC);

            if ($Data) {
        ?>

              <div class="container mt-4" style="margin-top: 100px !important;">
                <div class="row justify-content-center align-items-center">
                  <!-- Product Information Section -->
                  <div class="col-md-4 text-center">
                    <img class="img-fluid rounded custom-shadow" src="../admin/uploaded_img/<?php echo htmlspecialchars($Data['productImage']); ?>" alt="Product Image" style="width: 400px; height: 400px; object-fit: cover;">
                  </div>
                  <div class="col-md-6 border rounded p-4 custom-shadow">
                    <h2 class="fw-bold"><?php echo htmlspecialchars($Data['productName']); ?> <span style="float:right;"><a style="font-size: 20px;" href="customer_index.php"><i class="bi bi-arrow-left"></i> Back</a></span></h2>
                    <h3 class="text-success">&#8369;<?php echo htmlspecialchars($Data['productPrice']); ?></h3>

                    <div class="mt-3">
                      <p><strong>Contact Person:</strong> <?php echo htmlspecialchars($Data['fname'] . ' ' . $Data['Lname']); ?></p>
                      <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($Data['phone']); ?></p>
                      <p><strong>Business Address:</strong> <?php echo htmlspecialchars($Data['address']); ?></p>
                      <p><strong>Email Address:</strong> <?php echo htmlspecialchars($Data['email']); ?></p>
                      <p><strong>Product Detail:</strong> <?php echo htmlspecialchars($Data['productDetails']); ?></p>
                    </div>
                  </div>
                </div>
              </div>

      </div>

      <div class="container">
        <div class="row">
          <!-- Comment Section -->
          <div class="col-md-12 p-4 border rounded mt-4">
            <h2 class="text-left mt-4">Comments / Reviews</h2>
            <?php
              function getCommentsForProduct($id)
              {
                global $conn;
                try {
                  $stmt = $conn->prepare("SELECT * FROM feedback WHERE product_id = ?");
                  $stmt->execute([$id]);
                  return $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                  echo "Error: " . $e->getMessage();
                  return array();
                }
              }

              $comments = getCommentsForProduct($id);

              if (!empty($comments)) {
                echo '<div class="comments-container w3-border-top"><br>';
                foreach ($comments as $comment) {
                  $fullName = '';
                  try {
                    $stmt = $conn->prepare("SELECT fname, Lname FROM users WHERE userid = ?");
                    $stmt->execute([$comment['userid']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $fullName = $user ? $user['fname'] . ' ' . $user['Lname'] : '';
                  } catch (PDOException $e) {
                    // Handle database error if needed
                  }

                  $displayName = !empty($fullName) ? htmlspecialchars($fullName) : (!empty($comment['name']) ? htmlspecialchars($comment['name']) : 'Unknown User');
                  echo '<hr class="mb-4">';
                  echo '<div class="comment">';
                  echo '<div class="text">';
                  echo '<div class="fw-bold">' . $displayName . '</div>';
                  echo '<div class="mb-4">' . htmlspecialchars($comment['comment']) . '</div>';
                  echo '</div>';
                  echo '</div>';
                }
                echo '</div>';
              } else {
                echo '<p>No comments found for this product.</p>';
              }
            ?>
          </div>
        </div>
  <?php
            } else {
              echo "No product found for ID: $id";
            }
          } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
          }
        } else {
          echo "Product ID is not set in the URL.";
        }
  ?>
      </div>
      </div>
    </main>

    <footer id="footer" class="footer position-relative">
      <div class="container footer-top">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.php" class="logo d-flex align-items-center">
              <span class="sitename">LFPMS</span>
            </a>
            <div class="footer-contact pt-3">
              <p>Luna St, La Paz, </p>
              <p>Iloilo City, 5000 Iloilo</p>
              <p class="mt-3"><strong>Phone:</strong> <span>Under Development</span></p>
              <p><strong>Email:</strong> <span>lfpms@wvsu.edu.ph</span></p>
            </div>
            <div class="social-links d-flex mt-4 mb-2">
              <a href="#"><i class="bi bi-twitter"></i></a>
              <a href="#"><i class="bi bi-facebook"></i></a>
              <a href="#"><i class="bi bi-instagram"></i></a>
              <a href="#"><i class="bi bi-linkedin"></i></a>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="index.php#about">About us</a></li>
              <li><a href="index.php#services">Services</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="container copyright text-center">
        <p>© <span>Copyright</span> <strong class="px-1 sitename">Local Food Product Management System</strong><br><span style="font-weight: bold;">All Rights Reserved | LFPMS</span></p>
      </div>
    </footer>

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js" integrity="sha512-V4Nl5TzIik6bcXYkg94xE4DBU3zo9rlU3uxzQsnnNmSaMPg7zNwZyyQZkSwf6cnSw7e0mnxfPpHJeU5oymL/Mg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>

  </body>

  </html>