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

// Fetch the number of registered attendees for the event
$registeredAttendees = getRegisteredAttendeesCount($eventId);

// Calculate remaining capacity
$remainingCapacity = $event['max_capacity'] - $registeredAttendees;

// Check if the current user is already registered for the event
$isRegistered = isUserRegistered($_SESSION['user_id'], $eventId);

include 'templates/header.php';
?>

<div class="container"
    style="max-width: 800px; margin: 50px auto; padding: 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);">
    <h2 style="font-size: 28px; font-weight: bold; color: #333; margin-bottom: 20px; text-align: center;">
        <?= htmlspecialchars($event['name']) ?></h2>
    <p style="font-size: 16px; color: #555; margin-bottom: 15px;"><strong>Description:</strong>
        <?= htmlspecialchars($event['description']) ?></p>
    <p style="font-size: 16px; color: #555; margin-bottom: 15px;"><strong>Date:</strong>
        <?= htmlspecialchars($event['date']) ?></p>
    <p style="font-size: 16px; color: #555; margin-bottom: 15px;"><strong>Time:</strong>
        <?= htmlspecialchars($event['time']) ?></p>
    <p style="font-size: 16px; color: #555; margin-bottom: 15px;"><strong>Location:</strong>
        <?= htmlspecialchars($event['location']) ?></p>
    <p style="font-size: 16px; color: #555; margin-bottom: 15px;"><strong>Max Capacity:</strong>
        <?= htmlspecialchars($event['max_capacity']) ?></p>
    <p style="font-size: 16px; color: #555; margin-bottom: 20px;"><strong>Remaining Capacity:</strong>
        <?= htmlspecialchars($remainingCapacity) ?></p>

    <div class="btn-group" style="display: flex; gap: 10px; justify-content: center;">
        <a href="index.php" class="btn btn-secondary"
            style="background-color: #6c757d; border: none; padding: 10px 20px; font-size: 16px; border-radius: 8px; color: #fff; text-decoration: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: background-color 0.3s ease;">
            Back to Events
        </a>
        <?php if (!isEventCreator($_SESSION['user_id'], $event) && !$isRegistered): // Hide Register button if user is the creator or already registered 
        ?>
        <a href="register_event.php?id=<?= $event['id'] ?>" class="btn btn-primary"
            style="background-color: #007bff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 8px; color: #fff; text-decoration: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: background-color 0.3s ease;">
            Register
        </a>
        <?php endif; ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>