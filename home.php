<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../Vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#photoCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#photoCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#photoCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>

                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://via.placeholder.com/800x400" class="d-block w-100" alt="Image 1">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>First Slide</h5>
                                    <p>Some description for the first slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://via.placeholder.com/800x400" class="d-block w-100" alt="Image 2">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Second Slide</h5>
                                    <p>Some description for the second slide.</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img src="https://via.placeholder.com/800x400" class="d-block w-100" alt="Image 3">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Third Slide</h5>
                                    <p>Some description for the third slide.</p>
                                </div>
                            </div>
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <script src="Vendor/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                    <script src="Vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
</body>
</html>