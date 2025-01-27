<?php
include 'includes/config.php';
include 'includes/auth.php';

// Redirect to index if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (empty($username)) {
        $error = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = "Username can only contain letters, numbers, and underscores.";
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $error = "Username must be between 3 and 20 characters long.";
    } elseif (!preg_match('/[a-zA-Z]/', $username)) { // Ensure at least one letter
        $error = "Username must contain at least one letter.";
    } elseif (empty($email)) {
        $error = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif (empty($password)) {
        $error = "Password is required.";
    } elseif (strlen($password) < 6) { // Change this to your desired minimum length
        $error = "Password must be at least 6 characters long.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    }

    if (empty($error)) {
        // Hash the password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $userExists = $stmt->fetchColumn() > 0;

            if ($userExists) {
                $error = "Username or email already exists.";
            } else {
                // Insert the new user into the database
                $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashedPassword]);
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = "An error occurred during registration. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .register-container {
        max-width: 400px;
        margin: 100px auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .register-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .register-container .form-group {
        margin-bottom: 15px;
    }

    .register-container .btn-primary {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        background-color: #007bff;
        border: none;
    }

    .register-container .btn-primary:hover {
        background-color: #0056b3;
    }

    .register-container p {
        text-align: center;
        margin-top: 15px;
    }

    .register-container a {
        color: #007bff;
        text-decoration: none;
    }

    .register-container a:hover {
        text-decoration: underline;
    }

    .alert {
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($username ?? '') ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email ?? '') ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>

</html>