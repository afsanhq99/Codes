<?php
include '../includes/config.php';
include '../includes/functions.php';
include '../includes/auth.php';

if (!isAdmin()) {
    header("Location: ../login.php");
    exit;
}

$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;
$event = null; // Initialize $event to null

if ($eventId) {
    $event = getEventDetails($eventId);
}

if (isset($_GET['download']) && $eventId && $event) {
    $users = getRegisteredUsersForEvent($eventId);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $event['name'] . '_attendees.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('Username', 'Email'));

    foreach ($users as $user) {
        fputcsv($output, array($user['username'], $user['email']));
    }

    fclose($output);
    exit;
}

include '../templates/header.php';
?>

<div class="container"
    style="font-family: Arial, sans-serif; margin: 0; padding: 20px; max-width: 1200px; margin: 0 auto;">
    <h2 style="color: #333; margin-bottom: 20px;">Event Reports</h2>
    <form method="get">
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="event_id" style="display: block; margin-bottom: 5px;">Select Event:</label>
            <select class="form-control" name="event_id" required style="width: 100%; padding: 10px; font-size: 16px;">
                <option value="">-- Select Event --</option>
                <?php
                $allEvents = $db->query("SELECT id, name FROM events");
                while ($row = $allEvents->fetch(PDO::FETCH_ASSOC)):
                ?>
                    <option value="<?= $row['id'] ?>" <?= $eventId == $row['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"
            style="background-color: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer;">Generate
            Report</button>
        <?php if ($eventId && $event): ?>
            <a href="?event_id=<?= $eventId ?>&download=true" class="btn btn-success"
                style="background-color: #28a745; color: white; padding: 10px 20px; text-decoration: none; display: inline-block; margin-left: 10px;">Download
                CSV</a>
        <?php endif; ?>
    </form>
    <?php if ($eventId && $event): ?>
        <h3 style="color: #333; margin-top: 20px;">Attendees for <?= htmlspecialchars($event['name']) ?>:</h3>
        <table class="table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f8f9fa;">
                    <th style="padding: 10px; border: 1px solid #ddd;">Username</th>
                    <th style="padding: 10px; border: 1px solid #ddd;">Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = getRegisteredUsersForEvent($eventId);
                foreach ($users as $user):
                ?>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($user['username']) ?></td>
                        <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($user['email']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>