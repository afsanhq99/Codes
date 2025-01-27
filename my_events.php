<?php
session_start();
include 'includes/config.php';
include 'includes/functions.php';
include 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Fetch events registered by the current user
$userId = $_SESSION['user_id'];
$events = getEventsRegisteredByUser($userId);

include 'templates/header.php';
?>

<div class="container mt-5" style="min-height: calc(100vh - 200px); padding-bottom: 20px;">
    <h2 style="font-size: 28px; font-weight: bold; color: #333; margin-bottom: 20px; text-align: center;">My Events</h2>
    <?php if (empty($events)): ?>
        <div class="alert alert-info"
            style="background-color: #e9f5ff; border-color: #b8daff; color: #004085; padding: 15px; border-radius: 8px; text-align: center;">
            You have not registered for any events yet.
        </div>
    <?php else: ?>
        <table class="table table-bordered"
            style="width: 100%; border-collapse: collapse; margin-top: 20px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <thead>
                <tr style="background-color: #007bff; color: #fff;">
                    <th style="padding: 12px; text-align: left;">Event Name</th>
                    <th style="padding: 12px; text-align: left;">Date</th>
                    <th style="padding: 12px; text-align: left;">Time</th>
                    <th style="padding: 12px; text-align: left;">Location</th>
                    <th style="padding: 12px; text-align: left;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 12px; color: #333;"><?= htmlspecialchars($event['name']) ?></td>
                        <td style="padding: 12px; color: #333;"><?= htmlspecialchars($event['date']) ?></td>
                        <td style="padding: 12px; color: #333;"><?= htmlspecialchars($event['time']) ?></td>
                        <td style="padding: 12px; color: #333;"><?= htmlspecialchars($event['location']) ?></td>
                        <td style="padding: 12px;">
                            <a href="event_details.php?id=<?= $event['id'] ?>" class="btn btn-primary btn-sm"
                                style="background-color: #007bff; border: none; padding: 8px 15px; font-size: 14px; border-radius: 8px; color: #fff; text-decoration: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                View Details
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>