<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard - Smart Auto Care</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Smart Auto Care</a>
    <div class="ms-auto text-light">
        Selamat Datang, <span id="userName" class="fw-bold">Pelanggan</span>
        <button onclick="logout()" class="btn btn-outline-light btn-sm ms-2">Log Keluar</button>
    </div>
  </div>
</nav>

<div class="container">
    <h2 class="mb-4">Papan Pemuka Pelanggan</h2>

    <div class="row">
    
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Kenderaan Saya</span>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addVehicleModal">+ Tambah Kenderaan</button>
                </div>
                <div class="card-body" id="vehicleList">
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Tempah Servis Penyelenggaraan</div>
                <div class="card-body">
                    <form id="bookingForm">
                        <div class="mb-3">
                            <label class="form-label">Pilih Kenderaan</label>
                            <select id="bookingVehicle" class="form-select" required>
                                <option value="Myvi - WX1234Y">Perodua Myvi (WX1234Y)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tarikh Pilihan</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Masalah</label>
                            <textarea class="form-control" rows="3" placeholder="Nyatakan masalah..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Hantar Tempahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addVehicleModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addVehicleForm">
          <div class="modal-header">
            <h5 class="modal-title">Daftar Kenderaan Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Nombor Plat</label>
                <input type="text" id="plateNum" class="form-control" required placeholder="e.g. VAB1234">
            </div>
            <div class="mb-3">
                <label class="form-label">Model Kenderaan</label>
                <input type="text" id="modelNum" class="form-control" required placeholder="e.g. Honda City">
            </div>
            <div class="mb-3">
                <label class="form-label">Distance / Mileage Semasa (km)</label>
                <input type="number" id="mileageNum" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Simpan Kenderaan</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const user = JSON.parse(localStorage.getItem('currentUser')) || { name: 'Pelanggan' };
document.getElementById('userName').innerText = user.name;

let vehicles = JSON.parse(localStorage.getItem('userVehicles')) || [
    { plate: 'WX1234Y', model: 'Perodua Myvi', mileage: 65000, lastService: 58000, health: 65 }
];

function renderVehicles() {
    const list = document.getElementById('vehicleList');
    list.innerHTML = '';
    vehicles.forEach(v => {
        const diff = v.mileage - v.lastService;
        let aiMsg = diff > 5000 
            ? "Peringatan AI: Servis diperlukan segera! Had jarak minyak enjin telah melebihi 5,000km."
            : `Status kenderaan dalam keadaan baik. Servis seterusnya dalam ${5000 - diff} km.`;

        list.innerHTML += `
            <div class="border rounded p-3 mb-3 bg-white">
                <div class="d-flex justify-content-between">
                    <h5>${v.plate} - ${v.model}</h5>
                    <span class="badge bg-${v.health > 75 ? 'success' : 'warning'}">Health Score: ${v.health}%</span>
                </div>
                <p class="mb-1">Mileage Semasa: <strong>${v.mileage.toLocaleString()} km</strong></p>
                <div class="alert alert-info py-2 mt-2">
                    <strong>Syor Ramalan AI:</strong><br>${aiMsg}
                </div>
            </div>
        `;
    });
}

document.getElementById('addVehicleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const plate = document.getElementById('plateNum').value;
    const model = document.getElementById('modelNum').value;
    const mileage = parseInt(document.getElementById('mileageNum').value);

    vehicles.push({ plate, model, mileage, lastService: mileage, health: 100 });
    localStorage.setItem('userVehicles', JSON.stringify(vehicles));
    renderVehicles();
    
    bootstrap.Modal.getInstance(document.getElementById('addVehicleModal')).hide();
});

function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = 'index.html';
}

renderVehicles();
</script>
</body>
</html>
