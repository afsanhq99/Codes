<?php
include 'includes/config.php';
include 'includes/functions.php';
include 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$results = searchEventsAndAttendees($searchTerm);

include 'templates/header.php';
?>

<div class="container mt-4">
    <h2>Search Results</h2>

    <?php if (empty($results['events']) && empty($results['attendees'])): ?>
        <p>No results found for "<?= htmlspecialchars($searchTerm) ?>".</p>
    <?php else: ?>
        <?php if (!empty($results['events'])): ?>
            <h3>Events:</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['events'] as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['name']) ?></td>
                            <td><?= htmlspecialchars($event['description']) ?></td>
                            <td><?= htmlspecialchars($event['date']) ?></td>
                            <td><?= htmlspecialchars($event['location']) ?></td>
                            <td>
                                <a href="event_details.php?id=<?= $event['id'] ?>" class="btn btn-info btn-sm">View</a>
                                <?php if (isAdmin() || $event['created_by'] == $_SESSION['user_id']): ?>
                                    <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if (!empty($results['attendees'])): ?>
            <h3>Attendees:</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Event Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['attendees'] as $attendee): ?>
                        <tr>
                            <td><?= htmlspecialchars($attendee['username']) ?></td>
                            <td><?= htmlspecialchars($attendee['email']) ?></td>
                            <td><?= htmlspecialchars($attendee['event_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary">Back to Events</a>
</div>

<?php include 'templates/footer.php'; ?>