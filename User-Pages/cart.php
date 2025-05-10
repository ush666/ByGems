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
    <style>
        .btn.disabled,
        .btn:disabled,
        fieldset:disabled .btn {
            color: var(--bs-btn-disabled-color);
            pointer-events: none;
            background-color: var(--bs-btn-disabled-bg);
            border-color: #33333300 !important;
            opacity: var(--bs-btn-disabled-opacity);
        }

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

        /* Discount Styles */
        .discount-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .discount-automatic-section {
            /*background-color: #f0f7ff;*/
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .discount-badge {
            background-color: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 8px;
        }

        .discount-input-group {
            max-width: 400px;
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            margin-right: 5px;
        }

        .discounted-price {
            color: #28a745;
            font-weight: bold;
        }

        .automatic-discount-item {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #e9f7fe;
            border-left: 4px solid #17a2b8;
        }

        .discount-apply-btn {
            background-color: #17a2b8;
            color: white;
        }

        .discount-apply-btn:hover {
            background-color: #138496;
            color: white;
        }
    </style>
</head>

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

                <!-- Automatic Discounts Section -->
                <div class="discount-automatic-section mb-4">
                    <h5>Available Discounts:</h5>
                    <div id="automaticDiscountsContainer" class="row justify-content-around">
                        <p class="text-muted">Loading available discounts...</p>
                    </div>
                </div>

                <!-- Manual Discount Section -->
                <div class="discount-section mb-4">
                    <h5>Apply Discount Code</h5>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group discount-input-group">
                                <input type="text" class="form-control" id="discountCode" placeholder="Enter discount code">
                                <button class="btn btn-purple text-white bold" id="applyDiscountBtn">Apply</button>
                            </div>
                            <small id="discountMessage" class="form-text text-muted"></small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div id="activeDiscountInfo" style="display: none;">
                                <strong>Active Discount:</strong>
                                <span id="discountName"></span>
                                <span id="discountValue" class="discount-badge"></span>
                                <button class="btn btn-sm btn-outline-danger ms-2" id="removeDiscountBtn">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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
                                        enableTime: true,
                                        time_24hr: true,
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
    <!-- SweetAlert for error messages -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global variables to store cart data
        let cartItems = [];
        let activeDiscount = null;
        let discountedServices = {};
        let automaticDiscounts = [];
        let currentlyAppliedDiscount = null;

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

            // Load automatic discounts
            fetchAutomaticDiscounts();

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

            // Discount functionality
            document.getElementById('applyDiscountBtn').addEventListener('click', async function() {
                const code = document.getElementById('discountCode').value.trim();
                if (code) {
                    await applyDiscount(code);
                }
            });

            document.getElementById('removeDiscountBtn').addEventListener('click', function() {
                removeDiscount();
            });

            // Set up modal submission
            document.getElementById('submitCheckout').addEventListener('click', submitPayment);
        });

        // Fetch automatic discounts that don't require a code
        function fetchAutomaticDiscounts() {
            fetch('../backend/get_automatic_discounts.php', {
                    credentials: 'include'
                })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to load discounts');
                    }

                    automaticDiscounts = data.discounts.filter(d => !d.requires_code);
                    renderAutomaticDiscounts();
                })
                .catch(error => {
                    console.error('Error loading automatic discounts:', error);
                    document.getElementById('automaticDiscountsContainer').innerHTML = `
                <div class="alert alert-warning">
                    Could not load automatic discounts. ${error.message}
                    <button onclick="fetchAutomaticDiscounts()" class="btn btn-sm btn-outline-secondary ms-2">
                        Retry
                    </button>
                </div>
            `;
                });
        }

        // Apply an automatic discount
        async function applyAutomaticDiscount(code) {
            // If already applying or already has an applied discount
            if (currentlyAppliedDiscount) return;

            const buttons = document.querySelectorAll('.discount-apply-btn');
            const clickedButton = document.querySelector(`button[data-discount-code="${code}"]`);

            try {
                // Disable all buttons while processing
                buttons.forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="text-muted">Apply</span>';
                });

                const result = await applyDiscount(code);
                if (result) {
                    currentlyAppliedDiscount = code;

                    // Update the clicked button
                    clickedButton.innerHTML = '<i class="fas fa-check"></i> Applied';
                    clickedButton.classList.remove('discount-apply-btn');
                    clickedButton.classList.add('btn-success');

                    // Keep other buttons disabled
                    buttons.forEach(btn => {
                        if (btn.dataset.discountCode !== code) {
                            btn.disabled = true;
                        }
                    });

                    // Show the active discount info
                    updateDiscountUI();
                }
            } catch (error) {
                console.error("Discount application failed:", error);
                resetDiscountButtons();
            }
        }

        // Remove discount and re-enable buttons
        function removeDiscount() {
            currentlyAppliedDiscount = null;
            activeDiscount = null;
            discountedServices = {};

            // Reset cart items to original prices
            cartItems.forEach(item => {
                if (item.discount_applied) {
                    item.cart_price = parseFloat(item.service_price) * parseInt(item.quantity);
                    delete item.discount_applied;
                }
            });

            // Reset all discount buttons
            resetDiscountButtons();

            // Update UI
            updateDiscountUI();
            renderCartItems();
        }

        // Reset all discount buttons to initial state
        function resetDiscountButtons() {
            const buttons = document.querySelectorAll('[data-discount-code]');
            buttons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = 'Apply';
                btn.classList.add('discount-apply-btn');
                btn.classList.remove('btn-success');
            });
        }

        // Modified render function to handle initial states
        function renderAutomaticDiscounts() {
            const container = document.getElementById('automaticDiscountsContainer');

            if (automaticDiscounts.length === 0) {
                container.innerHTML = '<p class="text-muted">No automatic discounts available at this time.</p>';
                return;
            }

            container.innerHTML = automaticDiscounts.map(discount => {
                const isApplied = currentlyAppliedDiscount === discount.discount_code;
                const isDisabled = currentlyAppliedDiscount && currentlyAppliedDiscount !== discount.discount_code;

                return `
        <div class="col-5 automatic-discount-item d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">${discount.discount_name}</h6>
                <p class="mb-1 small">${discount.discount_description}</p>
                <span class="badge ${discount.discount_type === 'percentage' ? 'bg-info' : 'bg-primary'}">
                    ${discount.discount_type === 'percentage' ? 
                      `${discount.discount_value}% OFF` : 
                      `₱${parseFloat(discount.discount_value).toFixed(2)} OFF`}
                </span>
                ${discount.minimum_amount ? `
                    <span class="badge bg-warning text-dark ms-2">
                        Min. order: ₱${parseFloat(discount.minimum_amount).toFixed(2)}
                    </span>
                ` : ''}
            </div>
            <button class="btn btn-sm ${isApplied ? 'btn-success' : 'discount-apply-btn'}" 
                    data-discount-code="${discount.discount_code}"
                    onclick="applyAutomaticDiscount('${discount.discount_code}')"
                    ${isApplied || isDisabled ? 'disabled' : ''}>
                ${isApplied ? '<i class="fas fa-check"></i> Applied' : 'Apply'}
            </button>
        </div>`;
            }).join('');
        }

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
                        service_price: parseFloat(item.service_price),
                        cart_price: parseFloat(item.cart_price),
                        quantity: parseInt(item.quantity),
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
                <div>
                    ${item.discount_applied ? `
                        <span class="original-price">₱${(item.service_price * item.quantity).toFixed(2)}</span>
                        <span class="discounted-price">₱${item.cart_price.toFixed(2)}</span>
                        <span class="discount-badge">Saved ₱${item.discount_applied.toFixed(2)}</span>
                    ` : `
                        <span class="text-success">₱${item.cart_price.toFixed(2)}</span>
                    `}
                </div>
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

        async function updateCartItemStatus(cartItemId, isActive) {
            const checkbox = document.querySelector(`.service-checkbox[data-id="${cartItemId}"]`);
            const originalState = checkbox.checked;

            checkbox.disabled = true;

            try {
                const response = await fetch('../backend/update_cart_item.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cart_item_id: cartItemId,
                        status: isActive ? 'active' : 'inactive'
                    }),
                    credentials: 'include'
                });

                // First check if response is OK
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                // Then parse JSON
                const data = await response.json();

                // Check if JSON parsing succeeded
                if (!data) {
                    throw new Error('Empty response from server');
                }

                // Update local state only if the update was successful
                if (data.success) {
                    const item = cartItems.find(item => item.cart_item_id == cartItemId);
                    if (item) {
                        item.status = isActive ? 'active' : 'inactive';
                    }
                    // Show success message only if actual update occurred
                    if (data.updated) {
                        console.log(data.message);
                    }
                } else {
                    throw new Error(data.message || 'Update failed');
                }

                // Update UI
                document.getElementById('nextBtn').disabled = !hasSelectedServices();

            } catch (error) {
                console.error('Update error:', error);
                checkbox.checked = originalState;
                // Use a more user-friendly notification system
                alert(error.message || 'Failed to update cart item');
            } finally {
                checkbox.disabled = false;
            }
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

        // Discount functions
        async function applyDiscount(code) {
            try {
                const response = await fetch('../backend/apply_discount.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        discount_code: code,
                        // Remove the filter for active items only
                        cart_items: cartItems // Send all cart items regardless of status
                    }),
                    credentials: 'include'
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Failed to apply discount');
                }

                if (data.success) {
                    activeDiscount = data.discount;
                    discountedServices = data.discounted_services;

                    // Update all cart items with discounted prices
                    cartItems.forEach(item => {
                        if (discountedServices[item.cart_item_id]) {
                            item.discount_applied = parseFloat(discountedServices[item.cart_item_id].discount_amount);
                            item.cart_price = parseFloat(discountedServices[item.cart_item_id].discounted_price);
                        }
                    });

                    // Update UI
                    updateDiscountUI();
                    renderCartItems();

                    return true;
                } else {
                    throw new Error(data.message || 'Discount not applied');
                }
            } catch (error) {
                console.error('Discount error:', error);
                document.getElementById('discountMessage').textContent = error.message;
                document.getElementById('discountMessage').className = 'form-text text-danger';
                return false;
            }
        }

        function updateDiscountUI() {
            const discountInfo = document.getElementById('activeDiscountInfo');
            if (activeDiscount) {
                discountInfo.style.display = 'block';
                document.getElementById('discountName').textContent = activeDiscount.discount_name;

                let discountText = '';
                if (activeDiscount.discount_type === 'percentage') {
                    discountText = `-${activeDiscount.discount_value}%`;
                } else {
                    discountText = `-₱${parseFloat(activeDiscount.discount_value).toFixed(2)}`;
                }

                document.getElementById('discountValue').textContent = discountText;
                document.getElementById('discountMessage').textContent = activeDiscount.discount_description;
                document.getElementById('discountMessage').className = 'form-text text-success';
            } else {
                discountInfo.style.display = 'none';
                document.getElementById('discountMessage').textContent = '';
            }
        }

        // Collect checkout details and show summary
        function collectCheckoutDetails() {
            const orderSummary = document.getElementById('orderSummary');
            const warningMessage = document.getElementById('warningMessage');

            if (!validateCelebrantForm()) {
                warningMessage.innerHTML = '<div class="alert alert-danger">Please complete all required fields first.</div>';
                orderSummary.innerHTML = '<p class="text-center my-4">Please complete the form to see order summary.</p>';
                return;
            }

            warningMessage.innerHTML = '';

            // Change from filtering active items to using all items
            const selectedServices = cartItems; // Instead of .filter(item => item.status === 'active')

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

            // Calculate amounts with discount support
            const originalTotal = selectedServices.reduce((sum, item) => {
                return sum + (parseFloat(item.service_price) * parseInt(item.quantity));
            }, 0);

            const discountedTotal = selectedServices.reduce((sum, item) => {
                return sum + parseFloat(item.cart_price);
            }, 0);

            const totalDiscount = originalTotal - discountedTotal;
            const discountPercentage = originalTotal > 0 ?
                Math.round((totalDiscount / originalTotal) * 100) : 0;

            const depositAmount = discountedTotal * 0.5; // 50% deposit for partial payment
            const remainingBalance = discountedTotal - depositAmount;

            orderSummary.innerHTML = `
        <h4 class="text-center mb-4">Order Summary</h4>
        
        <!-- Pricing Summary Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Pricing Summary</h5>
                ${activeDiscount ? `
                    <div>
                        <span>Discount Applied: ${activeDiscount.discount_name}</span>
                        <span class="discount-badge">
                            ${activeDiscount.discount_type === 'percentage' ? 
                              `-${activeDiscount.discount_value}%` : 
                              `-₱${parseFloat(activeDiscount.discount_value).toFixed(2)}`}
                        </span>
                    </div>
                ` : ''}
            </div>
            <div class="card-body">
                <!-- Subtotal -->
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>₱${originalTotal.toFixed(2)}</span>
                </div>
                
                <!-- Discount Line (only shown if discount applied) -->
                ${totalDiscount > 0 ? `
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount:</span>
                        <span>-₱${totalDiscount.toFixed(2)}</span>
                    </div>
                ` : ''}
                
                <hr>
                
                <!-- Total Amount -->
                <div class="d-flex justify-content-between align-items-center">
                    <h5>Total Amount:</h5>
                    <h5 class="text-success">₱${discountedTotal.toFixed(2)}</h5>
                </div>
                
                <!-- Payment Breakdown
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between">
                        <span>Deposit (50%):</span>
                        <span>₱${depositAmount.toFixed(2)}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Remaining Balance:</span>
                        <span>₱${remainingBalance.toFixed(2)}</span>
                    </div>
                </div>-->
            </div>
        </div>
        
        <!-- Selected Services Card -->
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
                            ${service.quantity > 1 ? `<div class="text-muted">Qty: ${service.quantity}</div>` : ''}
                        </div>
                        <div>
                            ${service.discount_applied ? `
                                <div class="text-end">
                                    <span class="original-price">₱${(service.service_price * service.quantity).toFixed(2)}</span>
                                    <span class="discounted-price">₱${service.cart_price.toFixed(2)}</span>
                                    <span class="discount-badge">Saved ₱${service.discount_applied.toFixed(2)}</span>
                                </div>
                            ` : `
                                <span class="${service.status === 'active' ? 'text-success' : 'text-muted'}">
                                    ₱${service.cart_price.toFixed(2)}
                                </span>
                            `}
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
        
        <!-- Celebrant Details Card -->
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
        
        <!-- Event Details Card -->
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
        
        <!-- Client Details Card -->
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
        
        <!-- Checkout Form -->
        <form id="checkoutForm" method="POST" action="../backend/process_order.php" enctype="multipart/form-data">
            <input type="hidden" name="selected_services" value='${JSON.stringify(selectedServices)}'>
            <input type="hidden" name="event_details" value='${JSON.stringify(eventDetails)}'>
            <input type="hidden" name="total_amount" value="${originalTotal}">
            <input type="hidden" name="discounted_amount" value="${discountedTotal}">
            <input type="hidden" name="discount_amount" value="${totalDiscount}">
            <input type="hidden" name="discount_percentage" value="${discountPercentage}">
            <input type="hidden" name="deposit_amount" value="${depositAmount}">
            <input type="hidden" name="remaining_balance" value="${remainingBalance}">
            ${activeDiscount ? `
                <input type="hidden" name="discount_id" value="${activeDiscount.id}">
                <input type="hidden" name="discount_code" value="${activeDiscount.discount_code}">
                <input type="hidden" name="discount_name" value="${activeDiscount.discount_name}">
            ` : ''}
            
            <?php include("../components/check-boxes.php"); ?>
        </form>
    `;
        }

        // Submit payment
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
                        Swal.fire({
                            title: 'Success!',
                            text: 'Your order has been placed successfully',
                            icon: 'success',
                            confirmButtonText: 'View Order'
                        }).then(() => {
                            window.location.href = '../User-Pages/invoice.php?order_id=' + data.order_id;
                        });
                    } else {
                        Swal.fire('Error', data.message || 'Failed to place order', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'An error occurred while processing your order', 'error');
                });
        }
    </script>
</body>

</html>