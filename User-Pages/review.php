<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ByGems | Reviews</title>
        <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/home.css">
        <link rel="stylesheet" href="../css/review.css">
    </head>

    <body>
        <?php
        $reviews = "font-bold";
        include("../components/header.php");
        require_once '../includes/db.php';
        $reviews = $pdo->query("
            SELECT reviews.*, account.name
            FROM reviews 
            JOIN account ON reviews.user_id = account.user_id 
            ORDER BY reviews.created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="container py-5 mt-5" style="padding-top: 80px !important;">
            <div class="row g-4">
                <?php if (count($reviews) === 0): ?>
                <div class="col-12 text-center">
                    <div class="alert bg-white py-5 review-card" style="transform: translateY(0px);" role="alert">
                        <h4 class="mb-0">No reviews yet</h4>
                        <p class="text-muted mt-2">Be the first to leave a review!</p>
                    </div>
                </div>
                <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                <div class="col-lg-6 col-md-12">
                    <div class="review-card p-4 pb-0">
                        <div class="profile-info">
                            <img src="<?= empty($review['profile_picture']) ? '../img/default.png' : '../uploads/profile_pictures/' . htmlspecialchars($review['profile_picture']) ?>"
                                alt="<?= htmlspecialchars($review['name']) ?>">
                            <div>
                                <div class="profile-name"><?= htmlspecialchars($review['name'])
                                            ?></div>
                                <div class="profile-role">
                                    <?= date("F j, Y g:i A", strtotime($review['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3"><?= nl2br(htmlspecialchars($review['message'])) ?></p>
                        <div class="gallery">
                            <?php
                                    $images = json_decode($review['images'], true) ?? [];
                                    foreach ($images as $imgPath): ?>
                            <img src="../<?= htmlspecialchars($imgPath) ?>" alt="Review image">
                            <?php endforeach; ?>
                        </div>
                        <button class="btn-view" data-bs-toggle="modal"
                            data-bs-target="#modalReview<?= $review['id'] ?>">View</button>
                    </div>
                </div>

                <!-- Modal for this review -->
                <div class="modal fade" id="modalReview<?= $review['id'] ?>" tabindex="-1"
                    aria-labelledby="modalLabel<?= $review['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="profile-info">
                                    <img src="<?= empty($review['profile_picture']) ? '../img/default.png' : '../uploads/profile_pictures/' . htmlspecialchars($review['profile_picture']) ?>"
                                        alt="<?= htmlspecialchars($review['name']) ?>">
                                    <div>
                                        <div class="profile-name"><?= htmlspecialchars($review['name'])
                                                    ?>
                                        </div>
                                        <div class="profile-role">
                                            <?= date("F j, Y g:i A", strtotime($review['created_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><?= nl2br(htmlspecialchars($review['message'])) ?></p>
                                <div class="gallery">
                                    <?php foreach ($images as $imgPath): ?>
                                    <img src="../<?= htmlspecialchars($imgPath) ?>" alt="Review image">
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>



        <!-- Footer -->
        <?php
        include("../components/footer.php");
        ?>
        <script src="../bootstrap-5.3.2-dist\js\bootstrap.bundle.min.js"></script>
    </body>

</html>