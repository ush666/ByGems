<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems | Checkout Page</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/cart.css">
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
                <div class="service-card d-flex align-items-center gap-3 shadow-sm" onclick="toggleServiceCheckbox(this, event)">
                    <input type="checkbox" class="service-checkbox form-check-input">
                    <img src="../img/Image-0.png" alt="Service">
                    <div class="service-details">
                        <strong>Pop-up Package Set A</strong>
                        <p>₱4,650</p>
                    </div>
                    <div class="d-flex flex-column flex-md-row gap-2 gap-md-4">
                        <a class="point text-purple text-center d-flex align-items-center font-2" data-bs-toggle="modal" data-bs-target="#detailsModal" onclick="showDetails('Gemster Host Solo','Solo','₱2,000')"><ion-icon name="open-outline"></ion-icon></a>
                        <a class="point text-danger text-center d-flex align-items-center font-2"><ion-icon name="trash-bin-outline"></ion-icon></a>
                    </div>
                </div>
                <div class="service-card d-flex align-items-center gap-3 shadow-sm" onclick="toggleServiceCheckbox(this, event)">
                    <input type="checkbox" class="service-checkbox form-check-input">
                    <img src="../img/Image-4.png" alt="Service">
                    <div class="service-details">
                        <strong>Gemster Host Solo</strong>
                        <p>₱2,000</p>
                    </div>
                    <div class="d-flex flex-column flex-md-row gap-2 gap-md-4">
                        <a class="point text-purple text-center d-flex align-items-center font-2" data-bs-toggle="modal" data-bs-target="#detailsModal" onclick="showDetails('Gemster Host Solo','Solo','₱2,000')"><ion-icon name="open-outline"></ion-icon></a>
                        <a class="point text-danger text-center d-flex align-items-center font-2"><ion-icon name="trash-bin-outline"></ion-icon></a>
                    </div>
                </div>
                <div class="event-details-footer d-flex justify-content-end position-sticky z-3 bottom-0 left-0">
                    <div class="d-flex justify-content-between d-md-none mb-3 px-3">
                        <p class="text-danger"><strong>Note:</strong> <span class="text-black">Select an Item or Package first</span></p>
                    </div>
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
                            <label>Celebrant’s Age</label>
                            <input type="text" class="form-control" placeholder="Enter Celebrant’s Age" id="celebrantsAge" required>
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
                            <input type="text" class="form-control" placeholder="Enter theme" id="themeColor" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Celebrant’s Gender</label>
                            <input type="text" class="form-control" placeholder="Enter color" id="gender" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Event Type</label>
                            <textarea class="form-control" rows="3" placeholder="Event type description" id="eventType" required></textarea>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Event Location</label>
                            <input type="text" class="form-control" placeholder="Enter Location" id="eventLocation" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Event Time & Date</label>
                            <input type="datetime-local" class="form-control" id="eventDate" required>
                        </div>
                        <div class="w-100 h-1">
                            <hr>
                        </div>
                        <h4>Client's Details</h4>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Client’s Full Name</label>
                                <input type="text" class="form-control" placeholder="Enter Client’s Full Name" id="clientName" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Client’s Phone Number</label>
                                <input type="text" class="form-control" placeholder="Enter Client’s Phone Number" id="phoneNumber" required>
                            </div>
                            <div class="col-md-4 mb-3">
                            </div>
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
                        <h6>
                            Please Add Item(s) and Fill Out the Form First
                        </h6>
                    </div>
                </div>
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
                    <input type="file" id="referencePic" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" id="submitCheckout" class="btn btn-primary">Submit Payment</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .image img {
            border-radius: 10%;
            box-shadow: 2px 2px 6px rgba(215, 159, 99, 0.8), -2px -2px 6px rgba(255, 214, 133, 0.81) !important;
        }

        .img-fluid {
            margin: 0;
            width: 60% !important;
        }
    </style>

    <?php include("../components/footer.php"); ?>
    <script>
        // First tab: enable Next when at least one service is checked.
        const checkboxes = document.querySelectorAll('.service-checkbox');
        const nextBtn = document.getElementById('nextBtn');

        function checkServices() {
            nextBtn.disabled = !Array.from(checkboxes).some(cb => cb.checked);
        }
        checkboxes.forEach(cb => cb.addEventListener('change', checkServices));
        checkServices();

        document.addEventListener("DOMContentLoaded", function() {
            const tabLinks = document.querySelectorAll("#checkoutTabs a");
            const requiredFields = document.querySelectorAll('#celebrantForm [required]');
            const nextBtn2 = document.getElementById('nextBtn2');
            const warningMessage = document.getElementById("warningMessage");

            function checkForm() {
                const allFilled = Array.from(requiredFields).every(f => f.value.trim() !== '');
                nextBtn2.disabled = !allFilled;

                // Show or hide the warning message without affecting the order summary.
                if (!allFilled) {
                    warningMessage.innerHTML = `<p class="text-danger text-center">Please Fill Out the Details first.</p>`;
                } else {
                    warningMessage.innerHTML = ''; // Clear the warning message when valid.
                    // Optionally, call collectCheckoutDetails() here if you want to update the summary automatically.
                    collectCheckoutDetails();
                }
            }

            requiredFields.forEach(f => f.addEventListener('input', checkForm));
            document.querySelectorAll(".service-checkbox").forEach(cb => cb.addEventListener('change', checkForm));
            checkForm();

            // Prevent switching to the third tab if required fields are not filled.
            tabLinks.forEach(link => {
                link.addEventListener("click", function(e) {
                    const targetTab = this.getAttribute("href");
                    if (targetTab === "#eventDetails") {
                        const allFilled = Array.from(requiredFields).every(f => f.value.trim() !== '');
                        const hasSelectedService = document.querySelector(".service-checkbox:checked") !== null;
                        if (!allFilled || !hasSelectedService) {
                            e.preventDefault();
                            checkForm();
                            return false;
                        }
                    }
                });
            });
        });




        // Tab navigation restrictions
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector(".btn-next").addEventListener("click", function(e) {
                e.preventDefault();
                document.querySelector('a[href="#celebrantDetails"]').click();
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });

            document.querySelector(".btn-next-1").addEventListener("click", function(e) {
                e.preventDefault();
                document.querySelector('a[href="#eventDetails"]').click();
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
                collectCheckoutDetails();
            });

            document.querySelector('a[href="#eventDetails"]').addEventListener("click", function(e) {
                e.preventDefault();
                document.querySelector('a[href="#eventDetails"]').click();
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
                collectCheckoutDetails();
            });
        });

        function toggleServiceCheckbox(card, event) {
            // Only toggle if the clicked target is not a link or the checkbox itself.
            if (event.target.closest('a') || event.target.classList.contains('service-checkbox')) {
                return;
            }
            const checkbox = card.querySelector('.service-checkbox');
            checkbox.checked = !checkbox.checked;
            // Dispatch a change event so any listeners update accordingly.
            checkbox.dispatchEvent(new Event('change'));
        }


        function collectCheckoutDetails() {
            // Check if all required fields are filled.
            const requiredFields = document.querySelectorAll('#celebrantForm [required]');
            const allFilled = Array.from(requiredFields).every(f => f.value.trim() !== '');
            let orderSummary = document.getElementById("orderSummary");

            if (!allFilled) {
                orderSummary.innerHTML = `<p class="text-danger text-center">Please fill in all details first.</p>`;
                return;
            }

            // If all required fields are filled, proceed.
            let selectedServices = [];
            document.querySelectorAll(".service-card").forEach(card => {
                let cb = card.querySelector(".service-checkbox");
                if (cb && cb.checked) {
                    selectedServices.push({
                        name: card.querySelector("strong").textContent,
                        price: card.querySelector("p").textContent
                    });
                }
            });

            let eventDetails = {
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
                    zip: document.getElementById('zip').value,
                }
            };

            if (selectedServices.length === 0) {
                orderSummary.innerHTML = `<p class="text-center my-auto">No services selected.</p>`;
                return;
            }

            orderSummary.innerHTML = `
        <h4 class="text-center">Confirm Your Order</h4>
        <h5>Selected Services:</h5>
        <div>
            ${selectedServices.map(s => `
                <div class="service-card d-flex align-items-center gap-3 shadow-sm my-3">
                    <img src="../img/Image-4.png" alt="Service">
                    <div class="service-details">
                        <strong>${s.name}</strong>
                        <p>${s.price}</p>
                    </div>
                </div>
            `).join('')}
        </div>
        <div class="border bg-w p-2 my-2">
            <h5>Celebrant Details:</h5>
            <p><strong>Full Name:</strong> ${eventDetails.firstName} ${eventDetails.middleName} ${eventDetails.lastName} <br>
            <strong>Age:</strong> ${eventDetails.age}</p>
        </div>
        <div class="border bg-w p-2 my-2">
            <h5>Event Details:</h5>
            <p><strong>Theme:</strong> ${eventDetails.theme} (${eventDetails.themeColor})<br>
            <strong>Type:</strong> ${eventDetails.eventType}<br>
            <strong>Location:</strong> ${eventDetails.eventLocation}<br>
            <strong>Date & Time:</strong> ${eventDetails.eventDate}</p>
        </div>
        <div class="border bg-w p-2 my-2">
            <h5>Client Details:</h5>
            <p><strong>Name:</strong> ${eventDetails.clientName}<br>
            <strong>Phone:</strong> ${eventDetails.clientPhone}<br>
            <strong>Address:</strong> ${eventDetails.address.street}, ${eventDetails.address.barangay}, ${eventDetails.address.city}, ${eventDetails.address.province} - ${eventDetails.address.zip}</p>
        </div>
        <form id="checkoutForm" method="POST" action="/place-order" enctype="multipart/form-data">
            <input type="hidden" name="selectedServices" value='${JSON.stringify(selectedServices)}'>
            <input type="hidden" name="eventDetails" value='${JSON.stringify(eventDetails)}'>
            <?php include("../components/check-boxes.php"); ?>
        </form>
    `;

            // (Optional) Add event listeners to remove buttons here if needed.
        }


        // Event Listener to monitor changes in service selection
        document.querySelectorAll(".service-checkbox").forEach(cb => {
            cb.addEventListener("change", updateOrderSummary);
        });

        updateOrderSummary();

        // Modal submission: Upload reference and submit form.
        document.getElementById('submitCheckout').addEventListener('click', function() {
            let fileInput = document.getElementById('referencePic');
            if (fileInput.files.length === 0) {
                alert("Please upload your payment reference image.");
                return;
            }
            // Here you can append the file to the checkout form if needed before submitting.
            document.getElementById('checkoutForm').submit();
        });

        function showDetails(service, variation, price) {
            document.getElementById('modalService').textContent = service;
            document.getElementById('modalVariation').textContent = variation;
            document.getElementById('modalPrice').textContent = price;
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
        });
    </script>
    </script>



</body>

</html>