<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS for consistent styling -->
    <style>
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .navbar {
        background-color: #ffffff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1rem 2rem;
    }

    .navbar-brand {
        font-weight: bold;
        color: #007bff !important;
        font-size: 1.5rem;
    }

    .navbar-brand:hover {
        color: #0056b3 !important;
    }

    .nav-link {
        color: #333 !important;
        font-weight: 500;
        margin: 0 0.5rem;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        color: #007bff !important;
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        padding: 0.5rem 1.5rem;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: translateY(-2px);
    }

    .welcome-message {
        font-weight: bold;
        color: #007bff;
        margin-right: 1rem;
    }

    .navbar-toggler {
        border: none;
        outline: none;
    }

    .navbar-toggler-icon {
        background-image: none;
        font-size: 1.5rem;
        color: #007bff;
    }

    .navbar-toggler:focus {
        box-shadow: none;
    }

    .fa-icon {
        margin-right: 0.5rem;
    }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?= isAdmin() ? BASE_URL . 'admin/dashboard.php' : BASE_URL . 'index.php' ?>">
            <i class="fas fa-calendar-alt fa-icon">Event Management System</i>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>admin/dashboard.php">
                        <i class="fas fa-tachometer-alt fa-icon"></i>Admin Dashboard
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="my_events.php">
                        <i class="fas fa-calendar-check fa-icon"></i>My Events
                    </a>
                </li>
                <?php endif; ?>
                <!-- Welcome Message -->
                <li class="nav-item">
                    <span class="nav-link welcome-message">
                        <i class="fas fa-user fa-icon"></i>Welcome,
                        <?= htmlspecialchars($_SESSION['username'] ?? 'Guest') ?>
                    </span>
                </li>
                <!-- Logout Button -->
                <li class="nav-item">
                    <a class="btn btn-danger text-white font-weight-bold"
                        href="<?= isAdmin() ? BASE_URL . 'admin/logout.php' : BASE_URL . 'logout.php' ?>">
                        <i class="fas fa-sign-out-alt fa-icon"></i>Logout
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        <i class="fas fa-sign-in-alt fa-icon"></i>Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">
                        <i class="fas fa-user-plus fa-icon"></i>Register
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>