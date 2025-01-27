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

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = registerForEvent($eventId, $_SESSION['user_id']);

    // Prepare the response array for AJAX
    $response = array();
    if ($result == "success") {
        $response['status'] = "success";
        $response['message'] = "Successfully registered for the event!";
    } elseif ($result == "already_registered") {
        $response['status'] = "error";
        $response['message'] = "You are already registered for this event.";
    } elseif ($result == "full") {
        $response['status'] = "error";
        $response['message'] = "Sorry, this event is full.";
    } else {
        $response['status'] = "error";
        $response['message'] = "Error registering for the event.";
    }

    // Set the header to indicate JSON content type for AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Stop further execution after sending the JSON response for AJAX
}

include 'templates/header.php';
?>

<div class="container d-flex flex-column align-items-center justify-content-center my-5"
    style="max-width: 600px; padding: 30px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);">
    <h2 class="text-center mb-4" style="font-size: 28px; font-weight: bold; color: #333;">
        Register for: <?= htmlspecialchars($event['name']) ?>
    </h2>

    <!-- Message Display Area -->
    <div id="message" class="text-center mb-4" style="font-size: 16px;"></div>

    <!-- Registration Form -->
    <form id="register-form" method="post" action="register_event.php?id=<?= $eventId ?>" class="w-100 text-center">
        <button type="submit" class="btn btn-primary btn-lg"
            style="background-color: #007bff; border: none; padding: 10px 30px; font-size: 18px; border-radius: 8px; color: #fff; text-decoration: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: background-color 0.3s ease;">
            Confirm Registration
        </button>
    </form>

    <!-- Back to Event Button -->
    <br>
    <a href="event_details.php?id=<?= $eventId ?>" class="btn btn-secondary btn-lg"
        style="background-color: #6c757d; border: none; padding: 10px 30px; font-size: 18px; border-radius: 8px; color: #fff; text-decoration: none; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: background-color 0.3s ease;">
        Back to Event
    </a>
</div>

<!-- Include JavaScript for AJAX handling -->
<script>
document.getElementById('register-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission

    const form = e.target;
    const formData = new FormData(form);
    const messageDiv = document.getElementById('message');

    fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        })
        .catch(error => {
            messageDiv.innerHTML =
                `<div class="alert alert-danger">An error occurred. Please try again.</div>`;
        });
});
</script>

</body>

</html>