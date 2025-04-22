<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems | Checkout Page</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/cart.css">
    <!-- Add Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<style>
    .form-check-input:checked {
        background-color: #A2678A !important;
        border-color: #A2678A !important;
        padding: 0pc !important;
    }

    .image img {
        border-radius: 10%;
        box-shadow: 2px 2px 6px rgba(215, 159, 99, 0.8), -2px -2px 6px rgba(255, 214, 133, 0.81) !important;
    }

    .img-fluid {
        margin: 0;
        width: 60% !important;
    }

    .service-card {
        cursor: pointer;
        transition: all 0.3s;
    }

    .service-card:hover {
        background-color: #f8f9fa;
    }

    .btn-next,
    .btn-next-1 {
        background-color: #A2678A;
    }

    .btn-next:hover,
    .btn-next-1:hover {
        background-color: #8a5a7a;
    }
</style>

<body>
    <?php
    $cart = "font-bold";
    include("../components/header.php");
    ?>

    <div class="container my-5 pt-5">
        <!-- Nav Tabs for Large Screens -->
        <ul class="nav nav-tabs d-none d-md-flex" id="checkoutTabs">
            <li class="nav-item">
                <a class="nav-link cart-tab active" data-bs-toggle="tab" href="#availedServices">Availed Services</a>
            </li>
            <li class="nav-item">
                <a class="nav-link cart-tab" data-bs-toggle="tab" href="#celebrantDetails">Event Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link cart-tab place-btn" data-bs-toggle="tab" href="#eventDetails">Place Order</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Availed Services Tab -->
            <div class="tab-pane fade show position-relative active" id="availedServices">
                <h4>Availed Services</h4>
                <div id="servicesContainer"></div>
                <div class="event-details-footer d-flex justify-content-end position-sticky z-3 bottom-0 left-0">
                    <button class="btn btn-edit btn-next text-white btn-shadow" id="nextBtn" disabled>
                        <ion-icon name="arrow-forward-circle" style="font-size:1.2rem;"></ion-icon> Next
                    </button>
                </div>
            </div>

            <!-- Celebrant Details Tab -->
            <div class="tab-pane fade position-relative" id="celebrantDetails">
                <form id="celebrantForm">
                    <div class="row">
                        <div class="w-100 h-1">
                            <hr>
                        </div>
                        <h4>Celebrant Details</h4>
                        <div class="col-md-3 mb-3">
                            <label>First Name</label>
                            <input type="text" class="form-control" placeholder="Enter first name" id="firstName" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Last Name</label>
                            <input type="text" class="form-control" placeholder="Enter last name" id="lastName" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Middle Name (Optional)</label>
                            <input type="text" class="form-control" placeholder="Enter middle name (Optional)" id="middleName">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Celebrant's Age</label>
                            <input type="number" class="form-control" placeholder="Enter Celebrant's Age" id="celebrantsAge" required min="1">
                        </div>
                        <div class="w-100 h-1">
                            <hr>
                        </div>
                        <h4>Event Details</h4>
                        <div class="col-md-6 mb-3">
                            <label>Preferred Party Theme</label>
                            <textarea class="form-control" rows="3" placeholder="Any special requests or notes" id="partyTheme" required></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Preferred Theme Color</label>
                            <input type="text" class="form-control" placeholder="Enter theme color" id="themeColor" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Celebrant's Gender</label>
                            <select class="form-control" id="gender" required>
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Event Type</label>
                            <input type="text" class="form-control" placeholder="e.g. Birthday, Christening" id="eventType" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Event Location</label>
                            <input type="text" class="form-control" placeholder="Enter Location" id="eventLocation" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Event Time & Date</label>
                            <div class="col-sm-8">
                                <input type="text" class="input-pl-date montserrat shadow-cstm" id="eventDate"
                                    placeholder="Loading available dates..." disabled>
                                <input type="hidden" id="hiddenOrderDate" name="hiddenOrderDate">
                            </div>
                        </div>

                        <!-- Flatpickr CSS/JS -->
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>

                        <!-- SweetAlert for error messages -->
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                        <style>
                            /* Color Coding Styles */
                            .yellow-day {
                                background-color: #ffc107 !important;
                                border-color: #ffffff !important;
                            }

                            .red-day {
                                background-color: #dc3545 !important;
                                border-color: #dc3545 !important;
                                color: #ffffff !important;
                            }

                            .flatpickr-day.disabled {
                                color: #ccc !important;
                                cursor: not-allowed;
                            }

                            .order-count-badge {
                                position: absolute;
                                top: 2px;
                                right: 2px;
                                background: #333;
                                color: white;
                                border-radius: 50%;
                                width: 16px;
                                height: 16px;
                                font-size: 10px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            }
                        </style>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const orderDateInput = document.getElementById('eventDate');
                                const MAX_ORDERS_PER_DAY = 2;
                                let bookings = {};

                                async function fetchBookingData() {
                                    try {
                                        orderDateInput.placeholder = "Loading available dates...";
                                        const response = await fetch('../backend/booking-data.php');

                                        if (!response.ok) throw new Error('Failed to load availability data');

                                        const data = await response.json();
                                        bookings = data.orders || {};

                                        // Log all dates with orders to the console
                                        console.log("Dates with orders:");
                                        for (const [dateStr, orderCount] of Object.entries(bookings)) {
                                            console.log(`Date: ${dateStr}, Orders: ${orderCount}`);
                                        }

                                    } catch (error) {
                                        console.error('Error:', error);
                                        bookings = {};
                                        orderDateInput.placeholder = "Select a date";
                                    } finally {
                                        initDatePicker();
                                        orderDateInput.disabled = false;
                                    }
                                }

                                function initDatePicker() {
                                    const availableDates = [];
                                    const today = new Date();
                                    today.setHours(0, 0, 0, 0); // Reset time to midnight (00:00:00) to avoid timezone issues
                                    const todayStr = formatDate(today);

                                    // Create date range (today to 1 year ahead)
                                    const currentDate = new Date(today);
                                    const endDate = new Date();
                                    endDate.setFullYear(today.getFullYear() + 1);

                                    while (currentDate <= endDate) {
                                        const dateStr = formatDate(currentDate);
                                        const bookingsCount = bookings[dateStr] || 0;

                                        if (bookingsCount < MAX_ORDERS_PER_DAY) {
                                            availableDates.push(dateStr);
                                        }
                                        currentDate.setDate(currentDate.getDate() + 1);
                                    }

                                    const picker = flatpickr(orderDateInput, {
                                        dateFormat: "Y-m-d", // Ensure correct date format including year
                                        minDate: "today", // Disable past dates
                                        enable: availableDates,
                                        disable: [
                                            function(date) {
                                                return date.getDay() === 0; // Disable Sundays
                                            }
                                        ],
                                        // Adding the 'year selector' to the calendar view
                                        showMonths: 1, // Show only 1 month per view, but you can adjust this
                                        monthSelectorType: 'dropdown', // Enable month dropdown
                                        yearSelector: true, // Enable year dropdown in the calendar
                                        onChange: function(selectedDates, dateStr) {
                                            checkDateAvailability(dateStr);
                                        },
                                        onDayCreate: function(dObj, dStr, fp, dayElem) {

                                            const dateStr = formatDate(dayElem.dateObj);
                                            const bookingsCount = bookings[dateStr] || 0;

                                            // Disable today’s date completely
                                            if (dateStr === todayStr) {
                                                dayElem.classList.add('disabled');
                                                dayElem.style.pointerEvents = 'none';
                                            }

                                            // Clear previous styling
                                            dayElem.classList.remove('yellow-day', 'red-day');

                                            // Apply color coding
                                            if (bookingsCount === 1) {
                                                dayElem.classList.add('yellow-day');

                                                // Add badge showing "1"
                                                const badge = document.createElement("span");
                                                badge.className = "order-count-badge";
                                                badge.textContent = "1";
                                                dayElem.appendChild(badge);

                                            } else if (bookingsCount >= MAX_ORDERS_PER_DAY) {
                                                dayElem.style.pointerEvents = "none"; // Disable clicking
                                                dayElem.classList.add('red-day');

                                                // Add badge showing "2+"
                                                const badge = document.createElement("span");
                                                badge.className = "order-count-badge";
                                                badge.textContent = "2";
                                                badge.style.backgroundColor = "#333333";
                                                dayElem.appendChild(badge);
                                            }
                                        }
                                    });
                                }

                                function formatDate(date) {
                                    // Format as YYYY-MM-DD
                                    const yyyy = date.getFullYear();
                                    const mm = (date.getMonth() + 1).toString().padStart(2, '0');
                                    const dd = date.getDate().toString().padStart(2, '0');
                                    return `${yyyy}-${mm}-${dd}`;
                                }

                                fetchBookingData();
                            });
                        </script>






                        <div class="w-100 h-1">
                            <hr>
                        </div>
                        <h4>Client's Details</h4>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Client's Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter Client's Full Name" id="clientName" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Client's Phone Number</label>
                                <input type="tel" class="form-control" placeholder="Enter Client's Phone Number" id="phoneNumber" required>
                            </div>
                            <div class="col-md-4 mb-3"></div>
                            <div class="col-md-3 mb-3">
                                <label>Drive/Street</label>
                                <input type="text" class="form-control" placeholder="Drive/Street" id="street" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Barangay</label>
                                <input type="text" class="form-control" placeholder="Barangay" id="barangay" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>City</label>
                                <input type="text" class="form-control" placeholder="City" id="city" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Province</label>
                                <input type="text" class="form-control" placeholder="Province" id="province" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Zip</label>
                                <input type="text" class="form-control" placeholder="Zip" id="zip" required>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="event-details-footer d-flex justify-content-end position-sticky z-3 bottom-0 left-0">
                    <button class="btn btn-edit btn-next-1 text-white" id="nextBtn2" disabled>
                        <ion-icon name="arrow-forward-circle" style="font-size:1.2rem;"></ion-icon> Next
                    </button>
                </div>
            </div>

            <!-- Order Summary Tab -->
            <div class="tab-pane fade" id="eventDetails">
                <div id="orderSummary">
                    <div class="d-flex justify-content-center">
                        <h6 class="text-center my-4">
                            Please Add Item(s) and Fill Out the Form First
                        </h6>
                    </div>
                </div>
                <div id="warningMessage"></div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">GCash Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Scan the QR code using your GCash app, then upload the payment reference.</p>
                    <img src="../img/GCash-pay.png" alt="GCash QR" class="img-fluid mb-3">
                    <input type="file" id="referencePic" class="form-control" accept="image/*">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitCheckout" class="btn btn-primary">Submit Payment</button>
                </div>
            </div>
        </div>
    </div>

    <?php include("../components/footer.php"); ?>

    <!-- Add Bootstrap JS -->
    <script src="../bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Global variables to store cart data
        let cartItems = [];

        // Check if any services are selected
        function hasSelectedServices() {
            return cartItems.some(item => item.status === 'active');
        }

        // Initialize the page
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

            // Load cart data
            fetchCartData();

            // Set up tab navigation
            setupTabNavigation();

            // Set up form validation
            setupFormValidation();

            // Set up event listeners for buttons
            document.getElementById('nextBtn').addEventListener('click', function() {
                goToTab('celebrantDetails');
            });

            document.getElementById('nextBtn2').addEventListener('click', function() {
                if (validateCelebrantForm()) {
                    goToTab('eventDetails');
                    collectCheckoutDetails();
                }
            });

            // Set up modal submission
            document.getElementById('submitCheckout').addEventListener('click', submitPayment);
        });

        // Tab navigation functions
        function setupTabNavigation() {
            const tabLinks = document.querySelectorAll("#checkoutTabs a");

            tabLinks.forEach(link => {
                link.addEventListener("click", function(e) {
                    const targetTab = this.getAttribute("href");

                    if (targetTab === "#celebrantDetails" && !hasSelectedServices()) {
                        e.preventDefault();
                        alert("Please select at least one service first.");
                        return false;
                    }

                    if (targetTab === "#eventDetails" && (!hasSelectedServices() || !validateCelebrantForm())) {
                        e.preventDefault();
                        alert("Please complete all required fields first.");
                        return false;
                    }
                });
            });
        }

        function goToTab(tabId) {
            const tab = document.querySelector(`a[href="#${tabId}"]`);
            if (tab) {
                new bootstrap.Tab(tab).show();
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }
        }

        // Form validation
        function setupFormValidation() {
            const requiredFields = document.querySelectorAll('#celebrantForm [required]');

            requiredFields.forEach(field => {
                field.addEventListener('input', function() {
                    checkFormCompletion();
                });
            });
        }

        function checkFormCompletion() {
            const nextBtn2 = document.getElementById('nextBtn2');
            const allFilled = Array.from(document.querySelectorAll('#celebrantForm [required]')).every(f => f.value.trim() !== '');
            nextBtn2.disabled = !allFilled;
        }

        function validateCelebrantForm() {
            const requiredFields = document.querySelectorAll('#celebrantForm [required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            return isValid && hasSelectedServices();
        }

        // Fetch cart data from backend
        function fetchCartData() {
            const container = document.getElementById('servicesContainer');
            container.innerHTML = '<div class="text-center py-4"><div class="spinner-border"></div><p>Loading cart...</p></div>';

            fetch('../backend/getCartData.php', {
                    credentials: 'include'
                })
                .then(async response => {
                    const text = await response.text();
                    let data;

                    try {
                        data = JSON.parse(text);
                    } catch {
                        throw new Error('Invalid server response');
                    }

                    if (response.status === 401) {
                        window.location.href = '../login/customer_login.php';
                        return;
                    }

                    if (!response.ok) {
                        throw new Error(data.error || `Server error (${response.status})`);
                    }

                    cartItems = data.map(item => ({
                        cart_item_id: item.cart_item_id,
                        service_id: item.service_id,
                        service_name: item.service_name,
                        description: item.description,
                        category: item.category,
                        entertainer_duration_options: item.entertainer_duration_options,
                        image: item.image,
                        service_price: item.service_price,
                        cart_price: item.cart_price,
                        quantity: item.quantity,
                        status: item.status
                    }));

                    renderCartItems();
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    container.innerHTML = `
                    <div class="alert alert-danger">
                        ${error.message.replace('Database error', 'Cart service unavailable')}
                        <button onclick="fetchCartData()" class="btn btn-sm btn-warning ms-2">
                            Retry
                        </button>
                    </div>
                `;
                });
        }

        // Render cart items
        function renderCartItems() {
            const container = document.getElementById('servicesContainer');

            if (cartItems.length === 0) {
                container.innerHTML = '<p class="text-center py-4">Your cart is empty</p>';
                document.getElementById('nextBtn').disabled = true;
                return;
            }

            container.innerHTML = cartItems.map(item => `
            <div class="service-card d-flex align-items-center gap-3 p-3 mb-3 shadow-sm">
                <input type="checkbox" class="service-checkbox form-check-input" 
                    ${item.status === 'active' ? 'checked' : ''} 
                    data-id="${item.cart_item_id}"
                    id="cart-item-${item.cart_item_id}">
                <label for="cart-item-${item.cart_item_id}" class="d-flex align-items-center gap-3 w-100">
                    <img src="../uploads/${item.image || 'default.jpg'}" width="80" height="80" class="rounded" style="object-fit: cover;">
                    <div class="flex-grow-1">
                        <h5>${item.service_name}</h5>
                        <div class="text-success">₱${item.cart_price}</div>
                        ${item.quantity > 1 ? `<div class="text-muted">Qty: ${item.quantity}</div>` : ''}
                    </div>
                </label>
                <button class="btn btn-sm btn-outline-danger remove-btn" data-id="${item.cart_item_id}">
                    Remove
                </button>
            </div>
        `).join('');

            // Add event delegation for checkboxes and remove buttons
            container.addEventListener('change', function(e) {
                if (e.target.classList.contains('service-checkbox')) {
                    const cartItemId = e.target.dataset.id;
                    const isActive = e.target.checked;
                    updateCartItemStatus(cartItemId, isActive);
                    document.getElementById('nextBtn').disabled = !hasSelectedServices();
                }
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-btn')) {
                    e.stopPropagation();
                    removeCartItem(e.target.dataset.id);
                }
            });

            // Update next button state
            document.getElementById('nextBtn').disabled = !hasSelectedServices();
        }

        // Update cart item status
        function updateCartItemStatus(cartItemId, isActive) {
            const checkbox = document.querySelector(`.service-checkbox[data-id="${cartItemId}"]`);
            const originalState = checkbox.checked;

            checkbox.disabled = true;

            fetch('../backend/update_cart_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cart_item_id: cartItemId,
                        status: isActive ? 'active' : 'inactive'
                    }),
                    credentials: 'include'
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Update failed');
                    }
                    if (!data.success) {
                        throw new Error(data.message || 'Update unsuccessful');
                    }

                    // Update local state
                    const item = cartItems.find(item => item.cart_item_id == cartItemId);
                    if (item) {
                        item.status = isActive ? 'active' : 'inactive';
                    }

                    // Update UI
                    document.getElementById('nextBtn').disabled = !hasSelectedServices();
                })
                .catch(error => {
                    console.error('Update error:', error);
                    checkbox.checked = originalState;
                    alert(error.message);
                })
                .finally(() => {
                    checkbox.disabled = false;
                });
        }

        // Remove cart item
        function removeCartItem(itemId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to remove this item from your cart.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('../backend/removeCartItem.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                cart_item_id: itemId
                            }),
                            credentials: 'include'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                cartItems = cartItems.filter(item => item.cart_item_id != itemId);
                                renderCartItems();

                                Swal.fire({
                                    title: 'Removed!',
                                    text: 'The item has been removed from your cart.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire('Error', data.error || 'Failed to remove item.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to remove item.', 'error');
                        });
                }
            });
        }


        // Update the collectCheckoutDetails function
        function collectCheckoutDetails() {
            const orderSummary = document.getElementById('orderSummary');
            const warningMessage = document.getElementById('warningMessage');

            if (!validateCelebrantForm()) {
                warningMessage.innerHTML = '<div class="alert alert-danger">Please complete all required fields first.</div>';
                orderSummary.innerHTML = '<p class="text-center my-4">Please complete the form to see order summary.</p>';
                return;
            }

            warningMessage.innerHTML = '';

            const selectedServices = cartItems.filter(item => item.status === 'active');

            if (selectedServices.length === 0) {
                orderSummary.innerHTML = '<p class="text-center my-4">No services selected.</p>';
                return;
            }

            const eventDetails = {
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                middleName: document.getElementById('middleName').value,
                age: document.getElementById('celebrantsAge').value,
                theme: document.getElementById('partyTheme').value,
                themeColor: document.getElementById('themeColor').value,
                gender: document.getElementById('gender').value,
                eventType: document.getElementById('eventType').value,
                eventLocation: document.getElementById('eventLocation').value,
                eventDate: document.getElementById('eventDate').value,
                clientName: document.getElementById('clientName').value,
                clientPhone: document.getElementById('phoneNumber').value,
                address: {
                    street: document.getElementById('street').value,
                    barangay: document.getElementById('barangay').value,
                    city: document.getElementById('city').value,
                    province: document.getElementById('province').value,
                    zip: document.getElementById('zip').value
                }
            };

            // Calculate total amount
            const totalAmount = selectedServices.reduce((sum, item) => sum + parseFloat(item.cart_price), 0);
            const depositAmount = totalAmount * 0.5; // 50% deposit for partial payment
            const remainingBalance = totalAmount - depositAmount;

            orderSummary.innerHTML = `
                <h4 class="text-center mb-4">Order Summary</h4>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Selected Services</h5>
                    </div>
                    <div class="card-body">
                        ${selectedServices.map(service => `
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6>${service.service_name}</h6>
                                    <small class="text-muted">${service.description}</small>
                                </div>
                                <div class="text-success">₱${service.cart_price}</div>
                            </div>
                        `).join('')}
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Total</h5>
                            <h5 class="text-success">₱${totalAmount.toFixed(2)}</h5>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Celebrant Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> ${eventDetails.firstName} ${eventDetails.middleName ? eventDetails.middleName + ' ' : ''}${eventDetails.lastName}</p>
                        <p><strong>Age:</strong> ${eventDetails.age}</p>
                        <p><strong>Gender:</strong> ${eventDetails.gender}</p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Event Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Theme:</strong> ${eventDetails.theme} (${eventDetails.themeColor})</p>
                        <p><strong>Type:</strong> ${eventDetails.eventType}</p>
                        <p><strong>Location:</strong> ${eventDetails.eventLocation}</p>
                        <p><strong>Date & Time:</strong> ${new Date(eventDetails.eventDate).toLocaleString()}</p>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Client Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> ${eventDetails.clientName}</p>
                        <p><strong>Phone:</strong> ${eventDetails.clientPhone}</p>
                        <p><strong>Address:</strong> ${eventDetails.address.street}, ${eventDetails.address.barangay}, ${eventDetails.address.city}, ${eventDetails.address.province} ${eventDetails.address.zip}</p>
                    </div>
                </div>
                
                <form id="checkoutForm" method="POST" action="../backend/process_order.php" enctype="multipart/form-data">
                    <input type="hidden" name="selected_services" value='${JSON.stringify(selectedServices)}'>
                    <input type="hidden" name="event_details" value='${JSON.stringify(eventDetails)}'>
                    <input type="hidden" name="total_amount" value="${totalAmount}">
                    <input type="hidden" name="deposit_amount" value="${depositAmount}">
                    <input type="hidden" name="remaining_balance" value="${remainingBalance}">
                    
                    <?php include("../components/check-boxes.php"); ?>
                </form>
            `;
        }

        // Update the submitPayment function
        function submitPayment() {
            const form = document.getElementById('checkoutForm');
            const formData = new FormData(form);

            fetch('../backend/process_order.php', {
                    method: 'POST',
                    body: formData,
                    credentials: 'include'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order placed successfully!');
                        window.location.href = 'order-confirmation.php?order_id=' + data.order_id;
                    } else {
                        alert('Error: ' + (data.message || 'Failed to place order'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your order');
                });
        }

        function submitPayment() {
            const fileInput = document.getElementById('referencePic');

            if (!fileInput.files.length) {
                alert('Please upload your payment reference');
                return;
            }

            alert('Payment submitted! Thank you for your order.');

            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
            modal.hide();

            // Redirect or show success message
            window.location.href = 'order-confirmation.php';
        }
    </script>
    <script src="../bootstrap/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update payment method and type when changed
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelector('input[name="payment_method"][value="' + this.value + '"]').checked = true;
                });
            });

            document.querySelectorAll('input[name="payment_type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelector('input[name="payment_type"][value="' + this.value + '"]').checked = true;
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const agreeCheckbox = document.getElementById('agreeTerms');
            const submitButton = document.getElementById('submitPaymentBtn');
            const termsLink = document.getElementById('termsText');

            // Checkbox change handler
            agreeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    showTermsModal();
                } else {
                    submitButton.disabled = true;
                }
            });

            // Click handler for terms text
            termsLink?.addEventListener('click', function() {
                agreeCheckbox.checked = !agreeCheckbox.checked;
                if (agreeCheckbox.checked) showTermsModal();
            });

            function showTermsModal() {
                Swal.fire({
                    title: 'Terms and Conditions',
                    html: `<div class="text-start p-3" style="max-height: 60vh; overflow-y: auto;">
                <h6 class="fw-bold">Payment Policy</h6>
                <ul>
                    <li>50% deposit required to confirm booking</li>
                    <li>Full payment due 7 days before event</li>
                </ul>
                
                <h6 class="fw-bold mt-4">Cancellation Policy</h6>
                <ul>
                    <li>No refunds after 72 hours of payment</li>
                    <li>Date changes require 7-day notice</li>
                </ul>
            </div>`,
                    icon: 'info',
                    confirmButtonText: 'I Accept',
                    cancelButtonText: 'Decline',
                    showCancelButton: true,
                    confirmButtonColor: '#A2678A',
                    width: '600px'
                }).then((result) => {
                    agreeCheckbox.checked = result.isConfirmed;
                    submitButton.disabled = !result.isConfirmed;
                });
            }
        });
    </script>
</body>

</html>