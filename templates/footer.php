<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
    }

    .events-container {

        flex: 1;
    }

    footer {
        margin-top: 0;

    }
    </style>
</head>

<body>

    <main>
        <div class="events-container">
        </div>
    </main>
    <footer style="background-color: #343a40; color: #ffffff; padding: 20px 0;  text-align: center;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
                <!-- Footer Description -->
                <div style="flex: 1; min-width: 250px; margin-bottom: 20px;">
                    <h3 style="font-size: 20px; margin-bottom: 10px;">Event Management System</h3>
                    <p style="font-size: 14px; line-height: 1.6; color: #cccccc;">
                        Your go-to platform for managing and organizing events seamlessly. Join us and make your events
                        unforgettable!
                    </p>
                </div>

                <!-- Social Media Links -->
                <div style="flex: 1; min-width: 250px; margin-bottom: 20px;">
                    <h3 style="font-size: 20px; margin-bottom: 10px;">Follow Us</h3>
                    <div style="display: flex; justify-content: center; gap: 15px;">
                        <a href="https://facebook.com" target="_blank"
                            style="color: #ffffff; text-decoration: none; font-size: 24px;">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://twitter.com" target="_blank"
                            style="color: #ffffff; text-decoration: none; font-size: 24px;">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://instagram.com" target="_blank"
                            style="color: #ffffff; text-decoration: none; font-size: 24px;">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://linkedin.com" target="_blank"
                            style="color: #ffffff; text-decoration: none; font-size: 24px;">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Copyright Notice -->
                <div style="flex: 1; min-width: 250px; margin-bottom: 20px;">
                    <p style="font-size: 14px; color: #cccccc;">
                        ©
                        <?= date('Y') ?> Event Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>