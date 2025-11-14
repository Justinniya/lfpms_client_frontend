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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <style>
    .scanner-container {
      max-width: 400px;
      width: 100%;
      padding: 20px;
      background: white;
      border-radius: 12px;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .scanner-box {
      display: block;
      margin: 0 auto;
      border: 2px dashed #6c757d;
      width: 350px !important;
      padding: 20px;
      border-radius: 10px;
      background: #eef2ff;
      margin-top: 15px;
    }

    .result-box {
      margin-top: 15px;
      font-size: 18px;
      font-weight: bold;
      color: green;
    }

    #my-qr-reader {
      padding: 20px;
      border: 1.5px solid #b2b2b2;
      border-radius: 8px;
      background: white;
    }

    button {
      padding: 10px 20px;
      border: none;
      outline: none;
      border-radius: 0.5em;
      color: white;
      font-size: 15px;
      cursor: pointer;
      margin-top: 15px;
      margin-bottom: 10px;
      background-color: #4f46e5;
      transition: 0.3s background-color;
    }

    button:hover {
      background-color: #4338ca;
    }

    video {
            width: 100% !important;
            border: 1px solid #b2b2b2 !important;
            border-radius: 0.25em;
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
          <li><a href="index.php">Home</a></li>
          <li><a href="index.php#about">About</a></li>
          <li><a href="index.php#contact">Contact</a></li>
          <li><a href="scanqr">Scan QR <i style="font-size:20px    ;" class="fa-solid fa-expand"></i></a></li>
          <li><a href="login/login.php">Login</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>


  <div class="container" style="margin-top: 150px !important;">

    <div class="scanner-box text-center">
      <div class="text-center">
        <i class="bi bi-upc-scan" style="font-size: 50px;"></i>
        <h1 class="title text-center">QR Scan Transaction</h1>
        <p class="text-center text-muted">Place your QR code inside the box below to scan</p>
        <div id="my-qr-reader"></div>
      </div>
    </div>
    <div id="scanned-result" class="result-box"></div>

  </div>


  <!-- Include Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Include html5-qrcode library -->
  <script src="html5-qrcode.min.js"></script>
  <script>
    // Function to run when the document is ready
    function domReady(fn) {
      if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(fn, 1000);
      } else {
        document.addEventListener("DOMContentLoaded", fn);
      }
    }

    // Run this function when the DOM is ready
    domReady(function() {
      // If QR code is successfully scanned
      function onScanSuccess(decodeText, decodeResult) {
      console.log(decodeText, decodeResult)
        // Redirect to ScanResult.php with the scanned transaction ID
        window.location.href = `ScanResult.php?Id=${decodeText}`;
      }

      // Initialize the QR code scanner
      let htmlscanner = new Html5QrcodeScanner(
        "my-qr-reader", {
          fps: 10,
          qrbox: 250
        }
      );

      // Render the QR code scanner and pass the callback function
      htmlscanner.render(onScanSuccess);
    });
  </script>



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

</html>