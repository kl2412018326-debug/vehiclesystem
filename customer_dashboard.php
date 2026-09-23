<?php
require_once 'db_config.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Customer') {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['user_id'];
$message = '';

// Register Vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_vehicle'])) {
    $plate = trim($_POST['plate_number']);
    $model = trim($_POST['model']);
    $mileage = (int)$_POST['current_mileage'];

    $stmt = $conn->prepare("INSERT INTO vehicles (customer_id, plate_number, model, current_mileage, last_service_mileage) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $customer_id, $plate, $model, $mileage, $mileage);
    $stmt->execute();
    $message = "Vehicle added successfully!";
}

// Book Appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $v_id = $_POST['vehicle_id'];
    $date = $_POST['appointment_date'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO appointments (customer_id, vehicle_id, appointment_date, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $customer_id, $v_id, $date, $notes);
    $stmt->execute();
    $message = "Service appointment booked!";
}

$vehicles = $conn->query("SELECT * FROM vehicles WHERE customer_id = $customer_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container">
    <h2 class="mb-4">My Dashboard</h2>
    <?php if ($message): ?><div class="alert alert-success"><?= $message ?></div><?php endif; ?>

    <div class="row">
        <!-- Vehicle List & Health Score -->
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>My Vehicles</span>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addVehicleModal">+ Add Vehicle</button>
                </div>
                <div class="card-body">
                    <?php if ($vehicles->num_rows > 0): ?>
                        <?php while($v = $vehicles->fetch_assoc()): ?>
                            <div class="border rounded p-3 mb-3 bg-white">
                                <div class="d-flex justify-content-between">
                                    <h5><?= htmlspecialchars($v['plate_number']) ?> - <?= htmlspecialchars($v['model']) ?></h5>
                                    <span class="badge bg-<?= $v['health_score'] > 75 ? 'success' : ($v['health_score'] > 40 ? 'warning' : 'danger') ?>">
                                        Health Score: <?= $v['health_score'] ?>%
                                    </span>
                                </div>
                                <p class="mb-1">Current Mileage: <strong><?= number_format($v['current_mileage']) ?> km</strong></p>
                                
                                <!-- AI Recommendation Engine Logic -->
                                <div class="alert alert-info py-2 mt-2">
                                    <strong>AI Predictive Recommendation:</strong><br>
                                    <?php 
                                        $diff = $v['current_mileage'] - $v['last_service_mileage'];
                                        if ($diff > 5000) {
                                            echo "Service Required immediately! Oil life exceeded recommended limit (Interval: 5,000km). Estimated Cost: RM 150 - RM 250.";
                                        } else {
                                            echo "Vehicle operational status normal. Next scheduled service in " . (5000 - $diff) . " km.";
                                        }
                                    ?>
                                </div>
                                <a href="qr_service.php?vehicle_id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-dark" target="_blank">Access Digital QR Record</a>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No registered vehicles found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Book Appointment Form -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Book Maintenance Service</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Select Vehicle</label>
                            <select name="vehicle_id" class="form-select" required>
                                <?php 
                                $vehicles->data_seek(0);
                                while($v = $vehicles->fetch_assoc()): 
                                ?>
                                    <option value="<?= $v['id'] ?>"><?= $v['plate_number'] ?> (<?= $v['model'] ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preferred Date</label>
                            <input type="date" name="appointment_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Notes / Issues</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Describe issues..."></textarea>
                        </div>
                        <button type="submit" name="book_appointment" class="btn btn-primary w-100">Submit Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div class="modal fade" id="addVehicleModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
          <div class="modal-header">
            <h5 class="modal-title">Register New Vehicle</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Plate Number</label>
                <input type="text" name="plate_number" class="form-control" required placeholder="e.g. WX1234Y">
            </div>
            <div class="mb-3">
                <label class="form-label">Vehicle Model</label>
                <input type="text" name="model" class="form-control" required placeholder="e.g. Perodua Myvi">
            </div>
            <div class="mb-3">
                <label class="form-label">Current Mileage (km)</label>
                <input type="number" name="current_mileage" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add_vehicle" class="btn btn-success">Save Vehicle</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>