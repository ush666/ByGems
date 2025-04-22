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
                    <a class="nav-link pb-1 active" id="editProfileTab" data-bs-toggle="pill" href="#editProfile">Edit Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-1" id="preferencesTab" data-bs-toggle="pill" href="#preferences">Preferences</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pb-1" id="securityTab" data-bs-toggle="pill" href="#security">Security</a>
                </li>
            </ul>


            <!-- Tabs Content -->
            <div class="tab-content">
                <!-- Edit Profile Tab -->
                <div class="tab-pane fade show active" id="editProfile">
                    <form id="userProfileForm" enctype="multipart/form-data">
                        <div class="row g-4 align-items-start">
                            <!-- Profile Picture Upload -->
                            <div class="col-md-4 text-center mt-5">
                                <img src="../img/default.png" alt="Profile" id="profilePreview" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                <input class="form-control" type="file" id="profileImage" accept="image/*">
                            </div>

                            <!-- User Info -->
                            <div class="col-md-8 mt-5">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="fullName" class="form-label">Full Name</label>
                                        <input type="text" id="fullName" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" id="username" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" id="email" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input type="date" id="dob" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" id="address" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" id="city" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="mt-4 d-flex justify-content-end text-center">
                            <button type="submit" class="btn btn-submit px-5">Update Profile</button>
                        </div>
                    </form>
                </div>

                <!-- Preferences Tab -->
                <div class="tab-pane fade mt-4" id="preferences">
                    <h4>Preferences</h4>
                    <form>
                        <div class="form-check mb-3">
                            <input class="form-check-input" disabled type="checkbox" id="darkMode">
                            <label class="form-check-label" for="darkMode">Enable Dark Mode</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" checked type="checkbox" id="emailNotifications">
                            <label class="form-check-label" for="emailNotifications">Receive Email Notifications</label>
                        </div>

                        <div class="mt-3 d-flex justify-content-end text-center">
                            <button type="submit" class="btn btn-submit">Save Preferences</button>
                        </div>
                    </form>
                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade mt-4" id="security">
                    <h4>Change Password</h4>
                    <form class="column">
                        <div class="mb-3 col-md-6">

                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" id="currentPassword" class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" id="newPassword" class="form-control" required>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" id="confirmPassword" class="form-control" required>
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

</body>

</html>