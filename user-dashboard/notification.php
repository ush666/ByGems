<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ByGems | Notifications</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <style>
        body {
            background: #FFF9E5;
            font-family: Arial, sans-serif;
        }

        .calendar-container {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            max-width: 100%;
            margin: 0 auto;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .card {
            box-shadow: 4px 4px 6px #d9d9d9,
                -4px -4px 6px #ffffff;
        }

        .card:hover {
            background: #FFF9E5;
            box-shadow: 4px 4px 6px #d9d9d9,
                -4px -4px 6px #ffffff;
                transform: translateY(-2px) !important;
        }

        .calendar-header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .calendar-weekdays,
        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: bold;
        }

        .calendar-day {
            padding: 8px;
            cursor: pointer;
            position: relative;
        }

        .has-notification::after {
            content: "•";
            position: absolute;
            top: -5px;
            right: 2px;
            color: #dc3545;
            font-size: 1.2rem;
        }

        .current-date {
            background-color: rgba(255, 193, 7);
            border-radius: 3px;
            color: #fff !important;
            border-radius: 50%;
            border-top-right-radius: 10px;
            height: 35px;
            width: 35px;
        }

        .card-container {
            border: none;
            transition: all 0.3s;
            background: #fff;
        }

        .card-container:hover {
            transform: translateY(0px) !important;
        }

        .notification-card.bg-light {
            background: #FFF9E5;
            box-shadow: inset 4px 4px 6px #d9d4c3,
                inset -4px -4px 6px #ffffff;
        }

        .bold {
            font-weight: bold !important;
        }

        /* Tooltip container */
        .tooltip .tooltip-inner {
            background-color: rgb(119, 114, 98);
            /* dark background */
            color: #fff;
            font-weight: bold;
            /* white text */
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.9rem;
            max-width: 200px;
        }

        .tooltip {
            --bs-tooltip-bg: var(--bs-emphasis-color);
        }

        :root {
            --bs-emphasis-color: rgb(119, 114, 98);
        }
    </style>
</head>

<body>
    <?php include("../components/header.php"); ?>
    <div class="container my-5 pt-5">
        <div class="card-container shadow rounded-4 p-4">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-3">Notifications</h2>
                    <!--<button class="btn btn-danger mb-3" onclick="clearAll()">Clear All</button>-->
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
        document.addEventListener("DOMContentLoaded", function() {
            let notifications = [{
                    id: 1,
                    date: "2025-04-05",
                    text: "New order received",
                    read: false
                },
                {
                    id: 2,
                    date: "2025-04-07",
                    text: "Payment processed",
                    read: false
                },
                {
                    id: 3,
                    date: "2025-04-10",
                    text: "Shipment dispatched",
                    read: true
                }
            ];

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
                notifications = notifications.map(n =>
                    n.id === id ? {
                        ...n,
                        read: true
                    } : n
                );
                renderNotifications();
                renderCalendar();
            }

            function viewNotification(id) {
                const notification = notifications.find(n => n.id === id);
                if (notification) {
                    document.getElementById("notificationModalBody").innerText = notification.message;
                }
            }


            function renderCalendar() {
                const calendarEl = document.getElementById("calendar");
                const now = new Date();
                const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;
                const month = now.getMonth();
                const year = now.getFullYear();
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const daysInMonth = lastDay.getDate();
                const startingDay = firstDay.getDay();
                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                const weekdayNames = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];

                let calendarHTML = `<div class="calendar-header">${monthNames[month]} ${year}</div><div class="calendar-weekdays">`;
                weekdayNames.forEach(day => {
                    calendarHTML += `<div>${day}</div>`;
                });
                calendarHTML += `</div><div class="calendar-days">`;

                for (let i = 0; i < startingDay; i++) {
                    calendarHTML += `<div class="calendar-day other-month"></div>`;
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                    const hasNotification = notifications.some(n => n.date === dateStr);
                    let dayClass = "calendar-day";
                    if (hasNotification) dayClass += " has-notification";
                    if (dateStr === todayStr) dayClass += " current-date";
                    calendarHTML += `<div class="${dayClass}" data-bs-toggle="tooltip" title="${hasNotification ? notifications.find(n => n.date === dateStr).text : ''}">${day}</div>`;
                }

                calendarHTML += `</div>`;
                calendarEl.innerHTML = calendarHTML;

                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
            }

            function clearAll() {
                notifications = [];
                renderNotifications();
                renderCalendar();
            }

            window.markAsRead = markAsRead;
            window.viewNotification = viewNotification;

            renderNotifications();
            renderCalendar();
        });
    </script>


    <script type="module" src="../ionicons/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="../ionicons/dist/ionicons/ionicons.js"></script>
    <script src="../bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>