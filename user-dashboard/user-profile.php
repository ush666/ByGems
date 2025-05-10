<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ByGems | Profile</title>
        <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/home.css">

    </head>

    <body style="background-color: #FFF9E5;">
        <?php
        session_start();
        require_once '../includes/db.php';
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        $stmt = $pdo->prepare("SELECT * FROM account WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $loggedInUser = $stmt->fetch();
        //var_dump($loggedInUser);
        //exit;
        
        include("../components/header.php");
        ?>
        <div class="container my-5 pt-5">
            <div class="card shadow rounded-4 p-4">
                <!--<h3 class="mb-2 text-center">User Dashboard</h3>-->

                <style>
                /* Custom Tab Styling 
                .custom-tabs {
                    border-bottom: 2px solid #f0f0f0;
                }*/

                .custom-tabs .nav-link {
                    color: #718EBF;
                    /* Default text color */
                    font-weight: 600;
                    position: relative;
                    transition: all 0.3s ease-in-out;
                }

                .custom-tabs .nav-link.active {
                    color: #A2678A;
                    background-color: rgba(51, 51, 51, 0) !important;
                    /* Pink active text */
                }

                .custom-tabs .nav-link.active::after {
                    content: "";
                    width: 40%;
                    height: 3px;
                    background-color: #ffbb33;
                    /* Yellow underline */
                    position: absolute;
                    bottom: -3px;
                    left: 30%;
                    transition: width 0.3s ease-in-out;
                }

                button:focus,
                input:focus,
                textarea:focus {
                    outline: none !important;
                    box-shadow: none !important;
                    border: #333 solid 1px !important;
                }

                a:active,
                a:focus {
                    outline: none !important;
                    box-shadow: none !important;
                    border: none !important;
                }

                .btn-submit {
                    background-color: #A2678A;
                    color: #fff;
                    font-weight: 600;
                    transition: all 0.3s;
                }


                .btn-submit:hover {
                    background-color: rgb(165, 122, 148);
                    color: #fff;
                }

                .card:hover {
                    transform: translateY(0px) !important;
                }

                img#profilePreview,
                input {
                    border: 1px #ababab solid !important;
                }

                .form-check-input:checked {
                    background-color: #A2678A !important;
                    border-color: #A2678A !important;
                }
                </style>

                <!-- Tabs Navigation -->
                <ul class="nav nav-pills justify-content-start custom-tabs" id="dashboardTabs">
                    <li class="nav-item">
                        <a class="nav-link pb-1 active" id="editProfileTab" data-bs-toggle="pill"
                            href="#editProfile">Edit Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pb-1" id="securityTab" data-bs-toggle="pill" href="#security">Security</a>
                    </li>
                </ul>


                <!-- Tabs Content -->
                <div class="tab-content">
                    <!-- Edit Profile Tab -->
                    <div class="tab-pane fade show active" id="editProfile">
                        <form id="userProfileForm" method="POST" action="../events/update_profile.php"
                            enctype="multipart/form-data">
                            <div class="row g-4 align-items-start">
                                <div class="col-md-4 text-center mt-5">
                                    <img src="<?= !empty($loggedInUser['profile_picture']) ? '../uploads/profile/' . htmlspecialchars($loggedInUser['profile_picture']) : '../img/default.png' ?>"
                                        alt="Profile" id="profilePreview" class="img-fluid rounded-circle mb-3"
                                        style="width: 150px; height: 150px; object-fit: cover;">
                                    <input class="form-control" type="file" id="profileImage" name="profile_picture"
                                        accept="image/*">
                                </div>
                                <div class="col-md-8 mt-5">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Full Name</label>
                                            <input type="text" id="fullName" name="name"
                                                value="<?= htmlspecialchars($loggedInUser['name']) ?>"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" id="username" name="username"
                                                value="<?= htmlspecialchars($loggedInUser['username']) ?>"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" id="email" name="email"
                                                value="<?= htmlspecialchars($loggedInUser['email']) ?>"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="tel" id="phone" name="phone"
                                                value="<?= ($loggedInUser['phone'] ?? '') ?>" class="form-control"
                                                maxlength="15" pattern="[0-9+ ]*">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" id="dob" name="dob"
                                                value="<?= htmlspecialchars($loggedInUser['birthday'] ?? '') ?>"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" id="address" name="address"
                                                value="<?= ($loggedInUser['address'] ?? '') ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-end text-center">
                                <button type="submit" class="btn btn-submit px-5">Update Profile</button>
                            </div>
                        </form>

                    </div>

                    <!-- Security Tab -->
                    <div class="tab-pane fade mt-4" id="security">
                        <h4>Change Password</h4>
                        <form class="column" method="POST" action="../events/change_password.php">
                            <div class="mb-3 col-md-6">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" name="currentPassword" id="currentPassword" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" name="newPassword" id="newPassword" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control"
                                    required>
                            </div>
                            <div class="mt-3 d-flex justify-content-end text-center">
                                <button type="submit" class="btn btn-submit" style="width: 18%;">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script type="module" src="../ionicons/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="../ionicons/dist/ionicons/ionicons.js"></script>
        <script src="../bootstrap/dist/js/bootstrap.bundle.min.js"></script>

        <script>
        document.getElementById('profileImage').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profilePreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
        </script>


        <?php if (isset($_SESSION['alert'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        Swal.fire({
            icon: '<?= $_SESSION['alert']['type'] ?>',
            title: '<?= $_SESSION['alert']['message'] ?>',
            timer: 5000,
            width: '400px',
            showConfirmButton: false
        });
        </script>
        <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>

        <script>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        Swal.fire({
            title: 'Profile Updated!',
            text: 'Your profile has been successfully updated.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
        // Optionally remove ?success=1 from the URL without reloading
        if (window.history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url.pathname);
        }
        <?php endif; ?>
        </script>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'email-taken'): ?>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Email already in use',
            text: 'That email address is already associated with another account.',
            confirmButtonColor: '#d33'
        });
        </script>
        <?php endif; ?>
    </body>

</html>