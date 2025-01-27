<?php
include '../includes/config.php';
include '../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

include '../templates/header.php';
?>

<!-- Custom CSS for Admin Dashboard -->
<style>
    .dashboard-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .dashboard-container h2 {
        color: #007bff;
        margin-bottom: 20px;
    }

    .dashboard-container p {
        font-size: 18px;
        color: #333;
    }

    .dashboard-links {
        list-style: none;
        padding: 0;
    }

    .dashboard-links li {
        margin-bottom: 15px;
    }

    .dashboard-links a {
        display: block;
        padding: 15px;
        background-color: #007bff;
        color: #ffffff;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .dashboard-links a:hover {
        background-color: #0056b3;
    }
</style>

<div class="dashboard-container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin! Manage your events and view reports here.</p>

    <ul class="dashboard-links">
        <li><a href="reports.php">Event Reports</a></li>
        <li><a href="../index.php">View Events</a></li>
        <li><a href="../create_event.php">Create Event</a></li>
    </ul>
</div>