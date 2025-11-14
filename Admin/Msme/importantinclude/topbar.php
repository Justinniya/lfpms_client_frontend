<?php
$current_user = $_SESSION['id'] ?? null; // Ensure user ID is set

if (!$current_user) {
    die("Error: User not logged in.");
}

try {
    $stmt = $conn->prepare("
        SELECT id, 
               CASE 
                   WHEN status = 0 THEN 'Your assessment has been rejected.'
                   WHEN status = 6 THEN 'Your assessment has been approved.'
                   WHEN status = 2 THEN 'A chatroom you were in has been closed by the consultant.'
                   WHEN status = 3 THEN 'Your assessment is pending.'
               END AS message,
               CASE 
                   WHEN status = 0 THEN 'rejected'
                   WHEN status = 6 THEN 'approved'
                   WHEN status = 2 THEN 'chat_closed'
                   WHEN status = 3 THEN 'pending'
               END AS type
        FROM (
            SELECT id, status FROM consultancyquestionnaire WHERE user_id = :user_id AND status IN (0, 3, 6)
            UNION 
            SELECT id, status FROM room WHERE id IN (SELECT room_id FROM chatmember WHERE user_id = :user_id) AND status = 2
        ) AS combined_notifications
        ORDER BY id DESC
    ");
    $stmt->bindParam(':user_id', $current_user, PDO::PARAM_INT);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>


<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="msme_index.php">
                <img src="assets/img/bb.png" alt="">
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <h5>Welcome Msme, <span class="text-black fw-bold"><?= htmlspecialchars($loggedInUser['fname']); ?>!</span></h5>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="icon-bell"></i>
                    <span id="notificationCount" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                        <?= count($notifications) > 0 ? count($notifications) : ''; ?>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                    <div id="notificationList">
                        <?php if (!empty($notifications)) : ?>
                            <?php foreach ($notifications as $notification) : ?>
                                <a class="dropdown-item preview-item py-3 notification-item"
                                    href="#" data-notification-id="<?= $notification['id']; ?>"
                                    data-notification-type="<?= $notification['type']; ?>">
                                    <div class="preview-thumbnail">
                                        <?php if ($notification['type'] === 'rejected') : ?>
                                            <i class="mdi mdi-alert m-auto text-danger"></i>
                                        <?php elseif ($notification['type'] === 'chat_closed') : ?>
                                            <i class="mdi mdi-chat-remove m-auto text-warning"></i>
                                        <?php elseif ($notification['type'] === 'pending') : ?>
                                            <i class="mdi mdi-clock-outline m-auto text-info"></i>
                                        <?php else : ?>
                                            <i class="mdi mdi-check-circle m-auto text-success"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="preview-item-content">
                                        <h6 class="preview-subject fw-normal text-dark mb-1">
                                            <?= $notification['type'] === 'rejected' ? 'Assessment Rejected' : ($notification['type'] === 'chat_closed' ? 'Chatroom Closed' : ($notification['type'] === 'pending' ? 'Pending Assessment' : 'Assessment Approved')); ?>
                                        </h6>
                                        <p class="fw-light small-text mb-0"><?= htmlspecialchars($notification['message']); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <a class="dropdown-item preview-item py-3 text-center">
                                <p class="fw-light small-text mb-0">No new notifications</p>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>


            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    var notificationCount = <?= count($notifications); ?>;
                    if (notificationCount === 0) {
                        $('#notificationCount').hide();
                    } else {
                        $('#notificationCount').show();
                    }

                    $(".notification-item").on("click", function(e) {
                        e.preventDefault();

                        var notificationId = $(this).data("notification-id");
                        var notificationType = $(this).data("notification-type");

                        // Prevent updating if notification type is "pending"
                        if (notificationType === "pending") {
                            return; // Do nothing when clicked
                        }

                        var notificationItem = $(this);

                        if (notificationId) {
                            $.ajax({
                                url: "update_notification_status.php",
                                type: "POST",
                                data: {
                                    notification_id: notificationId,
                                    notification_type: notificationType
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.success) {
                                        notificationItem.fadeOut(500, function() {
                                            $(this).remove();
                                        });

                                        var newCount = $("#notificationList .notification-item").length - 1;
                                        $("#notificationCount").text(newCount > 0 ? newCount : "").toggle(newCount > 0);

                                        setTimeout(function() {
                                            window.location.reload(true);
                                        }, 1000);
                                    } else {
                                        console.error("Error updating notification:", response.error);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error("AJAX error:", xhr.responseText, status, error);
                                }
                            });
                        } else {
                            console.error("Notification ID is missing");
                        }
                    });
                });
            </script>


            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="messageDropdownToggle" href="#" data-bs-toggle="dropdown">
                    <i class="fa fa-envelope fa-fw menu-icon" aria-hidden="true"></i>
                    <span id="messageCount" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle"></span>
                </a>
                <div id="MessageDropdown" class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdownToggle">
                    <a class="dropdown-item py-3 border-bottom">
                        <p class="mb-0 fw-medium float-start">You have new messages</p>
                        <span class="badge badge-pill badge-primary float-end">View all</span>
                    </a>
                    <!-- Dynamically loaded messages will be inserted here -->
                </div>
            </li>

            <script>
                function toggleDropdown() {
                    var dropdown = document.getElementById("MessageDropdown");
                    var userDropdown = document.getElementById("UserDropdown");

                    if (dropdown.style.display === "none" || dropdown.style.display === "") {
                        loadMessages(); // Load messages when dropdown opens
                        dropdown.style.display = "block";
                        if (userDropdown && userDropdown.classList.contains('show')) {
                            userDropdown.classList.remove('show'); // Close UserDropdown if open
                        }
                    } else {
                        dropdown.style.display = "none";
                    }
                }

                function toggleDropdown2() {
                    var dropdown = document.getElementById("UserDropdown");
                    var messageDropdown = document.getElementById("MessageDropdown");

                    if (!dropdown.classList.contains('show')) {
                        dropdown.classList.add('show');
                        messageDropdown.style.display = "none"; // Close MessageDropdown if open
                    } else {
                        dropdown.classList.remove('show');
                    }
                }

                function loadMessages() {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'fetch_messages2.php', true);
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            try {
                                var response = JSON.parse(xhr.responseText);
                                console.log('Response:', response); // Debugging log
                                var messages = response.messages;
                                var messageCount = response.count;

                                var messageDropdown = document.getElementById("MessageDropdown");
                                var messageBadge = document.getElementById("messageCount");

                                messageDropdown.innerHTML = ''; // Clear previous content
                                messages.forEach(function(message) {
                                    var messageItem = document.createElement('a');
                                    messageItem.setAttribute('href', 'chat.php?room_id=' + encodeURIComponent(message.room_id));
                                    messageItem.classList.add('dropdown-item', 'preview-item', 'py-3');

                                    var previewThumbnail = document.createElement('div');
                                    previewThumbnail.classList.add('preview-thumbnail');
                                    previewThumbnail.innerHTML = '<i class="fa fa-comment m-auto text-primary"></i>';

                                    var previewItemContent = document.createElement('div');
                                    previewItemContent.classList.add('preview-item-content');
                                    previewItemContent.innerHTML = '<h6 class="preview-subject fw-normal text-dark mb-1">' + message.name + '</h6><p class="fw-light small-text mb-0">' + message.message + '</p>';

                                    messageItem.appendChild(previewThumbnail);
                                    messageItem.appendChild(previewItemContent);

                                    messageDropdown.appendChild(messageItem);

                                    // Add event listener to mark message as read when clicked
                                    messageItem.addEventListener('click', function() {
                                        markMessageAsRead(message.message_id);
                                        messageItem.classList.remove('w3-text-bold'); // Remove bold style after marking as read
                                    });

                                    // Apply bold style for unread messages
                                    if (message.is_read !== 1) {
                                        messageItem.classList.add('w3-text-bold');
                                    }
                                });

                                // Update message count badge
                                messageBadge.textContent = messageCount;
                                messageBadge.style.display = messageCount > 0 ? 'inline-block' : 'none'; // Show badge only if there are messages
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                                console.error('Response text:', xhr.responseText);
                            }
                        } else {
                            console.error('Error fetching messages:', xhr.statusText);
                        }
                    };
                    xhr.onerror = function() {
                        console.error('Network error while fetching messages.');
                    };
                    xhr.send();
                }

                function markMessageAsRead(messageId) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'mark_as_read.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            console.log('Message marked as read:', messageId);
                            // Update UI or perform additional actions upon success (optional)
                        } else {
                            console.error('Error marking message as read:', xhr.statusText);
                        }
                    };
                    xhr.onerror = function() {
                        console.error('Network error while marking message as read.');
                    };
                    xhr.send('message_id=' + encodeURIComponent(messageId));
                }

                // Load messages on page load and refresh every 1 seconds
                window.onload = function() {
                    loadMessages();
                    setInterval(loadMessages, 1000); // Refresh every 1 seconds

                };
            </script>

            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= htmlspecialchars($loggedInUser['profile_image']) ?>"
                        alt="Admin"
                        class="rounded-circle"
                        width="30">
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                        <div class="dropdown-header text-center">
                            <img src="<?= htmlspecialchars($loggedInUser['profile_image']) ?>"
                                alt="Admin"
                                class="rounded-circle"
                                width="30">
                            <p class="mb-1 mt-3 fw-semibold"><?= htmlspecialchars($loggedInUser['fname']); ?> <?= htmlspecialchars($loggedInUser['Mname']); ?> <?= htmlspecialchars($loggedInUser['Lname']); ?></p>
                            <p class="fw-light text-muted mb-0"><?= htmlspecialchars($loggedInUser['email']); ?></p>
                        </div>
                        <a class="dropdown-item" href="account.php"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
                        <a class="dropdown-item" href="message.php"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Messages</a>
                        <a class="dropdown-item" href="importantinclude/logout.php"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
                    </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>