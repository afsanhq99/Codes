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

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $maxCapacity = $_POST['max_capacity'];

    // Server-side validation
    if (empty($name) || empty($date) || empty($time) || empty($location) || empty($maxCapacity)) {
        $errors[] = "Please fill in all required fields.";
    }
    // Add more validation as needed

    if (empty($errors)) {
        if (updateEvent($eventId, $name, $description, $date, $time, $location, $maxCapacity)) {
            header("Location: event_details.php?id=" . $eventId);
            exit;
        } else {
            $errors[] = "Error updating event.";
        }
    }
}

include 'templates/header.php';
?>

<div class="container">
    <h2>Edit Event</h2>
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($event['name']) ?>"
                required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" name="description"><?= htmlspecialchars($event['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($event['date']) ?>"
                required>
        </div>
        <div class="form-group">
            <label for="time">Time:</label>
            <input type="time" class="form-control" name="time" value="<?= htmlspecialchars($event['time']) ?>"
                required>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" class="form-control" name="location" value="<?= htmlspecialchars($event['location']) ?>"
                required>
        </div>
        <div class="form-group">
            <label for="max_capacity">Max Capacity:</label>
            <input type="number" class="form-control" name="max_capacity"
                value="<?= htmlspecialchars($event['max_capacity']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>