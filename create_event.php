<?php
include 'includes/config.php';
include 'includes/functions.php';
include 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
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
        if (createEvent($name, $description, $date, $time, $location, $maxCapacity, $_SESSION['user_id'])) {
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Error creating event.";
        }
    }
}

include 'templates/header.php';
?>

<div class="container">
    <h2>Create Event</h2>
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
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" name="date" required>
        </div>
        <div class="form-group">
            <label for="time">Time:</label>
            <input type="time" class="form-control" name="time" required>
        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" class="form-control" name="location" required>
        </div>
        <div class="form-group">
            <label for="max_capacity">Max Capacity:</label>
            <input type="number" class="form-control" name="max_capacity" required>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>