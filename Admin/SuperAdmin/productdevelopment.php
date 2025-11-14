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

// Fetch MSME users (usertype = 2)
function getMsmeUsers($conn)
{
  $sql = "SELECT userid, fname, Lname FROM users WHERE usertype = 2";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$loggedInUser = getLoggedInUser($conn, $_SESSION['id']);

$query = "SELECT userid, email, fname, Lname, username, password, usertype, status 
          FROM users 
          WHERE status = 1";

$result = $conn->query($query);
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
                      <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab">Ongoing Product Development</a>
                    </li>
                  </ul>
                </div>

                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row">
                      <div class="col-md-12 mb-4">
                        <div class="card mt-3">
                          <div class="card-body">
                          <h3 class="mb-4">Ongoing Product Development</h3>
                          <hr>
                            <div class="table-responsive">
                              <table id="feedback_monitoring" class="table table-border">
                                <thead>
                                  <tr>
                                    <th>Name</th>
                                    <th>Product Name</th>
                                    <th>Stage</th>
                                    <th>Progess</th>
                                    <th>Submittion Date</th>
                                    <th>
                                      <div class="text-center">
                                        Action
                                      </div>
                                    </th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  // Perform the SQL query to calculate counts and total rating value
                                  $query = "SELECT *, Con.id, Con.status 
                                  FROM consultancyquestionnaire AS Con 
                                  INNER JOIN users ON Con.user_id = users.userid 
                                  WHERE Con.status BETWEEN 1 AND 4 OR Con.status = 44";

                                  // Execute the query and fetch data
                                  $result = $conn->query($query);
                                  if ($result && $result->rowCount() > 0) {
                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                      echo "<tr>";
                                      echo "<td>{$row['fname']} {$row['Lname']} </td>";
                                      echo "<td>{$row['ProductName']}</td>";
                                      if ($row['status'] == 1 || $row['status'] == 0) {
                                        echo "<td>Stage 1</td>";
                                        echo "<td>Progress 10%</td>";
                                        echo "<td>{$row['SubmittionDate']}</td>";
                                        echo '<td><div class="text-center"><a class="btn btn-primary btn-sm text-white" href="ViewAssess.php?id=' . $row['id'] . '">Manage Development</a></td></div>';
                                      } elseif ($row['status'] == 2) {
                                        echo "<td>Stage 2</td>";
                                        echo "<td>Progress 40%</td>";
                                        echo "<td>{$row['SubmittionDate']}</td>";
                                        echo '<td><div class="text-center"><a class="btn btn-primary btn-sm text-white" href="Step2.php?id=' . $row['userid'] . '&feedid=' . $row['id'] . '">Manage Development</a></td></div>';
                                      } elseif ($row['status'] == 3 or $row['status'] == 44) {
                                        echo "<td>Stage 3</td>";
                                        echo "<td>Progress 80%</td>";
                                        echo "<td>{$row['SubmittionDate']}</td>";
                                        echo '<td><div class="text-center"><a class="btn btn-primary btn-sm text-white" href="Step3.php?id=' . $row['userid'] . '&feedid=' . $row['id'] . '">Manage Development</a></td></div>';
                                      } elseif ($row['status'] == 4) {
                                        echo "<td>Stage 4</td>";
                                        echo "<td>Progress 100%</td>";
                                        echo "<td>{$row['SubmittionDate']}</td>";
                                        echo '<td><div class="text-center"><a class="btn btn-primary btn-sm text-white" href="Step4.php?id=' . $row['userid'] . '">Manage Development</a></td></div>';
                                      }

                                      echo "</tr>";
                                    }
                                  }
                                  ?>
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