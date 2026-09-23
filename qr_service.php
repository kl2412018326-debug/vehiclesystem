<?php
require_once 'db_config.php';

$v_id = isset($_GET['vehicle_id']) ? (int)$_GET['vehicle_id'] : 0;

$stmt = $conn->prepare("SELECT v.*, u.full_name FROM vehicles v JOIN users u ON v.customer_id = u.id WHERE v.id = ?");
$stmt->bind_param("i", $v_id);
$stmt->execute();
$vehicle = $stmt->get_result()->fetch_assoc();

if (!$vehicle) {
    die("Vehicle Record Not Found!");
}

$history = $conn->query("SELECT sr.*, a.appointment_date, u.full_name as mechanic_name 
                         FROM service_records sr 
                         JOIN appointments a ON sr.appointment_id = a.id 
                         JOIN users u ON sr.mechanic_id = u.id 
                         WHERE a.vehicle_id = $v_id 
                         ORDER BY sr.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Digital Service Record - <?= htmlspecialchars($vehicle['plate_number']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="bg-light p-4">
<div class="container bg-white p-4 shadow rounded" style="max-width: 800px;">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2>Digital Vehicle Health Record</h2>
            <p class="text-muted mb-0">Owner: <?= htmlspecialchars($vehicle['full_name']) ?></p>
            <p class="text-muted">Vehicle Plate: <strong><?= htmlspecialchars($vehicle['plate_number']) ?></strong> | Model: <?= htmlspecialchars($vehicle['model']) ?></p>
        </div>
        <div id="qrcode"></div>
    </div>

    <h4>Maintenance History</h4>
    <?php if ($history->num_rows > 0): ?>
        <?php while($sr = $history->fetch_assoc()): ?>
            <div class="card mb-3 border-secondary">
                <div class="card-header d-flex justify-content-between">
                    <span>Date: <strong><?= $sr['appointment_date'] ?></strong></span>
                    <span>Mechanic: <?= htmlspecialchars($sr['mechanic_name']) ?></span>
                </div>
                <div class="card-body">
                    <p><strong>Findings:</strong> <?= htmlspecialchars($sr['inspection_findings']) ?></p>
                    <p><strong>Repairs Done:</strong> <?= htmlspecialchars($sr['repairs_done']) ?></p>
                    <p><strong>Replaced Parts:</strong> <?= htmlspecialchars($sr['replaced_components']) ?></p>
                    <p><strong>Cost:</strong> RM <?= number_format($sr['estimated_cost'], 2) ?></p>
                    <div class="alert alert-light border mb-0">
                        <strong>AI Recommendation Log:</strong> <?= htmlspecialchars($sr['ai_recommendation']) ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-muted">No completed service records logged yet.</p>
    <?php endif; ?>
</div>

<script>
    new QRCode(document.getElementById("qrcode"), {
        text: window.location.href,
        width: 100,
        height: 100
    });
</script>
</body>
</html>