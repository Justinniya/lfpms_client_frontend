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
    <style>
/* === Chat Layout === */
.chat-container {
  display: flex;
  flex-direction: column;
  height: 80vh;
  border: 1px solid #ccc;
  border-radius: 10px;
  overflow: hidden;
  background-color: #fefefe;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* === Header === */
.chat-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  background-color: #f8f9fa;
  border-bottom: 1px solid #ddd;
}

.chat-title {
  font-size: 1.2em;
  font-weight: 600;
  color: #333;
}

.chat-members {
  font-size: 0.9em;
  color: #666;
}

/* === Chat Window === */
.chat-window {
  flex: 1;
  padding: 15px;
  overflow-y: auto;
  background-color: #ffffff;
  display: flex;
  flex-direction: column;
}

/* === Messages === */
.chat-message {
  position: relative;
  margin: 8px 0;
  padding: 10px 14px;
  border-radius: 12px;
  max-width: 75%;
  word-wrap: break-word;
  background-color: #f1f1f1;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.chat-message {
  margin: 8px 0;
  padding: 10px 14px 20px; /* extra bottom padding for spacing */
  border-radius: 12px;
  max-width: 75%;
  word-wrap: break-word;
  background-color: #f1f1f1;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.chat-message.you {
  background-color: #d1e7ff;
  align-self: flex-end;
}

.chat-message strong {
  display: block;
  font-weight: 600;
  color: #333;
  margin-bottom: 4px;
}

.chat-message img {
  max-width: 180px;
  border-radius: 8px;
  margin-top: 8px;
}

.chat-message a {
  color: #007bff;
  text-decoration: none;
}

.chat-message a:hover {
  text-decoration: underline;
}

/* === Timestamp (Bottom Right of Card) === */
.chat-message .timestamp {
  display: block;
  text-align: right;
  font-size: 0.75em;
  color: #666;
  margin-top: 4px;
  opacity: 0.8;
}

/* === Footer === */
.chat-footer {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 15px;
  background-color: #f8f9fa;
  border-top: 1px solid #ddd;
}

.chat-footer input[type="text"] {
  flex: 1;
  border-radius: 8px;
  border: 1px solid #ccc;
  padding: 8px 10px;
  outline: none;
  transition: 0.2s;
}

.chat-footer input[type="text"]:focus {
  border-color: #007bff;
}

/* === Preview Box === */
#preview {
  display: none;
  margin: 10px;
  padding: 10px;
  background-color: #f8f9fa;
  border: 1px solid #ddd;
  border-radius: 10px;
  max-width: 280px;
  min-height: 90px;
  position: relative;
  align-self: flex-start;
}

#preview img {
  max-width: 100%;
  max-height: 160px;
  border-radius: 8px;
  display: block;
  margin: 0 auto;
}

#preview p {
  margin: 0;
  font-size: 0.9em;
  color: #333;
  word-break: break-all;
  text-align: center;
}

/* Remove Preview Button */
#preview .remove-preview {
  position: absolute;
  top: 6px;
  right: 6px;
  background: #dc3545;
  color: #fff;
  border: none;
  border-radius: 50%;
  width: 22px;
  height: 22px;
  line-height: 20px;
  font-size: 12px;
  text-align: center;
  cursor: pointer;
  transition: 0.2s;
}

#preview .remove-preview:hover {
  background: #b52a37;
}
.chat-message.other {
  background-color: #f1f1f1;
  align-self: flex-start;
}

.chat-message.you {
  background-color: #d1e7ff;
  align-self: flex-end;
  text-align: right;
}
    </style>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>LFPMS</title>
    <link rel="shortcut icon" href="assets/img/bb.png" />
    <?php include 'assets/chat.php'; ?>
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
                                            <a style="margin-left:5px; margin-top: -35px;  " class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Messages</a>
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
                                            <div class="ml-auto btn-go mt-3">
                                                <a href="https://meet.google.com/landing" target="blank" class="btn btn-success btn-sm text-white">Open Gmeet</a>
                                                <a href="Message.php" class="btn btn-primary btn-sm text-white">Go back</a>
                                            </div>
                                        </div>
                                        <div class="chat-window" id="chat-window">
                                            <?php foreach ($messages as $message): ?>
                                                <?php 
                                                    $isYou = $message['user_id'] == $_SESSION['id'];
                                                    $messageClass = $isYou ? 'chat-message you' : 'chat-message other';
                                                ?>
                                                <div class="<?= $messageClass ?>">
                                                    <strong><?= $isYou ? 'You' : htmlspecialchars($message['username']) ?>:</strong>
                                                    <?= nl2br(htmlspecialchars($message['message'])) ?>
                                                    
                                                    


                                                    <?php if ($message['file_path']): ?>
                                                    <br><a href="<?= htmlspecialchars($message['file_path']) ?>" target="_blank"><?= htmlspecialchars(basename($message['file_path'])) ?></a>
                                                    <?php endif; ?>

                                                    <span class="timestamp" data-time="<?= htmlspecialchars($message['created_at']) ?>"></span>

                                                </div>
                                                <?php endforeach; ?>

                                            </div>
                                            <div id="preview"></div>
                                            <div class="chat-footer d-flex align-items-center justify-content-between">
                                                
                                                <input type="file" id="file" hidden>
                                                <input type="text" class="form-control" id="message" placeholder="Enter new message" style="flex-grow: 1;">
                                                <div class="d-flex">
                                                <button id="uploadButton" class="btn btn-success m-2 text-white">Upload File</button>
                                                <button id="send" class="btn btn-primary m-2 text-white">Send</button>
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
            <?php include 'script.php'; ?>
        </div>

        <script>
  document.querySelectorAll('.timestamp').forEach(el => {
    // Get the original time
    const serverTime = el.dataset.time.replace(' ', 'T')
    const date = new Date(serverTime)
     const chatWindow = $('#chat-window')
    chatWindow.scrollTop(chatWindow[0].scrollHeight)


    // Format the date
    const options = {
      month: 'short',   
      day: '2-digit',   
      year: 'numeric',
      hour: '2-digit',  
      minute: '2-digit',
      hour12: true
    }

    el.textContent = date.toLocaleString('en-US', options)
  })
</script>
<script>
$(document).ready(function() {
  let selectedFile = null;

  // Upload button opens file picker
  $('#uploadButton').click(() => $('#file').click());

  // File preview
  $('#file').change(function() {
    selectedFile = this.files[0];
    const preview = $('#preview');
    preview.empty();

    if (selectedFile) {
      if (selectedFile.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.html(`<img src="${e.target.result}" alt="Preview">`);
        };
        reader.readAsDataURL(selectedFile);
      } else {
        preview.html(`<p><i class="fa fa-file"></i> ${selectedFile.name}</p>`);
      }
      preview.show();
    } else {
      preview.hide();
    }
  });

  // Send message via Flask backend
  $('#send').click(function() {
    const messageInput = $('#message');
    const message = messageInput.val().trim();
    const roomId = <?= json_encode($roomId) ?>;
    const userId = <?= json_encode($_SESSION['id']) ?>;
    const formData = new FormData();

    if (!message && !selectedFile) {
      alert('Please enter a message or upload a file.');
      return;
    }

    formData.append('room_id', roomId);
    formData.append('message', message);
    formData.append('user_id', userId);
    if (selectedFile) formData.append('file', selectedFile);

    $.ajax({
      url: 'http://localhost:5000/msme/add_message',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        try {
          const data = typeof response === 'string' ? JSON.parse(response) : response;

          // Handle file display
          let filePart = '';
          if (data.file) {
            if (/\.(jpg|jpeg|png|gif)$/i.test(data.file)) {
              filePart =  `<br><a href="${data.file}" target="_blank">${htmlspecialchars(basename(data.file))}</a>`;
            } else {
              filePart = `<br><a href="${data.file}" target="_blank">${data.file.split('/').pop()}</a>`;
            }
          }

          // Format new message (same as existing)
          const newMessage = `
            <div class="chat-message you">
              <strong>You:</strong> ${data.message || ''}
              ${filePart}
              <span class="timestamp">${data.created_at || new Date().toLocaleString()}</span>
            </div>
          `;

          $('#chat-window').append(newMessage);
          $('#chat-window').scrollTop($('#chat-window')[0].scrollHeight);

          // Reset inputs
          messageInput.val('');
          $('#file').val('');
          $('#preview').hide();
          selectedFile = null;
        } catch (err) {
          console.error('Response parse error:', err);
        }
      },
      error: function(xhr) {
        console.error('Error:', xhr.responseText);
        alert('Error sending message. Please check your Flask backend.');
      }
    });
  });
});
</script>
</body>

</html>