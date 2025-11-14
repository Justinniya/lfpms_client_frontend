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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>LFPMS</title>
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="shortcut icon" href="assets/img/bb.png" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'importantinclude/topbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'importantinclude/sidebar.php'; ?>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a style="margin-left:5px; margin-top: -35px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview of Ongoing Consultation</a>
                    </li>
                  </ul>
                </div>
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                      <div class="col-lg-12 grid-margin stretch-card">
                        <div class="card">                      
                          <div class="card-body">
                          <h4>List Of Ongoing Consultation</h4>
                          <hr>
                            <table id="room_monitoring" class="table table-border">
                              <thead>
                                <tr>
                                  <th>Room Name</th>
                                  <th>MSME Name</th>
                                  <th>Completion Date</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                // Get current user ID from session
                                $Id = $_SESSION['id']; // Adjust this if your session key is different

                                // Combined SQL query to fetch data filtered by the current user ID
                                $stmt = $conn->prepare("
                                  SELECT 
                                      r.id AS room_id, 
                                      r.name AS room_name, 
                                      r.created_at, 
                                      r.Con_status,
                                      rm.user_id,
                                      u.fname, 
                                      u.Lname, 
                                      u.usertype 
                                  FROM 
                                      room r
                                  JOIN 
                                      chatmember rm ON r.id = rm.room_id
                                  JOIN 
                                      users u ON rm.user_id = u.userid
                                  WHERE 
                                      r.status = 1 AND Con_status = 0
                                      AND rm.room_id IN (
                                          SELECT rm2.room_id 
                                          FROM chatmember rm2
                                          JOIN users u2 ON rm2.user_id = u2.userid
                                          WHERE u2.usertype = 0
                                      )
                                      AND rm.room_id IN (
                                          SELECT room_id 
                                          FROM chatmember 
                                          WHERE user_id = :current_user_id
                                      )
                                  ORDER BY 
                                      r.id ASC
                                ");
                                $stmt->bindParam(':current_user_id', $Id);
                                $stmt->execute();
                                $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Group data by room
                                $roomData = [];
                                foreach ($rooms as $row) {
                                  if (!isset($roomData[$row['room_id']])) {
                                    $roomData[$row['room_id']] = [
                                      'room_id' => $row['room_id'],
                                      'room_name' => $row['room_name'],
                                      'created_at' => $row['created_at'],
                                      'msme_name' => null,
                                      'msme_id' => null, // Store MSME user_id
                                      'assisted_by' => null,
                                      'consultant_id' => null, // Store Consultant user_id
                                    ];
                                  }

                                  // Check user type and populate the relevant fields
                                  if ($row['usertype'] == 2) {
                                    // If it's an MSME and not the current user, assign MSME user_id
                                    if ($row['user_id'] != $Id) {
                                      $roomData[$row['room_id']]['msme_name'] = $row['fname'] . ' ' . $row['Lname'];
                                      $roomData[$row['room_id']]['msme_id'] = $row['user_id']; // Capture other MSME user_id
                                    }
                                  } elseif ($row['usertype'] == 3) {
                                    // If it's a Consultant and not the current user
                                    if ($row['user_id'] != $Id) {
                                      $roomData[$row['room_id']]['assisted_by'] = $row['fname'] . ' ' . $row['Lname'];
                                      $roomData[$row['room_id']]['consultant_id'] = $row['user_id']; // Capture other Consultant user_id
                                    }
                                  }
                                }

                                // Display the combined data
                                foreach ($roomData as $room): ?>
                                  <tr>
                                    <td><?= htmlspecialchars($room['room_name']) ?></td>
                                    <td><?= htmlspecialchars($room['msme_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($room['created_at']) ?></td>
                                    <td>
                                      <?php
                                      // Choose the other user's ID (not the current user)
                                      $user_id_to_pass = $room['consultant_id'] ?? $room['msme_id'] ?? null;
                                      // Get the room_id from the $room array
                                      $room_id = $room['room_id'] ?? null;
                                      ?>
                                      <div class="text-center">
                                        <a style="color:white;" href="Conreport.php?user_id=<?= htmlspecialchars($user_id_to_pass) ?>&room_id=<?= htmlspecialchars($room_id) ?>" class="btn btn-primary">
                                          Create Report
                                        </a>
                                      </div>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include 'importantinclude/footer.php'; ?>
      </div>
    </div>
  </div>
  <?php include 'script.php'; ?>
  <script>
    $(document).ready(function() {
      $('#room_monitoring').DataTable({
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