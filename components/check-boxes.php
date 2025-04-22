<style>
    .custom-radio {
        accent-color: #F97316;
    }

    input[type="radio"]:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .hover-effect:hover {
        background-color: #f8f9fa !important;
        cursor: pointer;
    }

    .text-orange {
        color: #F97316;
    }

    .btn-purple {
        background-color: #6f42c1;
    }

    .btn-shadow {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Payment Selection Section -->
<div class="row mb-3">
    <div class="container mt-4 col-4">
        <div class="shadow-sm p-3 border rounded-3" style="background: #fff; max-width: 300px;">
            <legend class="fs-5 fw-semibold text-orange">Payment Method</legend>

            <label for="gcash" class="d-flex align-items-center p-3 rounded border mb-2 text-dark fw-medium bg-light hover-effect">
                <div class="me-3">
                    <img src="../img/GCash.png" alt="GCash" width="40">
                </div>
                GCash
                <input type="radio" name="payment_method" id="gcash" class="ms-auto custom-radio" value="gcash" checked>
            </label>
            <!--
            <label for="paypal" class="d-flex align-items-center p-3 rounded border mb-2 text-dark fw-medium bg-light hover-effect disabled">
                <div class="me-3">
                    <img src="../img/PayPal.png" alt="PayPal" width="40">
                </div>
                PayPal
                <input type="radio" name="payment_method" id="paypal" class="ms-auto custom-radio" value="paypal" disabled>
            </label>-->
        </div>
    </div>

    <div class="container mt-4 col-4">
        <div class="shadow-sm p-3 border rounded-3" style="background: #fff; max-width: 300px;">
            <legend class="fs-5 fw-semibold text-orange">Payment Type</legend>

            <label for="partial" class="d-flex align-items-center p-3 rounded border mb-2 text-dark fw-medium bg-light hover-effect">
                <div class="me-3">
                    <img src="../img/partial-payment.png" alt="partial-payment" width="40">
                </div>
                Partial Payment
                <input type="radio" name="payment_type" id="partial" class="ms-auto custom-radio" value="partial" checked>
            </label>
            <label for="full" class="d-flex align-items-center p-3 rounded border mb-2 text-dark fw-medium bg-light hover-effect">
                <div class="me-3">
                    <img src="../img/fully-paid.png" alt="fully-payment" width="40">
                </div>
                Fully Paid
                <input type="radio" name="payment_type" id="full" class="ms-auto custom-radio" value="fullypaid">
            </label>
        </div>
    </div>

    <div class="col-4 d-flex flex-column justify-content-start mb-4">
        <div class="d-flex flex-column justify-content-end gap-0 h-50">
            <button type="button" class="m-auto mx-0 w-100 btn btn-purple text-white bold d-flex justify-content-center align-items-center gap-1 btn-shadow" style="width: 20%;" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                <ion-icon name="checkmark-done-circle-outline" class="bold" style="font-size: 1.2rem;"></ion-icon> Check Out
            </button>
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
                <input type="file" name="payment_proof" class="form-control mb-3" required accept="image/*">

                <!-- Terms Agreement -->
                <!-- Add aria-describedby to link the terms text -->
                <!--<input class="form-check-input" type="checkbox" id="agreeTerms" aria-describedby="termsText">
                <label class="form-check-label" for="agreeTerms">
                    I agree to the <span id="termsText" class="fw-bold" style="color: #A2678A; cursor: pointer;">terms and conditions</span>
                </label>-->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="checkoutForm" class="btn btn-purple text-white bold" id="submitPaymentBtn">Submit Payment</button>
            </div>
        </div>
    </div>
</div>