<?php
include "../includes/dbcon.php";
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
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['id'];

// Corrected SQL query
$sql = "SELECT username, email, fname FROM users WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // bind the id as integer
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data if available
if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
  $username = $user['username'];
  $email = $user['email'];
  $fname = $user['fname'];

  // Echo user information
  echo "Welcome, " . htmlspecialchars($fname) . " (" . htmlspecialchars($username) . ")";
  echo "<br>Email: " . htmlspecialchars($email);


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
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="assets/css/main.css" rel="stylesheet">
    <style>
      .card-title,
      .card-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3rem;
        height: 2.6rem;
      }

      .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }

      .product-price {
        color: green;
      }

      .product-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
      }

      .card-footer {
        margin-top: auto;
      }

      .card-footer a {
        width: 100%;
        text-align: center;
      }

      .col-md-5th {
        width: 20%;
        float: left;
      }

      @media (max-width: 768px) {
        .col-md-5th {
          width: 50%;
        }
      }

      @media (max-width: 576px) {
        .col-md-5th {
          width: 100%;
        }
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
      <section id="hero" class="hero section">
        <div class="hero-bg">
          <img src="assets/img/bg12.png" alt="">
        </div>
      </section>

      <section id="service-details" class="service-details section">
        <div class="container">
          <div class="row gy-5">
            <div class="col-lg-12 ps-lg-5" data-aos="fade-up" data-aos-delay="200">
              <div class="card p-4 shadow-lg">
                <div class="ml-2 mt-4 text-center">
                  <h1>My Local Food Product Purchases</h1>
                </div>
                </form>
                <div class="container my-4">
                  <h1 class="text-center font-weight-bold mt-2">MY PURCHASES</h1>
                  <hr>
                  <div class="row">
                    <?php
                    $select_products = $conn->prepare("
                      SELECT 
                          purchases.purchase_id, 
                          purchases.transaction_id, 
                          purchases.product_id, 
                          purchases.quantity AS purchase_quantity, 
                          purchases.total_price AS purchase_total_price,
                          transactions.msme_id, 
                          transactions.user_id, 
                          transactions.transaction_date, 
                          transactions.total_price AS transaction_total_price, 
                          transactions.quantity AS transaction_quantity, 
                          products.productName, 
                          products.productPrice, 
                          products.productImage, 
                          products.productDetails
                      FROM 
                          purchases
                      JOIN 
                          transactions ON purchases.transaction_id = transactions.transaction_id
                      JOIN 
                          products ON purchases.product_id = products.product_id
                      WHERE 
                          transactions.user_id = ?
                  ");
                    $select_products->execute([$_SESSION['id']]);



                    if ($select_products->rowCount() > 0) {
                      while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="col-md-3 mb-4"> <!-- Changed to col-md-3 for 4 columns -->
                          <div class="card h-100">
                            <img src="../Admin/uploaded_img/<?= htmlspecialchars($fetch_products['productImage']); ?>" class="card-img-top" height="250px" alt="Product Image"> <!-- Adjusted image height -->
                            <div class="card-body">
                              <h5 class="card-title"><?= htmlspecialchars($fetch_products['productName']); ?></h5>
                              <p class="card-text text-success font-weight-bold">₱<?= htmlspecialchars($fetch_products['productPrice']); ?></p>
                            </div>
                            <div class="card-footer">
                              <a href="comment.php?id=<?= htmlspecialchars($fetch_products['product_id']); ?>" class="btn btn-primary w-100">View Product</a>
                            </div>
                          </div>
                        </div>
                    <?php
                      }
                    } else {
                      echo '<div class="col-12 text-center"><p class="text-muted">You have not purchased any product yet!</p></div>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>
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
          </div>
          <div class="col-lg-4 col-md-4 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="index.php#about">About us</a></li>
              <li><a href="index.php#services">Services</a></li>
            </ul>
          </div>
          <div class="col-lg-4 col-md-4 footer-links">
            <h4>Follow Us</h4>
            <ul>
              <li><a href="#"><i class="bi bi-twitter"></i> Twitter</a></li>
              <li><a href="#"><i class="bi bi-facebook"></i> Facebook</a></li>
              <li><a href="#"><i class="bi bi-instagram"></i> Instagram</a></li>
              <li><a href="#"><i class="bi bi-linkedin"></i> LinkedIn</a></li>
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
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- Include jQuery Library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Real-Time Search Script -->
    <script>
      $(document).ready(function() {
        $('#searchQuery').on('input', function() {
          let query = $(this).val();

          if (query.length > 0) { // Only send request if query is not empty
            $.ajax({
              url: 'search.php', // PHP file to handle the search query
              method: 'POST',
              data: {
                search_query: query
              },
              success: function(data) {
                $('#searchResults').html(data); // Display the search results
              }
            });
          } else {
            $('#searchResults').html(''); // Clear results if input is empty
          }
        });
      });
    </script>
  </body>
<?php
} else {
  echo "No user found!";
}
?>

  </html>