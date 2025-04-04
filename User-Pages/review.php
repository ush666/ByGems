<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ByGems Reviews with Modal</title>
        <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/home.css">
        <link rel="stylesheet" href="../css/review.css">
    </head>

    <body>
        <?php
        $reviews = "font-bold";
        include("../components/header.php");
        ?>
        <div class="container py-5 mt-5" style="padding-top: 80px !important;">
            <div class="row g-4">
                <!-- Review Card 1 -->
                <div class="col-lg-6 col-md-12">
                    <div class="review-card p-4 pb-0">
                        <div class="profile-info">
                            <img src="../img/user_avatar.png" alt="Emily Johnson">
                            <div>
                                <div class="profile-name">Emily Johnson</div>
                                <div class="profile-role">Event Planner Extraordinaire</div>
                            </div>
                        </div>
                        <p>ByGems transformed my daughter's birthday party into a magical experience! The balloon
                            decorations
                            were stunning, and the magician kept everyone entertained. Highly recommend their services!
                        </p>
                        <div class="gallery">
                            <img src="../img/review.png" alt="Party Image 1">
                            <img src="../img/review.png" alt="Party Image 2">
                            <img src="../img/review.png" alt="Party Image 3">
                            <img src="../img/review.png" alt="Party Image 4">
                            <img src="../img/review.png" alt="Party Image 5">
                            <img src="../img/review.png" alt="Party Image 6">
                            <img src="../img/review.png" alt="Party Image 7">
                            <img src="../img/review.png" alt="Party Image 8">
                        </div>
                        <button class="btn-view" data-bs-toggle="modal" data-bs-target="#modalReview">View</button>
                    </div>
                </div>

                <!-- Review Card 2 -->
                <div class="col-lg-6 col-md-12">
                    <div class="review-card p-4 pb-0">
                        <div class="profile-info">
                            <img src="../img/user_avatar.png" alt="Emily Johnson">
                            <div>
                                <div class="profile-name">Emily Johnson</div>
                                <div class="profile-role">Event Planner Extraordinaire</div>
                            </div>
                        </div>
                        <p>ByGems transformed my daughter's birthday party into a magical experience! The balloon
                            decorations
                            were stunning, and the magician kept everyone entertained. Highly recommend their services!
                        </p>
                        <div class="gallery">
                            <img src="../img/review.png" alt="Party Image 1">
                            <img src="../img/review.png" alt="Party Image 2">
                            <img src="../img/review.png" alt="Party Image 3">
                            <img src="../img/review.png" alt="Party Image 4">
                            <img src="../img/review.png" alt="Party Image 5">
                            <img src="../img/review.png" alt="Party Image 6">
                            <img src="../img/review.png" alt="Party Image 7">
                            <img src="../img/review.png" alt="Party Image 8">
                        </div>
                        <button class="btn-view" data-bs-toggle="modal" data-bs-target="#modalReview">View</button>
                    </div>
                </div>

                <!-- Review Card 3 -->
                <div class="col-lg-6 col-md-12">
                    <div class="review-card p-4 pb-0">
                        <div class="profile-info">
                            <img src="../img/user_avatar.png" alt="Emily Johnson">
                            <div>
                                <div class="profile-name">Emily Johnson</div>
                                <div class="profile-role">Event Planner Extraordinaire</div>
                            </div>
                        </div>
                        <p>ByGems transformed my daughter's birthday party into a magical experience! The balloon
                            decorations
                            were stunning, and the magician kept everyone entertained. Highly recommend their services!
                        </p>
                        <div class="gallery">
                            <img src="../img/review.png" alt="Party Image 1">
                            <img src="../img/review.png" alt="Party Image 2">
                            <img src="../img/review.png" alt="Party Image 3">
                            <img src="../img/review.png" alt="Party Image 4">
                            <img src="../img/review.png" alt="Party Image 5">
                            <img src="../img/review.png" alt="Party Image 6">
                            <img src="../img/review.png" alt="Party Image 7">
                            <img src="../img/review.png" alt="Party Image 8">
                        </div>
                        <button class="btn-view" data-bs-toggle="modal" data-bs-target="#modalReview">View</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalReview" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="profile-info">
                            <img src="../img/user_avatar.png" alt="Emily Johnson">
                            <div>
                                <div class="profile-name">Emily Johnson</div>
                                <div class="profile-role">Event Planner Extraordinaire</div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">


                        <p>ByGems transformed my daughter's birthday party into a magical experience! The balloon
                            decorations
                            were stunning, and the magician kept everyone entertained. Highly recommend their services!
                        </p>

                        <div class="gallery">
                            <img src="../img/review.png" alt="Party Image 1">
                            <img src="../img/review.png" alt="Party Image 2">
                            <img src="../img/review.png" alt="Party Image 3">
                            <img src="../img/review.png" alt="Party Image 4">
                            <img src="../img/review.png" alt="Party Image 5">
                            <img src="../img/review.png" alt="Party Image 6">
                            <img src="../img/review.png" alt="Party Image 7">
                            <img src="../img/review.png" alt="Party Image 8">
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php
        include("../components/footer.php");
        ?>
        <script src="../bootstrap-5.3.2-dist\js\bootstrap.bundle.min.js"></script>
    </body>

</html>