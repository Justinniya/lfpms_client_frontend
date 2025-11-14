<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit();
}
include '../../session.php';

function getLoggedInUser($conn, $user_id)
{
  $sql = "SELECT * FROM users WHERE userid = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$user_id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);

// Fetch room ID from URL
$roomId = $_GET['room_id'];

try {
  // Fetch room name
  $roomStmt = $conn->prepare("SELECT name FROM room WHERE id = ?");
  $roomStmt->execute([$roomId]);
  $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

  if (!$room) {
    throw new Exception("Room not found.");
  }

  // Fetch messages from the room
  $messageStmt = $conn->prepare("SELECT chat.*, users.username FROM chat INNER JOIN users ON chat.user_id = users.userid WHERE room_id = ? ORDER BY created_at ASC");
  $messageStmt->execute([$roomId]);
  $messages = $messageStmt->fetchAll(PDO::FETCH_ASSOC);

  // Fetch members in the room
  $memberStmt = $conn->prepare("SELECT users.username FROM chatmember INNER JOIN users ON chatmember.user_id = users.userid WHERE chatmember.room_id = ? and usertype != 0");
  $memberStmt->execute([$roomId]);
  $members = $memberStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Database error: " . $e->getMessage();
  exit();
} catch (Exception $e) {
  echo $e->getMessage();
  exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>LFPMS</title>
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="../assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" type="text/css" href="../assets/js/select.dataTables.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="shortcut icon" href="assets/img/bb.png" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <style>
    .chat-container {
      display: flex;
      flex-direction: column;
      height: 80vh;
      border: 1px solid #ccc;
      border-radius: 5px;
      overflow: hidden;
    }

    .chat-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background-color: #f8f9fa;
      border-bottom: 1px solid #ccc;
    }

    .chat-title {
      font-size: 1.2em;
      font-weight: bold;
    }

    .chat-members {
      font-size: 0.9em;
      color: #666;
    }

    .chat-window {
      flex: 1;
      padding: 10px;
      overflow-y: auto;
      background-color: #fff;
    }

    .chat-message {
      margin-bottom: 10px;
      padding: 10px;
      border-radius: 5px;
      background-color: #f1f1f1;
    }

    .chat-message strong {
      display: block;
      margin-bottom: 5px;
    }

    .chat-footer {
      padding: 10px;
      background-color: #f8f9fa;
      border-top: 1px solid #ccc;
      text-align: center;
    }
  </style>
</head>

<body class="with-welcome-text">
  <div class="container-scroller">

    <?php include 'importantinclude/topbar.php'; ?>

    <div class="container-fluid page-body-wrapper">

      <?php include 'importantinclude/sidebar.php'; ?>

      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="home-tab">
              <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true"> Inbox <span class="mdi mdi-arrow-right-thin"></span> Archived Messages <span class="mdi mdi-arrow-right-thin"></span> Message</a>
                  </li>
                </ul>
              </div>
              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-sm-12">

                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                      <div class="chat-container">
                        <div class="chat-header">
                          <div class="chat-title">
                            <strong><?= htmlspecialchars($room['name']) ?></strong>
                            <div class="chat-members">
                              <b>Members</b>:
                              <?php foreach ($members as $member): ?>
                                <?= htmlspecialchars($member['username']) ?>,
                              <?php endforeach; ?>
                            </div>
                          </div>
                          <div class="ml-auto btn-go">
                            <a href="MessageArchive.php" class="btn btn-primary btn-sm text-white">Go back</a>
                          </div>
                        </div>
                        <div class="chat-window" id="chat-window">
                          <?php foreach ($messages as $message): ?>
                            <div class="chat-message">
                              <strong><?= htmlspecialchars($message['username']) ?>:</strong>
                              <?= htmlspecialchars($message['message']) ?>
                              <em>(<?= htmlspecialchars($message['created_at']) ?>)</em>
                              <?php if ($message['file_path']): ?>
                                <br><a href="<?= htmlspecialchars($message['file_path']) ?>" target="_blank"><?= htmlspecialchars(basename($message['file_path'])) ?></a>
                              <?php endif; ?>
                            </div>
                          <?php endforeach; ?>
                        </div>
                        <div class="chat-footer">
                          <p>This is an archive Chat room</p>
                        </div>
                      </div>

                      <?php include 'importantinclude/footer.php'; ?>
                    </div>
                    <!-- main-panel ends -->
                  </div>
                  <!-- page-body-wrapper ends -->
                </div>
                <!-- container-scroller -->
                <!-- plugins:js -->
                <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
                <script src="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
                <!-- endinject -->
                <!-- Plugin js for this page -->
                <script src="../assets/vendors/chart.js/chart.umd.js"></script>
                <script src="../assets/vendors/progressbar.js/progressbar.min.js"></script>
                <!-- End plugin js for this page -->
                <!-- inject:js -->
                <script src="../assets/js/off-canvas.js"></script>
                <script src="../assets/js/template.js"></script>
                <script src="../assets/js/settings.js"></script>
                <script src="../assets/js/hoverable-collapse.js"></script>
                <script src="../assets/js/todolist.js"></script>
                <!-- endinject -->
                <!-- Custom js for this page-->
                <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
                <script src="../assets/js/dashboard.js"></script>
                <!-- <script src="assets/js/Chart.roundedBarCharts.js"></script> -->
                <!-- End custom js for this page-->
                <!-- jQuery (Required for DataTables) -->
                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                <!-- DataTables JS -->
                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

                <script>
                  $(document).ready(function() {
                    $('#dataTable').DataTable({
                      responsive: true,
                      autoWidth: false,
                      lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                      ],
                      language: {
                        search: "🔍 Search:",
                        lengthMenu: "Show _MENU_ entries",
                        paginate: {
                          previous: "←",
                          next: "→"
                        }
                      },
                      drawCallback: function() {
                        $('.dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').css("margin-bottom", "15px");
                      }
                    });
                  });
                </script>

</body>

</html>