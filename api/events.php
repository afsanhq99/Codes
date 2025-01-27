<?php
include '../includes/config.php';
include '../includes/functions.php';

$eventId = isset($_GET['id']) ? $_GET['id'] : null;

if ($eventId) {
    $event = getEventDetails($eventId);
    if ($event) {
        header('Content-Type: application/json');
        echo json_encode($event);
    } else {
        header('HTTP/1.1 404 Not Found');
        echo json_encode(['error' => 'Event not found']);
    }
} else {
    // Get a list of all events
    $events = getEvents();
    header('Content-Type: application/json');
    echo json_encode($events);
}