<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ByGems | Home</title>
    <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">

    <!-- FullCalendar & jQuery -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

</head>

<body>

    <?php
    $home = "font-bold";
    include("../components/header.php");
    ?>

    <!-- Hero Carousel -->
    <section id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="hover">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../img/landing-page.png" class="d-block w-100" alt="Party Image 1">
            </div>
            <div class="carousel-item">
                <img src="../img/landing-page-1.png" class="d-block w-100" alt="Party Image 2">
            </div>
            <div class="carousel-item">
                <img src="../img/landing-page-2.png" class="d-block w-100" alt="Party Image 3">
            </div>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </section>


    <!-- CTA Section -->
    <section class="cta-section mt-4">
        <div class="container">
            <div class="row align-items-center">
                <!-- Text Content -->
                <div class="col-lg-6">
                    <h1 class="fw-bold">Book Your Dream Party with ByGems!</h1>
                    <p class="text-muted">
                        Dive into a world of fun and celebration by scheduling your next event with us.
                        We specialize in creating unforgettable experiences tailored just for you.
                    </p>

                    <div class="cta-card mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3 fs-4"><ion-icon class="text-warning" name="sparkles"></ion-icon></div>
                            <div>
                                <p class="cta-highlight mb-1">Experience the Magic of ByGems</p>
                                <small class="text-muted">Visit our service catalog and start planning your party
                                    today!</small>
                            </div>
                        </div>
                    </div>

                    <button class="cta-btn text-white">Let’s Get Started!</button>
                </div>

                <!-- Image Content -->
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <img src="../img/cta-img.png" alt="Party Image" class="img-fluid cta-img p-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4">Unleash the Fun with ByGems</h2>
            <div class="row">
                <div class="col-md-4">
                    <h4>
                        <ion-icon size="medium" name="cube-outline"></ion-icon> New Packages
                    </h4>
                    <p>Explore our new party themes and services.</p>
                </div>
                <div class="col-md-4">
                    <h4>
                        <ion-icon size="medium" name="hammer-outline"></ion-icon> Service Catalog
                    </h4>
                    <p>View our catalog of party services and packages.</p>
                </div>
                <div class="col-md-4">
                    <h4>
                        <ion-icon size="medium" name="calendar-outline"></ion-icon> Calendar Booking
                    </h4>
                    <p>Reserve your date with ease through our calendar system.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Calendar Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Calendar Availability</h2>
            <div class="bg-white border p-4 rounded shadow-sm">
                <div id="calendar"></div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 500,
                contentHeight: 'auto',
                events: '../events/fetch_events.php', // Fetch events dynamically
                eventColor: '#007bff', // Default event color
                eventTextColor: '#fff',
            });

            calendar.render();
        });
    </script>


    <!-- Map Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Our Location</h2>
            <div class="row">
                <div class="col-lg-5">
                    <p>
                        We are conveniently located at the heart of the city, offering easy accessibility for all
                        your
                        guests. Our venue offers ample parking space and nearby public transportation options.
                    </p>
                    <p><strong>Address:</strong><br>
                        2F JBS Bldg, Mayor Jaldon Street, Canelar, <br>
                        Zamboanga City, Zamboanga del Sur, Philippines
                    </p>
                </div>
                <div class="col-lg-7">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5601.420811116311!2d122.06889046670028!3d6.914185074009675!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x325041fb34bc7727%3A0x92376904b1e02c01!2sZamboanga%20ISO!5e0!3m2!1sen!2sph!4v1742733777544!5m2!1sen!2sph"
                        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>



    <!-- Testimonial Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">What Our Happy Customers Say</h2>
            <div class="row g-4">

                <!-- Testimonial 1 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm p-4">
                        <div class="d-flex align-items-center mb-2">
                            <img src="../img/user_avatar.png" alt="Emily Johnson" class="rounded-circle me-3">
                            <div>
                                <h5 class="mb-0">Emily Johnson</h5>
                                <small class="text-muted">Event Planner Extraordinaire</small>
                            </div>
                        </div>
                        <p class="text-muted">
                            ByGems transformed my daughter's birthday party into a magical experience! The balloon
                            decorations were stunning, and the magician kept everyone entertained. Highly recommend
                            their services!
                        </p>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm p-4">
                        <div class="d-flex align-items-center mb-2">
                            <img src="../img/user_avatar.png" alt="Marcus Lee" class="rounded-circle me-3">
                            <div>
                                <h5 class="mb-0">Marcus Lee</h5>
                                <small class="text-muted">Family Party Enthusiast</small>
                            </div>
                        </div>
                        <p class="text-muted">
                            We booked an inflatable castle for our family reunion, and it was a huge hit! The kids
                            had a blast, and the adults enjoyed it too. ByGems made everything easy and fun!
                        </p>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm p-4">
                        <div class="d-flex align-items-center mb-2">
                            <img src="../img/user_avatar.png" alt="Samantha Torres" class="rounded-circle me-3">
                            <div>
                                <h5 class="mb-0">Samantha Torres</h5>
                                <small class="text-muted">Birthday Queen</small>
                            </div>
                        </div>
                        <p class="text-muted">
                            I can't thank ByGems enough for their amazing service! The PartyHosts were incredible,
                            keeping the kids engaged and happy. Will definitely use them again!
                        </p>
                    </div>
                </div>

                <!-- Testimonial 4 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm p-4">
                        <div class="d-flex align-items-center mb-2">
                            <img src="../img/user_avatar.png" alt="David Kim" class="rounded-circle me-3">
                            <div>
                                <h5 class="mb-0">David Kim</h5>
                                <small class="text-muted">Corporate Event Coordinator</small>
                            </div>
                        </div>
                        <p class="text-muted">
                            ByGems provided an excellent service for our company picnic. The mascots were
                            delightful, and everyone had a great time. They are my go-to for event planning!
                        </p>
                    </div>
                </div>

                <!-- Testimonial 5 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm p-4">
                        <div class="d-flex align-items-center mb-2">
                            <img src="../img/user_avatar.png" alt="Jessica Wong" class="rounded-circle me-3">
                            <div>
                                <h5 class="mb-0">Jessica Wong</h5>
                                <small class="text-muted">Thrilled Mom</small>
                            </div>
                        </div>
                        <p class="text-muted">
                            From start to finish, ByGems made planning my son's party a breeze. The decorations were
                            beautiful, and the entertainers were top-notch. Highly recommend!
                        </p>
                    </div>
                </div>

                <!-- Testimonial 6 -->
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm p-4">
                        <div class="d-flex align-items-center mb-2">
                            <img src="../img/user_avatar.png" alt="Liam Smith" class="rounded-circle me-3">
                            <div>
                                <h5 class="mb-0">Liam Smith</h5>
                                <small class="text-muted">Satisfied Customer</small>
                            </div>
                        </div>
                        <p class="text-muted">
                            I was impressed by the professionalism of the ByGems team. They took care of every
                            detail, and our event was a smashing success! Thank you, ByGems!
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Services Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <p class="text-uppercase text-muted">Your Party, Our Passion</p>
            <h2 class="fw-bold">Explore Our Unmatched Party Services</h2>

            <!-- Carousel -->
            <div id="servicesCarousel" class="carousel slide my-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../img/m_image.png" class="d-block w-100" style="height: 70vh; object-fit: cover;"
                            alt="Balloons">
                    </div>
                    <div class="carousel-item">
                        <img src="../img/landing-page.png" class="d-block w-100"
                            style="height: 70vh; object-fit: cover;" alt="Party Scene">
                    </div>
                    <div class="carousel-item">
                        <img src="../img/cta-img.png" class="d-block w-100" style="height: 70vh; object-fit: cover;"
                            alt="Entertainment">
                    </div>
                </div>

                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#servicesCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#servicesCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>

                <!-- Indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="0" class="active"
                        aria-current="true"></button>
                    <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#servicesCarousel" data-bs-slide-to="2"></button>
                </div>
            </div>

            <!-- Features -->
            <div class="row text-center g-3">
                <div class="col-6 col-md-3">
                    <div class="p-3 border rounded shadow-sm">
                        <i class="bi bi-lightning-fill"></i>
                        <p class="mb-0">Balloon Arrangements</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 border rounded shadow-sm">
                        <i class="bi bi-lock-fill"></i>
                        <p class="mb-0">Exciting PartyHosts</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 border rounded shadow-sm">
                        <i class="bi bi-music-note-beamed"></i>
                        <p class="mb-0">Magicians & Entertainment</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 border rounded shadow-sm">
                        <i class="bi bi-person-fill"></i>
                        <p class="mb-0">Fun Mascots</p>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="p-3 border rounded shadow-sm">
                        <i class="bi bi-cast"></i>
                        <p class="mb-0">Inflatable Castles</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 border rounded shadow-sm">
                        <i class="bi bi-layers"></i>
                        <p class="mb-0">Themed Parties</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 border rounded shadow-sm">
                        <i class="bi bi-joystick"></i>
                        <p class="mb-0">Interactive Games</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-3 border rounded shadow-sm">
                        <i class="bi bi-heart-fill"></i>
                        <p class="mb-0">Custom Packages</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-4">
                <a href="#" class="btn btn-warning fw-bold me-2">Start Planning Today</a>
                <a href="#" class="btn btn-outline-dark">Learn About Our Offers</a>
            </div>
        </div>
    </section>



    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center">Your Questions Answered</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne">
                            What services do you offer?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show">
                        <div class="accordion-body">We offer party planning, catering, and decoration services.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo">
                            How can I book an event?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse">
                        <div class="accordion-body">You can book directly through our website's calendar system.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <?php
    include("../components/footer.php");
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 400,
                contentHeight: 'auto',
                events: [
                    <?php
                    $eventCount = count($bookedEvents);
                    $counter = 0;
                    foreach ($bookedEvents as $event):
                        $counter++;
                    ?> {
                            title: "<?= htmlspecialchars($event['celebrant_name']); ?>",
                            start: "<?= $event['event_date']; ?>",
                            backgroundColor: "#1cff1c",
                            borderColor: "#1cff1c",
                            textColor: "#ffffff"
                        }
                        <?= $counter < $eventCount ? ',' : '' ?> < !--Avoid trailing comma-- >
                    <?php endforeach; ?>
                ]
            });
            calendar.render();
        });

        $(document).ready(function() {
            $('#eventTable').DataTable();
        });
    </script>
</body>

</html>