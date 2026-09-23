<?php
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Mechanic') {
    header("Location: login.php");
    exit();
}

$mechanic_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_service'])) {
    $appt_id = $_POST['appointment_id'];
    $findings = $_POST['findings'];
    $repairs = $_POST['repairs'];
    $components = $_POST['components'];
    $cost = $_POST['cost'];

    // Predictive Maintenance Logic Calculation
    $ai_rec = "Standard maintenance complete.";
    if (strpos(strtolower($findings), 'brake') !== false) {
        $ai_rec = "AI Alert: Brake pads critical. Schedule replacement in next 1,000 KM.";
    } elseif (strpos(strtolower($findings), 'oil') !== false) {
        $ai_rec = "AI Alert: Engine oil degradation detected. Recommended replacement interval set.";
    }

    $stmt = $conn->prepare("INSERT INTO service_records (appointment_id, mechanic_id, inspection_findings, repairs_done, replaced_components, estimated_cost, ai_recommendation) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssds", $appt_id, $mechanic_id, $findings, $repairs, $components, $cost, $ai_rec);
    
    if ($stmt->execute()) {
        // Update appointment and vehicle status
        $conn->query("UPDATE appointments SET status='Completed' WHERE id=$appt_id");
        
        // Lower health score if major findings noted
        $conn->query("UPDATE vehicles SET health_score = GREATEST(health_score - 15, 20) WHERE id = (SELECT vehicle_id FROM appointments WHERE id=$appt_id)");

        $message = "Service record saved and AI analysis updated successfully!";
    }
}

$appointments = $conn->query("SELECT a.id, u.full_name as customer, v.plate_number, v.model, a.appointment_date 
                              FROM appointments a 
                              JOIN users u ON a.customer_id = u.id 
                              JOIN vehicles v ON a.vehicle_id = v.id 
                              WHERE a.status = 'Pending'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mechanic Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container">
    <h2 class="mb-4">Mechanic Workstation</h2>
    <?php if ($message): ?><div class="alert alert-success"><?= $message ?></div><?php endif; ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">Assigned Service Tasks</div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr><th>Appt ID</th><th>Customer</th><th>Vehicle</th><th>Date</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php while($row = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['customer']) ?></td>
                        <td><?= htmlspecialchars($row['plate_number']) ?> (<?= htmlspecialchars($row['model']) ?>)</td>
                        <td><?= $row['appointment_date'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal<?= $row['id'] ?>">Inspect & Record</button>
                        </td>
                    </tr>

                    <!-- Modal for Service Record Entry -->
                    <div class="modal fade" id="serviceModal<?= $row['id'] ?>" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <form method="POST">
                              <div class="modal-header">
                                <h5 class="modal-title">Service Task #<?= $row['id'] ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">Inspection Findings</label>
                                    <textarea name="findings" class="form-control" required placeholder="e.g. Brake pads worn out, low oil"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Repairs / Maintenance Details</label>
                                    <textarea name="repairs" class="form-control" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Replaced Components</label>
                                    <input type="text" name="components" class="form-control" placeholder="e.g. Engine Oil, Oil Filter">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Estimated Cost (RM)</label>
                                    <input type="number" step="0.01" name="cost" class="form-control" required>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="submit" name="save_service" class="btn btn-success">Save Service Record</button>
                              </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>