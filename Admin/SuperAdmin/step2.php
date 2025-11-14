<?php
session_start();
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit();
}
include '../../session.php';

// Fetch logged-in user details
function getLoggedInUser($conn, $user_id)
{
  $sql = "SELECT * FROM users WHERE userid = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$user_id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);

if (isset($_GET['id'])) {
  $MSME_id = $_GET['id'];
  $id = $_GET['feedid'];

  $query = "Select * from consultancyquestionnaire WHERE id = :id";
  $statement = $conn->prepare($query);
  $statement->bindParam(':id', $id, PDO::PARAM_INT);
  $statement->execute();
  $Data = $statement->fetch(PDO::FETCH_ASSOC);

  if ($Data) {
    $selectedSize = $Data['Size'];
  } else {
    $selectedSize = null;
  }
} else {
  $selectedSize = null;
}
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
  <style>
    .custom-table th,
    .custom-table td {
      text-align: left;
      vertical-align: middle;
    }

    .custom-table .btn-group {
      display: flex;
      gap: 5px;
    }

    .custom-table .btn-sm {
      padding: 5px 10px;
    }
  </style>
</head>

<body class="with-welcome-text">
  <div class="container-scroller">
    <?php include 'importantinclude/topbar.php'; ?>
    <div class="container-fluid page-body-wrapper">
      <?php include 'importantinclude/sidebar.php'; ?>
      <div class="main-panel" style="margin-top: -20px;">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">Assessment Stage <span class="mdi mdi-arrow-right thin"> Stage 1 <span class="mdi mdi-arrow-right thin"> Stage 2</span></a>
                    </li>
                  </ul>
                </div>
                <div class="col-lg-12 grid-margin stretch-card mt-3">
                  <div class="card">
                    <div class="card-body">
                      <h3 class="mb-2 pt-4 d-flex flex-column"><b>Stage 2: Communication</b></h3>
                      <p><b>Status:</b> Establishing Communication</p>
                      <hr>
                      <?php
                      // Assuming $Id contains the current logged-in user's ID

                      // Fetch rooms where both the current user and the other user are members and their members' names
                      $stmt = $conn->prepare("
                    SELECT r.id, r.name, r.created_at, GROUP_CONCAT(u.username SEPARATOR ', ') AS members, r.status
                    FROM room r
                    LEFT JOIN chatmember rm ON r.id = rm.room_id
                    LEFT JOIN users u ON rm.user_id = u.userid
                    WHERE r.status = 0
                    AND r.id IN (
                        SELECT room_id FROM chatmember WHERE user_id = :current_user
                        AND room_id IN (SELECT room_id FROM chatmember WHERE user_id = :other_user)
                    )
                    GROUP BY r.id
                    ORDER BY r.id ASC
                ");
                      $stmt->bindParam(':current_user', $Id);
                      $stmt->bindParam(':other_user', $MSME_id);
                      $stmt->execute();
                      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                      ?>

                      <!-- Modal -->
                      <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="createRoomModalLabel">Create a Chat Room and Add Members</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form id="createRoomForm" action="AdminprocessRoom.php" method="POST">
                                <div class="form-group">
                                  <label for="roomName">Room Name</label>
                                  <input type="text" class="form-control" id="roomName" name="room_name" required>
                                </div>
                                <div class="form-group">
                                  <?php
                                  // Ensure $MSME_id is set from $_GET['id']
                                  if (isset($MSME_id)) {
                                    $stmt = $conn->prepare("SELECT userid, username FROM users WHERE usertype = 2 AND userid = :msme_id");
                                    $stmt->bindParam(':msme_id', $MSME_id, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if ($user):
                                  ?>
                                      <label for="msmeMember">MSME Member</label>
                                      <input type="hidden" name="msme_member" value="<?= htmlspecialchars($user['userid']) ?>">
                                      <p><b>MSME Name: </b><?= htmlspecialchars($user['username']) ?></p>
                                  <?php
                                    else:
                                      echo "<p>MSME member not found.</p>";
                                    endif;
                                  } else {
                                    echo "<p>MSME ID is not set.</p>";
                                  }
                                  ?>
                                </div>
                                <div class="form-group">
                                  <label for="consultantMembers">Select Consultant Member</label>
                                  <select style="color:black" id="consultantMembers" name="consultant_member" class="form-control" required>
                                    <?php
                                    $stmt = $conn->prepare("SELECT userid, username FROM users WHERE usertype = 3");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($users as $user): ?>
                                      <option value="<?= htmlspecialchars($user['userid']) ?>"><?= htmlspecialchars($user['username']) ?></option>
                                    <?php endforeach; ?>
                                  </select>
                                </div>
                                <div class="text-center">
                                  <button type="submit" class="btn btn-primary text-white">Create Room and Add Members</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>

                      <script>
                        document.getElementById('createRoomForm').addEventListener('submit', function() {
                          document.getElementById('approveButton').disabled = false;
                          document.getElementById('approveButton').classList.remove('btn-secondary');
                          document.getElementById('approveButton').classList.add('btn-primary');
                        });
                      </script>


                      <div class="">
                        <div class="card">
                          <div class="card-body">
                            <div class="btn-go text-end">
                              <button class="btn btn-primary mb-3 text-white"
                                data-bs-toggle="modal"
                                data-bs-target="#createRoomModal"
                                <?php if (count($rooms) > 0) echo 'disabled'; ?>>
                                Create Room and Add Members
                              </button>
                            </div>

                            <table id="room_monitoring" class="table custom-table w-100">
                              <thead>
                                <tr>
                                  <th>Room Name</th>
                                  <th>Members</th>
                                  <th>Created At</th>
                                  <th class="text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if (count($rooms) > 0): ?>
                                  <?php foreach ($rooms as $room): ?>
                                    <tr>
                                      <td><?= htmlspecialchars($room['name']) ?></td>
                                      <td><?= htmlspecialchars($room['members']) ?></td>
                                      <td><?= htmlspecialchars($room['created_at']) ?></td>
                                      <td>
                                        <div class="text-center">
                                          <a href="chat.php?room_id=<?= htmlspecialchars($room['id']) ?>" class="btn btn-primary text-white">Open Room</a>
                                          <!-- <button class="btn btn-success" onclick="deactivateRoom(<?= htmlspecialchars($room['id']) ?>)">Consultation Report</button> -->
                                        </div>
                                      </td>
                                    </tr>
                                  <?php endforeach; ?>
                                <?php else: ?>
                                  <tr>
                                    <td colspan="4" class="text-center">No room found</td>
                                  </tr>
                                <?php endif; ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <br>
                        <hr class="mb-4">
                        <div class="container">
                          <form method="POST" action="review_Assessment.php">
                            <div class="text-center">
                              <label class="mb-1" for="decision"><span style="font-weight: bold;">Stage Completion:</span> After Creating the room click the Proceed Button to countinue in the next Stage.</label><br>
                              <input name="user_id" type="hidden" value="<?php echo htmlspecialchars($Data['user_id']); ?>">

                              <?php
                              $disableApprove = true;  // Default: Disabled
                              $buttonClass = "btn btn-secondary"; // Gray out the button

                              // Check if there is a room for this MSME that is closed (status = 1)
                              foreach ($rooms as $room) {
                                if ($room['status'] == 0) {
                                  $disableApprove = false; // Enable the button
                                  $buttonClass = "btn btn-primary";
                                  $closedRoomId = $room['id']; // Capture the room ID
                                  break; // Stop checking after the first closed room
                                }
                              }
                              ?>
                              <button class="btn btn-primary text-white" id="approveButton" type="submit" name="decision" class="<?php echo $buttonClass; ?>" value="3" <?php echo $disableApprove ? 'disabled' : ''; ?>>
                                Proceed
                              </button>
                            </div>
                          </form>
                          <br>
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

    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../assets/vendors/chart.js/chart.umd.js"></script>
    <script src="../assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/template.js"></script>
    <script src="../assets/js/settings.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/todolist.js"></script>
    <script src="../assets/js/jquery.cookie.js" type="text/javascript"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#feedback_monitoring').DataTable({
          responsive: true,
          autoWidth: false
        });
      });
    </script>

</body>

</html>