<?php
session_start();
require_once '../includes/db.php'; // your PDO connection

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ByGems | Notifications</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <style>
        /* (your original CSS here) */
    </style>
</head>

<body>
    <?php include("../components/header.php"); ?>

    <div class="container my-5 pt-5">
        <div class="card-container shadow rounded-4 p-4">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-3">Notifications</h2>
                    <div id="notifications-list"></div>
                </div>
                <div class="col-md-4">
                    <h5 class="text-center mb-3">Calendar</h5>
                    <div class="calendar-container" id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="notificationModalBody">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let notifications = [];

        document.addEventListener("DOMContentLoaded", function() {
            fetch('../events/fetch_notifications.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }
                    notifications = data.map(n => ({
                        id: n.id,
                        date: n.created_at.split(' ')[0],
                        text: n.message,
                        link: n.link,
                        event_id: n.event_id,
                        read: n.is_read == 1
                    }));
                    renderNotifications();
                    renderCalendar();
                });

            function renderNotifications() {
                const list = document.getElementById("notifications-list");
                list.innerHTML = "";
                notifications.forEach(notif => {
                    let div = document.createElement("div");
                    div.className = "card p-3 mb-2 notification-card";
                    if (notif.read) div.classList.add("bg-light", "text-muted");

                    div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex flex-column justify-content-start align-items-start gap-2">
                        <span>${notif.text}</span>
                        <span class="badge bg-info me-2 p-2 ps-4 pe-4">${notif.date}</span>
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-end gap-2">
                        ${notif.read ? '' : `<button class="btn btn-sm btn-warning text-white bold" onclick="markAsRead(${notif.id})">Mark as Read</button>`}
                        <button class="btn btn-sm btn-purple text-white bold" onclick="viewNotification(${notif.id})" data-bs-toggle="modal" data-bs-target="#notificationModal">View</button>
                    </div>
                </div>
            `;
                    list.appendChild(div);
                });
            }

            function markAsRead(id) {
                fetch('../events/mark_notification_read.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id=${id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notifications = notifications.map(n => n.id === id ? {
                                ...n,
                                read: true
                            } : n);
                            renderNotifications();
                            renderCalendar();
                        } else {
                            console.error(data.error);
                        }
                    });
            }

            function viewNotification(id) {
                const notification = notifications.find(n => n.id === id);
                if (notification) {
                    document.getElementById("notificationModalBody").innerHTML = `
                <p>${notification.text}</p>
                ${notification.link ? `<a href="${notification.link}" class="btn btn-sm btn-primary mt-2" target="_blank">Go to Details</a>` : ''}
            `;
                }
            }

            function renderCalendar() {
                const calendarEl = document.getElementById("calendar");
                const now = new Date();
                const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
                const month = now.getMonth();
                const year = now.getFullYear();
                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                const weekdayNames = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];

                let calendarHTML = `
        <div class="text-center fw-bold mb-2">${monthNames[month]} ${year}</div>
        <div class="d-grid" style="grid-template-columns: repeat(7, 1fr); gap: 5px;">
    `;

                // Render weekday headers
                weekdayNames.forEach(day => {
                    calendarHTML += `<div class="text-center small fw-bold">${day}</div>`;
                });

                // Empty slots before the 1st day
                for (let i = 0; i < firstDay; i++) {
                    calendarHTML += `<div></div>`;
                }

                // Render days
                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const hasNotification = notifications.some(n => n.date === dateStr);
                    let classes = "rounded p-1 text-center";
                    if (hasNotification) classes += " bg-warning text-white fw-bold";
                    if (dateStr === todayStr) classes += " bg-primary text-white";

                    calendarHTML += `
            <div class="${classes}" style="cursor: pointer;" title="${hasNotification ? notifications.find(n => n.date === dateStr)?.text : ''}">
                ${day}
            </div>
        `;
                }

                calendarHTML += `</div>`;
                calendarEl.innerHTML = calendarHTML;
            }
            window.markAsRead = markAsRead;
            window.viewNotification = viewNotification;
        });
    </script>

    <!-- Footer -->
    <?php
    include("../components/footer.php");
    ?>
</body>

</html>