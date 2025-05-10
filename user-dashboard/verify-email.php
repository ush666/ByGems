<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Verify New Email</title>
        <link href="../bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/home.css">
    </head>
    <style>
        .form-control:focus {
            color: #000000 !important;
            background-color: none !important;
            border-color: #333 !important;
            box-shadow: none !important;
        }
    </style>

    <body class="d-flex justify-content-center align-items-center" style="height: 100vh; background: #FFF9E5;">
        <div class="card p-5 rounded-4 shadow-lg" style="width: 400px;">
            <h4 class="mb-3 text-center">Verify Your New Email</h4>
            <form method="POST" action="verify-email-handler.php">
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter OTP sent to your new email:</label>
                    <input type="text" name="otp" class="form-control" required maxlength="6">
                </div>
                <button type="submit" class="btn btn-purple text-white bold w-100">Verify</button>
            </form>
        </div>
    </body>

</html>