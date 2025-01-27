<?php
include 'includes/config.php';
include 'includes/functions.php';
include 'includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$sortField = isset($_GET['sort']) ? $_GET['sort'] : 'date';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$filterField = isset($_GET['filter_field']) ? $_GET['filter_field'] : null;
$filterValue = isset($_GET['filter_value']) ? $_GET['filter_value'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

// Server-side validation for filter and search
if ($filterField && empty($filterValue)) {
    $filterField = null; // Ignore filter if value is empty
}
if ($search && empty(trim($search))) {
    $search = null; // Ignore search if value is empty
}

$events = getEvents($page, $perPage, $sortField, $sortOrder, $filterField, $filterValue, $search);

// Get total number of events for pagination
$totalEvents = $db->query("SELECT COUNT(*) FROM events")->fetchColumn(); // Corrected line
$totalPages = ceil($totalEvents / $perPage);

include 'templates/header.php';
?>

<!-- JavaScript for client-side validation -->
<script>
    function validateSearch() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput.value.trim() === '') {
            alert('Please enter a search term.');
            return false;
        }
        return true;
    }

    function validateFilter() {
        const filterValueInput = document.querySelector('input[name="filter_value"]');
        if (filterValueInput.value.trim() === '') {
            alert('Please enter a filter value.');
            return false;
        }
        return true;
    }
</script>
<div class="events-container"
    style="max-width: 1200px; margin: 50px auto; padding: 20px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; margin-bottom: 20px; color: #333;">Upcoming Events</h2>
    <div class="row mb-3">
        <!-- Create Event Button -->
        <div class="col-md-6">
            <a href="create_event.php" class="btn btn-primary"
                style="background-color: #007bff; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; color: #fff;">Create
                Event</a>
        </div>

        <!-- Search Section -->
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <!-- Search Form -->
                <form method="get" action="search_results.php" class="form-inline" onsubmit="return validateSearch()">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Search events"
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                            style="width: 200px;">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary"
                                style="background-color: #6c757d; border: none; padding: 10px 20px; font-size: 16px; border-radius: 4px; color: #fff;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Events Table -->
    <table class="table table-striped" style="margin-top: 20px;">
        <thead>
            <tr>
                <th>
                    <a href="?sort=name&order=<?= $sortField == 'name' && $sortOrder == 'ASC' ? 'DESC' : 'ASC' ?>"
                        style="color: #007bff; text-decoration: none;">
                        Name <i class="fas fa-filter" title="Click to sort by Name"></i>
                    </a>
                </th>
                <th>
                    <a href="?sort=date&order=<?= $sortField == 'date' && $sortOrder == 'ASC' ? 'DESC' : 'ASC' ?>"
                        style="color: #007bff; text-decoration: none;">
                        Date <i class="fas fa-filter" title="Click to sort by Date"></i>
                    </a>
                </th>
                <th>
                    <a href="?sort=location&order=<?= $sortField == 'location' && $sortOrder == 'ASC' ? 'DESC' : 'ASC' ?>"
                        style="color: #007bff; text-decoration: none;">
                        Location <i class="fas fa-filter" title="Click to sort by Location"></i>
                    </a>
                </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['name']) ?></td>
                    <td><?= htmlspecialchars($event['date']) ?></td>
                    <td><?= htmlspecialchars($event['location']) ?></td>
                    <td>
                        <a href="event_details.php?id=<?= $event['id'] ?>" class="btn btn-info btn-sm"
                            style="background-color: #17a2b8; border: none; padding: 5px 10px; font-size: 14px; border-radius: 4px; color: #fff;">View</a>
                        <?php if (isAdmin() || $event['created_by'] == $_SESSION['user_id']): ?>
                            <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn btn-warning btn-sm"
                                style="background-color: #ffc107; border: none; padding: 5px 10px; font-size: 14px; border-radius: 4px; color: #000;">Edit</a>
                            <a href="delete_event.php?id=<?= $event['id'] ?>" class="btn btn-danger btn-sm"
                                style="background-color: #dc3545; border: none; padding: 5px 10px; font-size: 14px; border-radius: 4px; color: #fff;"
                                onclick="return confirm('Are you sure?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <nav>
            <ul class="pagination" style="justify-content: center; margin-top: 20px;">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                        <a class="page-link"
                            href="?page=<?= $i ?>&sort=<?= $sortField ?>&order=<?= $sortOrder ?><?= $filterField && $filterValue ? '&filter_field=' . $filterField . '&filter_value=' . $filterValue : '' ?><?= $search ? '&search=' . urlencode($search) : '' ?>"
                            style="color: #007bff; text-decoration: none; padding: 5px 10px; border: 1px solid #007bff; border-radius: 4px; margin: 0 2px;"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
<?php include 'templates/footer.php'; ?>