<?php
function getEvents($page = 1, $perPage = 10, $sortField = 'date', $sortOrder = 'ASC', $filterField = null, $filterValue = null)
{
    global $db;

    $offset = ($page - 1) * $perPage;
    $validSortFields = ['name', 'date', 'location'];
    $validSortOrders = ['ASC', 'DESC'];

    if (!in_array($sortField, $validSortFields)) {
        $sortField = 'date';
    }
    if (!in_array($sortOrder, $validSortOrders)) {
        $sortOrder = 'ASC';
    }

    $sql = "SELECT * FROM events";
    $params = [];
    $types = "";

    if ($filterField && $filterValue && in_array($filterField, $validSortFields)) {
        $sql .= " WHERE {$filterField} LIKE ?";
        $params[] = "%" . $filterValue . "%";
        $types .= "s";
    }

    $sql .= " ORDER BY {$sortField} {$sortOrder} LIMIT ?, ?";
    $params = array_merge($params, [$offset, $perPage]);
    $types .= "ii";
    $stmt = $db->prepare($sql);
    $paramIndex = 1;
    if ($filterField && $filterValue && in_array($filterField, $validSortFields)) {
        $stmt->bindValue($paramIndex, "%" . $filterValue . "%");
        $paramIndex++;
    }
    $stmt->bindParam($paramIndex, $offset, PDO::PARAM_INT);
    $paramIndex++;
    $stmt->bindParam($paramIndex, $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $events = [];
    foreach ($result as $row) {
        $events[] = $row;
    }

    return $events;
}

function createEvent($name, $description, $date, $time, $location, $maxCapacity, $userId)
{
    global $db;

    $stmt = $db->prepare("INSERT INTO events (name, description, date, time, location, max_capacity, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $description);
    $stmt->bindParam(3, $date);
    $stmt->bindParam(4, $time);
    $stmt->bindParam(5, $location);
    $stmt->bindParam(6, $maxCapacity, PDO::PARAM_INT);
    $stmt->bindParam(7, $userId, PDO::PARAM_INT);

    return $stmt->execute();
}

function getEventDetails($eventId)
{
    global $db;

    $stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bindParam(1, $eventId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateEvent($eventId, $name, $description, $date, $time, $location, $maxCapacity)
{
    global $db;

    $stmt = $db->prepare("UPDATE events SET name = ?, description = ?, date = ?, time = ?, location = ?, max_capacity = ? WHERE id = ?");
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $description);
    $stmt->bindParam(3, $date);
    $stmt->bindParam(4, $time);
    $stmt->bindParam(5, $location);
    $stmt->bindParam(6, $maxCapacity, PDO::PARAM_INT);
    $stmt->bindParam(7, $eventId, PDO::PARAM_INT);

    return $stmt->execute();
}

function deleteEvent($eventId)
{
    global $db;

    $stmt = $db->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bindParam(1, $eventId, PDO::PARAM_INT);

    return $stmt->execute();
}

function registerForEvent($eventId, $userId)
{
    global $db;

    // Check if already registered
    $stmt = $db->prepare("SELECT id FROM registrations WHERE event_id = ? AND user_id = ?");
    $stmt->bindParam(1, $eventId, PDO::PARAM_INT);
    $stmt->bindParam(2, $userId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetch()) {
        return "already_registered";
    }

    // Check capacity
    $stmt = $db->prepare("SELECT COUNT(*) as num_registered FROM registrations WHERE event_id = ?");
    $stmt->bindParam(1, $eventId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $numRegistered = $row['num_registered'];

    $stmt = $db->prepare("SELECT max_capacity FROM events WHERE id = ?");
    $stmt->bindParam(1, $eventId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxCapacity = $row['max_capacity'];

    if ($numRegistered >= $maxCapacity) {
        return "full";
    }

    // Register
    $stmt = $db->prepare("INSERT INTO registrations (event_id, user_id) VALUES (?, ?)");
    $stmt->bindParam(1, $eventId, PDO::PARAM_INT);
    $stmt->bindParam(2, $userId, PDO::PARAM_INT);
    if ($stmt->execute()) {
        return "success";
    } else {
        return "error";
    }
}

function getRegisteredUsersForEvent($eventId)
{
    global $db;

    $stmt = $db->prepare("
        SELECT u.username, u.email 
        FROM registrations r
        JOIN users u ON r.user_id = u.id
        WHERE r.event_id = ?
    ");
    $stmt->bindParam(1, $eventId, PDO::PARAM_INT);
    $stmt->execute();

    $users = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }

    return $users;
}

function searchEventsAndAttendees($searchTerm)
{
    global $db;

    // Search events
    $stmt = $db->prepare("SELECT * FROM events WHERE name LIKE ? OR description LIKE ? OR location LIKE ?");
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->bindValue(1, $likeTerm);
    $stmt->bindValue(2, $likeTerm);
    $stmt->bindValue(3, $likeTerm);
    $stmt->execute();
    $eventResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $events = [];
    foreach ($eventResults as $row) {
        $events[] = $row;
    }

    // Search attendees
    $stmt = $db->prepare("
        SELECT u.username, u.email, e.name as event_name
        FROM users u
        JOIN registrations r ON u.id = r.user_id
        JOIN events e ON r.event_id = e.id
        WHERE u.username LIKE ? OR u.email LIKE ?
    ");
    $stmt->bindValue(1, $likeTerm);
    $stmt->bindValue(2, $likeTerm);
    $stmt->execute();
    $attendeeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $attendees = [];
    foreach ($attendeeResults as $row) {
        $attendees[] = $row;
    }

    return ['events' => $events, 'attendees' => $attendees];
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function validateTime($time, $format = 'H:i')
{
    error_log("Validating Time: " . $time);

    $d = DateTime::createFromFormat($format, $time);

    if ($d && $d->format($format) === $time) {
        return true;
    }

    return false;
}


function getEventsRegisteredByUser($userId)
{
    global $db;

    try {
        $stmt = $db->prepare("
            SELECT e.id, e.name, e.date, e.time, e.location 
            FROM events e
            JOIN registrations r ON e.id = r.event_id
            WHERE r.user_id = ?
        ");
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching events: " . $e->getMessage());
        return [];
    }
}


function getRegisteredAttendeesCount($eventId)
{
    global $db;
    $stmt = $db->prepare("SELECT COUNT(*) FROM registrations WHERE event_id = ?");
    $stmt->execute([$eventId]);
    return $stmt->fetchColumn();
}


function isEventCreator($userId, $event)
{
    // Check if the user ID matches the event's creator ID
    return isset($event['created_by']) && ($userId == $event['created_by']);
}