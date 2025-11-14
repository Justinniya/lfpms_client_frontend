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
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
    <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <link rel="shortcut icon" href="assets/img/bb.png" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Select JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
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
                        <div class="col-sm-12">
                            <div class="home-tab">
                                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Message Archived Page</a>
                                        </li>
                                    </ul>
                                </div>
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
                            </div>
                            <?php include 'script.php'; ?>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#uploadButton').click(function() {
                                    $('#file').click();
                                });

                                $('#file').change(function() {
                                    const fileName = this.files[0].name;
                                    $('#message').val(fileName);
                                });

                                $('#send').click(function() {
                                    const messageInput = $('#message');
                                    const message = messageInput.val();
                                    const roomId = <?= json_encode($roomId) ?>;
                                    const fileInput = $('#file')[0];
                                    const formData = new FormData();

                                    formData.append('room_id', roomId);
                                    formData.append('message', message);
                                    formData.append('user_id', <?= json_encode($_SESSION['id']) ?>); // Send the user ID
                                    if (fileInput.files.length > 0) {
                                        formData.append('file', fileInput.files[0]);
                                    }

                                    $.ajax({
                                        url: 'sendMessage.php',
                                        type: 'POST',
                                        data: formData,
                                        contentType: false,
                                        processData: false,
                                        success: function(response) {
                                            $('#chat-window').append(`<div>${response}</div>`);
                                            messageInput.val(''); // Clear input
                                            $('#file').val(''); // Clear file input
                                        }
                                    });
                                });

                            });
                        </script>
</body>

</html>