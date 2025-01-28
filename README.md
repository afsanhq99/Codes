# Event Management System

## Overview

This is a simple, web-based event management system built with pure PHP and MySQL. It allows users to create, manage, and view events, register attendees, and generate event reports.

## Features

- User Authentication (Login, Registration)
- Event Management (Create, Read, Update, Delete)
- Attendee Registration
- Event Dashboard (Pagination, Sorting, Filtering)
- Event Reports (CSV Download for Admins)
- Search Functionality (Bonus)
- AJAX for Event Registration (Bonus)
- JSON API Endpoint for Event Details (Bonus)

## Technical Requirements

- Backend: Pure PHP (no frameworks)
- Database: MySQL
- Frontend: HTML, CSS, JavaScript (with Bootstrap)
- Security: Password hashing, prepared statements, input validation

## Installation

1. **Clone the repository:**

    ```bash
    git clone <repository_url>
    cd /Codes
    ```

2. **Configuration:**
    - Open `includes/config.php`.
    - Update the database credentials (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`) with your database information.

3. **Web Server:**
    - Make sure you have a web server with PHP and MySQL support (e.g., XAMPP, WAMP, or a hosting provider).
    - Place the project files in the web server's document root (e.g., `htdocs` in XAMPP).
    - Create the a databse and import the "setup.sql"

4. **Run the Application:**
    - Access the application in your web browser (e.g., `http://localhost/[Your folder name]`).

## Login Credentials

**Admin:**

- **Email:** <admin@example.com>
- **Password:** 123456

**User:**

- **Email:** <test@gmail.com>
- **Password:** 123456

## Usage

- **Login/Registration:** Use the provided credentials or register a new user.
- **Event Dashboard:** View, sort, and filter events.
- **Create Event:** Click "Create Event" and fill in the details.
- **Event Details:** Click "View" on an event to see details and register.
- **Edit/Delete:** Admins and event creators can edit or delete events.
- **Admin Dashboard:** Access event reports and other admin functions.
- **Event Reports:** Admins can download attendee lists in CSV format.


