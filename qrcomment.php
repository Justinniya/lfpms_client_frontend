<?php
include "includes/dbcon.php";
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

    .tag-btn {
      display: inline-block;
      background-color: #007bff;
      /* Blue background */
      color: white;
      /* White text */
      border: none;
      padding: 8px 12px;
      margin: 5px;
      font-size: 14px;
      border-radius: 20px;
      /* Rounded corners */
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .tag-btn:hover {
      background-color: #0056b3;
      /* Darker blue on hover */
    }

    .tag-btn:active {
      transform: scale(0.95);
      /* Slight shrink effect when clicked */
    }

    #tag-container {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background: #f8f9fa;
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
          <li><a href="index.php#hero">Home</a></li>
          <li><a href="index.php#about">About</a></li>
          <li><a href="index.php#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <main class="main" style="margin-top:100px;">
    <?php
    include './includes/dbcon.php';

    if (isset($_GET['Id']) && isset($_GET['TransactionId'])) {
      $product_id = $_GET['Id'];
      $transaction_id = $_GET['TransactionId'];

      try {
        $query = "SELECT * FROM `products` INNER JOIN users ON users.userid = products.msme_id WHERE product_id = :id";
        $statement = $conn->prepare($query);
        $statement->bindParam(':id', $product_id, PDO::PARAM_INT);
        $statement->execute();
        $Data = $statement->fetch(PDO::FETCH_ASSOC);

        if ($Data) {
          $checkScanStmt = $conn->prepare("SELECT COUNT(*) FROM `qr_scans` WHERE `transaction_id` = :transaction_id AND `ReviewStat` = 1");
          $checkScanStmt->bindParam(':transaction_id', $transaction_id, PDO::PARAM_STR);
          $checkScanStmt->execute();
          $scanCount = $checkScanStmt->fetchColumn();
    ?>
          <div class="container my-5">
            <div class="row shadow-sm border rounded">
              <div class="col-md-2">

              </div>
              <div class="row">
                <div class="col-md-4 w3-white p-4 text-start">

                  <img style="height:250px !important; display: block; margin: 0 auto;" class="img-fluid rounded" src="admin/uploaded_img/<?php echo $Data['productImage']; ?>" alt="Product Image">

                </div>
                <div class="col-md-8 mt-4">
                  <a style="float: right;" href="index.php">Back Home</a>
                  <h2 class="fw-bold"><?php echo $Data['productName']; ?></h2>
                  <h3 class="text-success">&#8369;<?php echo $Data['productPrice']; ?></h3>
                  <div class="mt-3"><strong>Seller:</strong> <?php echo $Data['fname']; ?> <?php echo $Data['Lname']; ?></div>
                  <div><strong>Address:</strong> <?php echo $Data['address']; ?></div>
                  <div><strong>Product Detail:</strong> <?php echo $Data['productDetails']; ?></div>
                </div>
              </div>
              <div class="col-md-2">

              </div>

              <div class="col-md-12 p-4 w3-white">
                <h4 class="text-left">Leave a Comment / Feedback about the Product</h4>
                <div class="comment-box">
                  <form action="insert_comment.php" method="post" enctype="multipart/form-data">
                    <label for="name">Name (Optional)</label>
                    <input type="text" name="name" id="name" value="">
                    <label for="mail">Email (Optional)</label>
                    <input type="text" name="mail" id="mail" value="">
                    <hr>
                    <input type="hidden" name="product_id" id="product_id" value="<?php echo $Data['product_id']; ?>">
                    <input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo $transaction_id; ?>">
                    <input type="hidden" name="msme" id="msme" value="<?php echo $Data['fname']; ?> <?php echo $Data['Lname']; ?>">
                    <input type="hidden" name="productName" id="productName" value="<?php echo $Data['productName']; ?>">
                    <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['id']; ?>">
                    <div id="tag-container" class="mb-2"><b>Tag:<br></b></div>
                    <textarea name="comment" id="comment-textarea" class="form-control" placeholder="Write your comment" required></textarea>

                    <div class="text-end">
                      <?php if ($scanCount > 0) { ?>
                        <p class="m-2">
                          <button type="submit" class="btn btn-primary" disabled>Submit</button>
                        <p class="text-danger mt-2">You have already submitted feedback for this product.</p>
                      <?php } else { ?>
                        <p class="m-2">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        <?php } ?>
                    </div>
                  </form>
                </div>

                <script>
                  function addTag(tagName) {
                    var commentTextarea = document.getElementById("comment-textarea");
                    commentTextarea.value += tagName + " ";
                  }

                  function removeTag(tagElement) {
                    tagElement.parentNode.removeChild(tagElement);
                  }

                  document.addEventListener("click", function(event) {
                    if (event.target.classList.contains("tag-btn")) {
                      addTag(event.target.textContent);
                    }
                  });

                  function createTagButton(tagName) {
                    var tagContainer = document.getElementById("tag-container");
                    var tagButton = document.createElement("button");
                    tagButton.textContent = tagName;
                    tagButton.classList.add("tag-btn");
                    tagButton.setAttribute("type", "button");
                    tagButton.setAttribute("onclick", "removeTag(this)");
                    tagContainer.appendChild(tagButton);

                    var space = document.createTextNode(" ");
                    tagContainer.appendChild(space);
                  }

                  createTagButton("Design");
                  createTagButton("Taste");
                  createTagButton("Quality");
                  createTagButton("Label");
                  createTagButton("Package");
                  createTagButton("Branding");
                  createTagButton("Logo");
                  createTagButton("Flavor");
                </script>

                <?php
                function getCommentsForProduct($id)
                {
                  global $conn;

                  try {
                    $stmt = $conn->prepare("SELECT * FROM feedback WHERE product_id = ?");
                    $stmt->execute([$id]);
                    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $comments;
                  } catch (PDOException $e) {
                    return array();
                  }
                }

                $comments = getCommentsForProduct($product_id);

                if (!empty($comments)) {
                  echo '<h5><b>Comments</h5></b><div class="comments-container w3-border-top"><br>';
                  foreach ($comments as $comment) {
                    $fullName = '';

                    try {
                      $stmt = $conn->prepare("SELECT fname, Lname FROM users WHERE userid = ?");
                      $stmt->execute([$comment['userid']]);
                      $user = $stmt->fetch(PDO::FETCH_ASSOC);
                      $fullName = $user ? $user['fname'] . ' ' . $user['Lname'] : '';
                    } catch (PDOException $e) {
                    }

                    $displayName = !empty($fullName) ? htmlspecialchars($fullName) : (!empty($comment['name']) ? htmlspecialchars($comment['name']) : 'Unknown User');

                    echo '<div class="comment">';
                    echo '<div class="text"><b>' . $displayName . '</b></div>';
                    echo '<div class="text">' . "Date Submitted: " . htmlspecialchars($comment['datesubmitted']) . '<br></div>';
                    echo '<div class="text">' . "Feedback: " . htmlspecialchars($comment['comment']) . '<br></div>';
                    echo '</div><hr>';
                  }
                  echo '</div>';
                } else {
                  echo '<p>No comments found for this product.</p>';
                }
                ?>

              </div>
            </div>
          </div>

    <?php
        } else {
          echo "No product found for ID: $product_id";
        }
      } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    } else {
      echo "Product ID or Transaction ID is not set in the URL.";
    }
    ?>
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