<div class="d-flex row align-items-center text-align-center mt-4">
    <div class="col-sm-4">
        <p class="m-0 font-3 font-brown montserrat ps-3">Pickup Date</p>
    </div>
    <div class="col-sm-8">
        <input type="text" class="input-pl-date montserrat shadow-cstm" id="orderDate"
            placeholder="Loading available dates..." name="orderdate" disabled>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderDateInput = document.getElementById('orderDate');
        let bookings = {};
        const maxOrdersPerDay = 30;

        async function fetchBookingData() {
            try {
                orderDateInput.placeholder = "Loading available dates...";
                const response = await fetch('/api/booking-data');

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                bookings = data.orders || {};

            } catch (error) {
                console.error('Error fetching booking data:', error);
                // Fallback - allow all dates if API fails
                bookings = {};
                orderDateInput.placeholder = "Select a date (availability not checked)";
            } finally {
                initDatePicker();
                orderDateInput.disabled = false;
            }
        }

        function initDatePicker() {
            const availableDates = [];
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const currentDate = new Date(today);
            const endDate = new Date();
            endDate.setFullYear(today.getFullYear() + 1);

            while (currentDate <= endDate) {
                const dateStr = formatDate(currentDate);
                const bookingsCount = bookings[dateStr] || 0;

                if (bookingsCount < maxOrdersPerDay) {
                    availableDates.push(dateStr);
                }

                currentDate.setDate(currentDate.getDate() + 1);
            }

            const picker = flatpickr(orderDateInput, {
                dateFormat: "Y-m-d",
                minDate: "today",
                enable: availableDates.length ? availableDates : undefined,
                onChange: function(selectedDates, dateStr) {
                    console.log("Selected date:", dateStr);
                    // You could add additional validation here
                },
                disableMobile: true,
                onReady: function() {
                    if (availableDates.length === 0) {
                        orderDateInput.placeholder = "No available dates found";
                    }
                }
            });

            // If API failed, enable all dates
            if (Object.keys(bookings).length === 0) {
                picker.set('enable', []);
            }
        }

        function formatDate(date) {
            return date.toISOString().split('T')[0]; // YYYY-MM-DD
        }

        fetchBookingData();
    });
</script>