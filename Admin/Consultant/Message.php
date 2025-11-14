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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
  <!-- Bootstrap Select JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

</head>

<body class="with-welcome-text">
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php include 'importantinclude/topbar.php'; ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php include 'importantinclude/sidebar.php'; ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="home-tab">
              <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true"> Inbox</a>
                  </li>
                </ul>
              </div>
              <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                  <div class="row">
                    <div class="col-sm-12">
                    </div>
                  </div>

                  <div class="modal fade" id="createRoomModal" tabindex="-1" role="dialog" aria-labelledby="createRoomModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="createRoomModalLabel">Create a Chat Room and Add Members</h5>
                          <button type="button" class="btn btn-none" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form action="processRoom.php" method="POST">
                            <div class="form-group">
                              <label for="roomName">Room Name</label>
                              <input type="text" class="form-control" id="roomName" name="room_name" required>
                            </div>
                            <div class="form-group">
                              <label for="members">Select Member</label>
                              <select id="members" name="member" class="selectpicker form-control" required data-live-search="true" data-actions-box="true" data-selected-text-format="count>3">
                                <?php
                                // Fetch users with usertype 2 or 0
                                $stmt = $conn->prepare("SELECT userid, username FROM users WHERE usertype IN (2)");
                                $stmt->execute();
                                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($users as $user): ?>
                                  <option value="<?= htmlspecialchars($user['userid']) ?>"><?= htmlspecialchars($user['username']) ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                            <button type="submit" class="btn btn-primary text-white">Create Room and Add Member</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 mb-4">
                    <div class="card">
                      <div class="card-body">
                      <h4>Messages</h4>
                      <hr>
                        <div class="btn-go text-end">
                          <button class="btn btn-primary mb-3 text-white" data-toggle="modal" data-target="#createRoomModal">Create Room and Add Members</button>
                          <a class="btn btn-danger mb-3 text-white" href="MessageArchive.php">Archive Message</a>
                        </div>
                        <?php
                        // Assuming $Id contains the current logged-in user's ID
                        $Id = $user_info['userid'];

                        // Fetch rooms where the current user is a member, r.status is 0, and get member list excluding admins
                        $stmt = $conn->prepare("
                            SELECT r.id, r.name, r.created_at,
                                GROUP_CONCAT(CASE WHEN u.usertype != 0 THEN u.username END ORDER BY u.username SEPARATOR ', ') AS members,
                                MAX(CASE WHEN u.usertype = 0 THEN 1 ELSE 0 END) AS has_admin
                            FROM room r
                            LEFT JOIN chatmember rm ON r.id = rm.room_id
                            LEFT JOIN users u ON rm.user_id = u.userid
                            WHERE r.status = 0
                            AND r.id IN (
                                SELECT room_id FROM chatmember WHERE user_id = :current_user
                            )
                            GROUP BY r.id
                            ORDER BY members ASC
                        ");
                        $stmt->bindParam(':current_user', $Id);
                        $stmt->execute();
                        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Group rooms by unique members (ignoring admins) and separate main rooms
                        $groupedRooms = [];
                        foreach ($rooms as $room) {
                          $membersKey = $room['members'];
                          if ($room['has_admin']) {
                            $groupedRooms[$membersKey]['main'][] = $room;  // Mark room as main if it has an admin
                          } else {
                            $groupedRooms[$membersKey]['regular'][] = $room;
                          }
                        }
                        ?>

                        <table id="room_monitoring" class="table table-border">
                          <thead>
                            <tr>
                              <th>Members</th>
                              <th>Created At</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($groupedRooms as $members => $roomTypes): ?>
                              <tr>
                                <td><?= htmlspecialchars($members) ?></td>
                                <td><?= htmlspecialchars($roomTypes['main'][0]['created_at'] ?? $roomTypes['regular'][0]['created_at']) ?></td>
                                <td>
                                  <div class="dropdown text-center">
                                    <button class="btn btn-primary dropdown-toggle text-white" type="button" data-toggle="dropdown">
                                      Select Room
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                      <?php if (!empty($roomTypes['main'])): ?>
                                        <?php foreach ($roomTypes['main'] as $mainRoom): ?>
                                          <div class="dropdown-item d-flex justify-content-between align-items-center">
                                            <a class="btn btn-primary text-white w-100" href="chat.php?room_id=<?= htmlspecialchars($mainRoom['id']) ?>">
                                              Room with Admin: "<?= htmlspecialchars($mainRoom['name']) ?> ""
                                            </a>
                                            <button class="btn btn-danger text-white w-100" onclick="deactivateRoom(<?= htmlspecialchars($mainRoom['id']) ?>)">
                                            End & Submit Report
                                            </button>
                                          </div>
                                        <?php endforeach; ?>
                                      <?php endif; ?>
                                      <?php if (!empty($roomTypes['regular'])): ?>
                                        <?php foreach ($roomTypes['regular'] as $regularRoom): ?>
                                          <div class="dropdown-item d-flex justify-content-between align-items-center">
                                            <a class="btn btn-primary text-white w-100" href="chat.php?room_id=<?= htmlspecialchars($regularRoom['id']) ?>">
                                              Room: <?= htmlspecialchars($regularRoom['name']) ?>
                                            </a>
                                            <button class="btn btn-danger text-white w-100" onclick="deactivateRoom(<?= htmlspecialchars($regularRoom['id']) ?>)">
                                              End & Submit Report
                                            </button>
                                          </div>
                                        <?php endforeach; ?>
                                      <?php endif; ?>
                                    </div>
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
            $('#room_monitoring').DataTable();

            // Fetch unread message counts and update badges
            $.ajax({
              url: 'fetch_unread_messages.php', // Ensure this endpoint returns the unread message counts
              method: 'GET',
              success: function(response) {
                var data = JSON.parse(response);
                var messages = data.messages;

                console.log(messages); // Debugging: Check the messages data

                messages.forEach(function(message) {
                  var badge = $('#unread-badge-' + message.room_id);
                  if (message.unread_count > 0) {
                    badge.text(message.unread_count); // Update the badge text with the unread count
                    badge.show();
                  } else {
                    badge.hide();
                  }
                });
              },
              error: function(xhr, status, error) {
                console.error('Error fetching unread message counts:', error);
              }
            });
          });
        </script>
        <script>
          $(document).ready(function() {
            $('.selectpicker').selectpicker({
              style: 'btn-info',
              size: 4
            });
          });

          function deactivateRoom(roomId) {
            if (confirm("Are you sure you want to finish the consultation?")) {
              $.ajax({
                url: 'deactivateRoom.php',
                type: 'POST',
                data: {
                  room_id: roomId
                },
                success: function(response) {
                  var data = JSON.parse(response);
                  if (data.success) {
                    location.reload(); // Reload the page to reflect the update
                  } else {
                    alert(data.message); // Show the error message if failure
                  }
                },
                error: function() {
                  alert("An error occurred while updating the room status.");
                }
              });
            }
          }
        </script>
</body>

</html>