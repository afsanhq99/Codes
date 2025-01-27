<?php
include 'includes/config.php';
include 'includes/functions.php';
include 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$eventId = $_GET['id'];
$event = getEventDetails($eventId);

if (!$event) {
    header("Location: index.php");
    exit;
}

// Authorization check
if (!isAdmin() && $event['created_by'] != $_SESSION['user_id']) {
    header("Location: index.php");
    exit;
}

if (deleteEvent($eventId)) {
    header("Location: index.php");
    exit;
} else {
    echo "Error deleting event.";
}
