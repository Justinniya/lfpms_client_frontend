<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("location: ../../index.php");
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
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/bb.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

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
                                            <a style="margin-left:5px; margin-top: -35px;" class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Archived Messages</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                    <div class="container">
                                        <div class="m-4 pb-4 pt-4">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 style="font-size: 30px;" class="card-title">Archived Chats</h5>
                                                    <hr>
                                                    <div class="btn-go text-end">
                                                        <a class="btn btn-primary mb-3 text-white " href="Message.php">Go Back</a>
                                                    </div>

                                                    <?php
                                                    $Id = $loggedInUser['userid']; // Current logged-in user's ID
                                                    $stmt = $conn->prepare("SELECT r.id, r.name, r.created_at, GROUP_CONCAT(u.username SEPARATOR ', ') AS members FROM room r LEFT JOIN chatmember rm ON r.id = rm.room_id LEFT JOIN users u ON rm.user_id = u.userid WHERE r.status = 1 AND r.id IN (SELECT room_id FROM chatmember WHERE user_id = :current_user) GROUP BY r.id ORDER BY r.id ASC");
                                                    $stmt->bindParam(':current_user', $Id);
                                                    $stmt->execute();
                                                    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    ?>

                                                    <table id="dataTable" class="table custom-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Room Name</th>
                                                                <th>Members</th>
                                                                <th>Created At</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($rooms as $room): ?>
                                                                <tr>
                                                                    <td><?= htmlspecialchars($room['name']) ?></td>
                                                                    <td><?= htmlspecialchars($room['members']) ?></td>
                                                                    <td><?= htmlspecialchars($room['created_at']) ?></td>
                                                                    <td class="text-center">
                                                                        <a style="" href="chatArchive.php?room_id=<?= htmlspecialchars($room['id']) ?>" class="btn btn-primary text-white">Open Room</a>
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

                <?php include 'importantinclude/footer.php'; ?>

            </div>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#room_monitoring').DataTable();
        });

</script>

</html>

