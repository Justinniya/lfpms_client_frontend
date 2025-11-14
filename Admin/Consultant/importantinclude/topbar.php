<?php
session_start();
$current_user = $_SESSION['id'] ?? null; // Ensure user ID is set

if (!$current_user) {
    exit("User not logged in.");
}

try {
    // Fetch notifications for Product Development Assessment approvals
    $stmt1 = $conn->prepare("
        SELECT 
            cq.id, 
            u.userid, 
            'Your Product Development Assessment has been approved.' AS message,
            'approved' AS type,
            cr.*
        FROM consultancyquestionnaire cq
        INNER JOIN consultation_report cr ON cq.id = cr.consultationID
        INNER JOIN users u ON u.userid = cr.conID
        WHERE cr.conID = :user_id 
        AND cq.status = 6
        ORDER BY cq.id DESC
    ");
    $stmt1->bindParam(':user_id', $current_user, PDO::PARAM_INT);
    $stmt1->execute();
    $product_notifications = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Fetch notifications for pending consultation reports (con status = 0)
    $stmt2 = $conn->prepare("
        SELECT 
            r.id AS room_id, 
            r.name AS room_name, 
            'You have a pending consultation report.' AS message,
            'pending' AS type,
            r.*
        FROM room r
        WHERE r.con_status = 0
        AND EXISTS (
            SELECT 1 FROM chatmember rm WHERE rm.room_id = r.id AND rm.user_id = :user_id
        )
        ORDER BY r.id ASC
    ");
    $stmt2->bindParam(':user_id', $current_user, PDO::PARAM_INT);
    $stmt2->execute();
    $room_notifications = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Merge notifications
    $notifications = array_merge($product_notifications, $room_notifications);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    exit("Database error. Please try again later.");
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    exit("An error occurred.");
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
            <a class="navbar-brand brand-logo" href="consultant_index.php">
                <img src="assets/img/bb.png" alt="">
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <h5>Welcome Consultant, <span class="text-black fw-bold"><?= htmlspecialchars($loggedInUser['fname']); ?>!</span></h5>
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
                       href="#" data-notification-id="<?= $notification['id']; ?>">
                        <div class="preview-thumbnail">
                            <?php if ($notification['type'] === 'rejected') : ?>
                                <i class="mdi mdi-alert m-auto text-danger"></i>
                            <?php elseif ($notification['type'] === 'chat_closed') : ?>
                                <i class="mdi mdi-chat-remove m-auto text-warning"></i>
                            <?php elseif ($notification['type'] === 'pending') : ?>
                                <i class="mdi mdi-clock-outline m-auto text-primary"></i>
                            <?php else : ?>
                                <i class="mdi mdi-check-circle m-auto text-success"></i>
                            <?php endif; ?>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject fw-normal text-dark mb-1">
                                <?php
                                switch ($notification['type']) {
                                    case 'rejected': echo 'Assessment Rejected'; break;
                                    case 'chat_closed': echo 'Chatroom Closed'; break;
                                    case 'pending': echo 'Pending Consultation Report'; break;
                                    case 'approved': echo 'Assessment Approved'; break;
                                    default: echo 'Notification';
                                }
                                ?>
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
    $(document).ready(function () {
        var notificationCount = <?= count($notifications); ?>;
        $("#notificationCount").toggle(notificationCount > 0);

        $(".notification-item").on("click", function (e) {
            e.preventDefault();

            var notificationId = $(this).data("notification-id");
            var notificationItem = $(this);

            if (!notificationId) {
                console.error("Notification ID is missing");
                return;
            }

            $.ajax({
                url: "update_notification_status.php",
                type: "POST",
                data: { notification_id: notificationId },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        notificationItem.fadeOut(500, function () {
                            $(this).remove();
                        });

                        var newCount = $("#notificationList .notification-item").length - 1;
                        $("#notificationCount").text(newCount > 0 ? newCount : "").toggle(newCount > 0);

                        // Refresh page if all notifications are gone
                        if (newCount === 0) {
                            setTimeout(function () {
                                window.location.reload(true);
                            }, 1000);
                        }
                    } else {
                        console.error("Error updating notification:", response.error);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", xhr.responseText, status, error);
                }
            });
        });
    });
</script>


            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="messageDropdownToggle" href="#" onclick="toggleDropdown()">
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
                                width="30"> </a>
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