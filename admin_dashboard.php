<?php
require_once 'db_config.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Function to safely execute queries and catch database errors
function get_query_data($conn, $sql) {
    $result = $conn->query($sql);
    if ($result === false) {
        die("Database Query Failed: " . $conn->error . "<br>Query: " . $sql);
    }
    return $result;
}

// Fetch stats safely
$res_cust = get_query_data($conn, "SELECT COUNT(*) as count FROM users WHERE role='Customer'");
$customers_cnt = $res_cust->fetch_assoc()['count'];

$res_veh = get_query_data($conn, "SELECT COUNT(*) as count FROM vehicles");
$vehicles_cnt = $res_veh->fetch_assoc()['count'];

$res_appt = get_query_data($conn, "SELECT COUNT(*) as count FROM appointments WHERE status='Pending'");
$appts_cnt = $res_appt->fetch_assoc()['count'];

// Fetch parts
$parts = get_query_data($conn, "SELECT * FROM spare_parts");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container">
    <h2 class="mb-4">Administrator Dashboard</h2>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white p-3">
                <h5>Total Customers</h5>
                <h3><?= $customers_cnt ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white p-3">
                <h5>Registered Vehicles</h5>
                <h3><?= $vehicles_cnt ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark p-3">
                <h5>Pending Appointments</h5>
                <h3><?= $appts_cnt ?></h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow-sm">
                <h5>Workshop AI Predictive Maintenance Analytics</h5>
                <canvas id="healthChart"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow-sm">
                <h5>Spare Parts Inventory</h5>
                <table class="table table-sm">
                    <thead>
                        <tr><th>Part Name</th><th>Stock</th><th>Unit Price (RM)</th></tr>
                    </thead>
                    <tbody>
                        <?php while($p = $parts->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['part_name']) ?></td>
                            <td><?= $p['stock_quantity'] ?></td>
                            <td><?= number_format($p['unit_price'], 2) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('healthChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Good Health (>80%)', 'Moderate (50-80%)', 'Critical Health (<50%)'],
        datasets: [{
            label: 'Vehicle Health Distribution',
            data: [12, 5, 2],
            backgroundColor: ['#198754', '#ffc107', '#dc3545']
        }]
    }
});
</script>
</body>
</html>