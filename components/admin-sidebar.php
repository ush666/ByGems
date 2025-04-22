<div class="d-flex flex-column p-3 bg-white shadow position-fixed h-100 z-3 top-0 rounded-0 admin-sidebar" style="width: 280px; max-height: 100vh; border-radius: 0 30px 30px 0; overflow-y: auto;">
    <!--<div class="text-center mt-5 pt-3" style="display: flex; flex-direction: column; align-items: center;">
        <img src="../img/default.png" class="rounded-circle mb-2" alt="Profile" style="width: 60px; height: 60px; object-fit: cover;">
        <h6 class="mb-0">
            <span><?= ucfirst($user['username']) ?></span>
        </h6>

        <small class="text-muted bold"><?= ucfirst($role['role']) ?></small>
    </div>-->
    <ul class="nav nav-pills flex-column mb-auto mt-5 pt-4">
        <li class="nav-item">
            <a href="../Staff-Pages/cms_dashboard.php" class="nav-link text-dark <?= $dashboard ?>">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="../Staff-Pages/event_management.php" class="nav-link text-dark <?= $event_manager ?>">
                Event Manager
            </a>
        </li>
        <li><a href="../Staff-Pages/authority_settings.php" class="nav-link text-dark <?= $authority_settings ?>">Authority Settings</a></li>
        <li><a href="../Staff-Pages/reports.php" class="nav-link text-dark <?= $reports ?>">Reports</a></li>
        <!--
            <li><a href="#" class="nav-link text-dark">Edit Packages</a></li>
            <li><a href="#" class="nav-link text-dark">Event Queries</a></li>
            <li><a href="#" class="nav-link text-dark">Site Style Manager</a></li>
            -->
        <hr>
        <li><a href="../Staff-Pages/packages&services.php" class="nav-link text-dark <?= $packageServices ?>">Packages & Services</a></li>
        <!--<li><a href="../Staff-Pages/orders.php" class="nav-link text-dark <?= $request ?>">Request Management</a></li>-->

    </ul><!--
    <ul class="nav flex-column">
        <li><a href="#" class="nav-link text-dark">Notifications</a></li>
        <li><a href="#" class="nav-link text-dark">Settings</a></li>
    </ul>-->
    <div class="mt-auto">
        <a href="../includes/logout.php" class="btn btn-purple w-100 mb-3" style="border-radius: 20px;">Log out</a>
        <div class="d-flex align-items-center gap-2">
            <img src="../img/default.png" alt="Profile Picture" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            <div>
                <small class="text-muted">Welcome back <ion-icon name="hand-left" class="text-warning"></ion-icon></small><br>
                <strong><?= ucfirst($user['name']) ?></strong>
            </div>
        </div>
    </div>
</div>