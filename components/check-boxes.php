<style>
    .custom-radio {
        accent-color: #F97316;
    }

    input[type="radio"]:focus {
        outline: none !important;
        box-shadow: none !important;
    }
</style>
<div class="row mb-3">
    <div class="container mt-4 col-4">
        <div class="shadow-sm p-3 border rounded-3" style="background: #fff; max-width: 300px;">
            <legend class="fs-5 fw-semibold text-orange">Payment Method</legend>

            <label for="html" class="d-flex align-items-center p-3 rounded border mb-2 text-dark fw-medium bg-light hover-effect">
                <div class="me-3">
                    <img src="../img/GCash.png" alt="GCash" width="40">
                </div>
                GCash
                <input type="radio" name="status" id="html" class="ms-auto custom-radio" checked>
            </label>

            <!-- Uncomment below to include other payment options -->
            <!-- 
            <label for="css" class="d-flex align-items-center p-3 rounded border mb-2 text-dark fw-medium bg-light hover-effect disabled">
                <div class="me-3 text-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="20">
                        <path d="M1.5 0h21l-1.91 21.563L11.977 24l-8.565-2.438L1.5 0zm17.09 4.413L5.41 4.41l.213 2.622 10.125.002-.255 2.716h-6.64l.24 2.573h6.182l-.366 3.523-2.91.804-2.956-.81-.188-2.11h-2.61l.29 3.855L12 19.288l5.373-1.53L18.59 4.414z"></path>
                    </svg>
                </div>
                CSS
                <input type="radio" name="status" id="css" class="ms-auto custom-radio" disabled>
            </label>

            <label for="javascript" class="d-flex align-items-center p-3 rounded border text-dark fw-medium bg-light hover-effect disabled">
                <div class="me-3 text-orange">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 512 512" width="20">
                        <g>
                            <path d="M1.008,0.5C0.438,0.583,0.48,1.27,0.521,1.958c0,169.668,0,339.31,0,508.974c169.364,1.135,340.808,0.162,510.979,0.486c0-170.309,0-340.61,0-510.918 C341.342,0.5,171.167,0.5,1.008,0.5z M259.893,452.167c-11.822,11.919-30.478,18.938-53.429,18.938 c-37.643,0-58.543-18.34-71.884-43.711c12.842-8.2,25.966-16.122,39.344-23.795c5.456,15.262,23.886,32.42,44.683,21.857 c13.183-6.699,11.661-27.01,11.661-49.054c0-45.773,0-98.578,0-139.872c-0.042-0.688-0.083-1.375,0.482-1.458 c15.707,0,31.413,0,47.116,0c0,36.788,0,78.402,0,117.529C277.866,395.199,280.91,430.988,259.893,452.167z M470.696,409.917 c-2.674,39.884-35.243,61.063-79.17,61.188c-43.062,0.124-70.624-19.013-87.433-48.567c12.085-8.317,25.778-15.017,38.375-22.822 c10.08,15.761,27.537,30.91,53.429,28.652c16.131-1.406,34.856-14.555,24.285-34.482c-5.127-9.66-17.516-14.567-28.656-19.425 c-35.352-15.424-76.828-29.571-72.861-84.992c1.327-18.514,9.852-31.525,20.889-40.796c11.311-9.5,26.46-15.867,46.629-16.511 c36.629-1.173,56.723,15.12,70.429,37.884c-11.664,8.891-24.514,16.608-37.401,24.281c-4.229-12.995-24.644-25.658-41.772-17.969 c-7.789,3.493-14.788,13.761-10.684,26.224c3.66,11.115,18.589,17.199,30.599,22.344 C433.706,340.486,474.331,355.693,470.696,409.917z"></path>
                        </g>
                    </svg>
                </div>
                JavaScript
                <input type="radio" name="status" id="javascript" class="ms-auto custom-radio" disabled>
            </label>
            -->
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
                <input type="radio" name="payment" id="partial" class="ms-auto custom-radio" checked>
            </label>
            <label for="full" class="d-flex align-items-center p-3 rounded border mb-2 text-dark fw-medium bg-light hover-effect">
                <div class="me-3">
                    <img src="../img/fully-paid.png" alt="fully-payment" width="40">
                </div>
                Fully Payment
                <input type="radio" name="payment" id="full" class="ms-auto custom-radio" checked>
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

