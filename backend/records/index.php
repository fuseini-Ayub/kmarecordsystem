<?php
include_once './assets/inc/functions.php';
check_login(2);

include_once '../../assets/inc/config.php';

// Fetch statistics from the database
$queries = [
    "total_incoming_files" => "SELECT COUNT(*) AS total FROM incoming_files",
    "total_outgoing_files" => "SELECT COUNT(*) AS total FROM outgoing_files",
    "incoming_this_month" => "SELECT COUNT(*) AS total FROM incoming_files WHERE MONTH(date_received) = MONTH(CURRENT_DATE()) AND YEAR(date_received) = YEAR(CURRENT_DATE())",
    "outgoing_this_month" => "SELECT COUNT(*) AS total FROM outgoing_files WHERE MONTH(date_dispatched) = MONTH(CURRENT_DATE()) AND YEAR(date_dispatched) = YEAR(CURRENT_DATE())",
    "incoming_this_week" => "SELECT COUNT(*) AS total FROM incoming_files WHERE YEARWEEK(date_received, 1) = YEARWEEK(CURDATE(), 1)",
    "outgoing_this_week" => "SELECT COUNT(*) AS total FROM outgoing_files WHERE YEARWEEK(date_dispatched, 1) = YEARWEEK(CURDATE(), 1)",
    "incoming_today" => "SELECT COUNT(*) AS total FROM incoming_files WHERE DATE(date_received) = CURDATE()",
    "outgoing_today" => "SELECT COUNT(*) AS total FROM outgoing_files WHERE DATE(date_dispatched) = CURDATE()",
    "recent_incoming_files" => "SELECT * FROM incoming_files ORDER BY date_received DESC LIMIT 5",
    "recent_outgoing_files" => "SELECT * FROM outgoing_files ORDER BY date_dispatched DESC LIMIT 5"
];

$stats = [];
$results = [];
foreach ($queries as $key => $query) {
    $result = $mysqli->query($query);
    if (strpos($key, 'recent_') === 0) {
        $results[$key] = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $stats[$key] = $result->fetch_assoc()['total'];
    }
}

// Map for URLs corresponding to each key
$links = [
    "total_incoming_files" => "incoming.php",
    "total_outgoing_files" => "outgoing.php",
    "recent_incoming_files" => "incoming.php",
    "recent_outgoing_files" => "outgoing.php"
];
?>

<?php include './assets/inc/navbar.php'; ?>
<?php include './assets/inc/sidebar.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4 page-title">Dashboard Overview</h2>

        <!-- Overall Statistics Section -->
        <div class="stats-container">
            <div class="row g-4">
                <?php
                $stat_cards = [
                    ['title' => 'Total Incoming Files', 'value' => $stats['total_incoming_files'], 'icon' => 'fas fa-file-import', 'color' => 'text-primary'],
                    ['title' => 'Total Outgoing Files', 'value' => $stats['total_outgoing_files'], 'icon' => 'fas fa-file-export', 'color' => 'text-success'],
                    ['title' => 'This Month (Incoming)', 'value' => $stats['incoming_this_month'], 'icon' => 'fas fa-calendar-alt', 'color' => 'text-info'],
                    ['title' => 'This Month (Outgoing)', 'value' => $stats['outgoing_this_month'], 'icon' => 'fas fa-calendar-check', 'color' => 'text-warning'],
                    ['title' => 'This Week (Incoming)', 'value' => $stats['incoming_this_week'], 'icon' => 'fas fa-calendar-week', 'color' => 'text-danger'],
                    ['title' => 'This Week (Outgoing)', 'value' => $stats['outgoing_this_week'], 'icon' => 'fas fa-calendar-week', 'color' => 'text-secondary'],
                    ['title' => 'Today (Incoming)', 'value' => $stats['incoming_today'], 'icon' => 'fas fa-calendar-day', 'color' => 'text-dark'],
                    ['title' => 'Today (Outgoing)', 'value' => $stats['outgoing_today'], 'icon' => 'fas fa-calendar-day', 'color' => 'text-muted'],
                ];

                foreach ($stat_cards as $card): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="dashboard-card">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="card-title m-0"><?php echo htmlspecialchars($card['title']); ?></h6>
                                <i class="<?php echo $card['icon'] . ' ' . $card['color']; ?> fa-2x"></i>
                            </div>
                            <p class="card-text m-0"><?php echo htmlspecialchars($card['value']); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Detailed Overview of Recent Files -->
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Recent Incoming Files</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Serial Number</th>
                                        <th>Date Received</th>
                                        <th>Subject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results['recent_incoming_files'] as $file): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($file['serial_number']); ?></td>
                                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($file['date_received']))); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($file['subject']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="<?php echo $links['recent_incoming_files']; ?>" class="btn btn-primary mt-3">View All
                            Incoming Files</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="dashboard-card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Recent Outgoing Files</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Serial Number</th>
                                        <th>Date Dispatched</th>
                                        <th>Subject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results['recent_outgoing_files'] as $file): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($file['serial_number']); ?></td>
                                        <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($file['date_dispatched']))); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($file['subject']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="<?php echo $links['recent_outgoing_files']; ?>" class="btn btn-primary mt-3">View All
                            Outgoing Files</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include './assets/inc/footer.php'; ?>
