<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Auto Care - Log Masuk & Daftar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.html">Smart Auto Care</a>
  </div>
</nav>

<div class="container" style="max-width: 450px;">
    <div class="card shadow" id="loginCard">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Log Masuk Pengguna</h3>
            <div id="alertBox"></div>
            <form id="loginForm">
                <div class="mb-3">
                    <label class="form-label">E-mel</label>
                    <input type="email" id="loginEmail" class="form-control" required placeholder="user@example.com">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kata Laluan</label>
                    <input type="password" id="loginPass" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Log Masuk</button>
            </form>
            <div class="text-center mt-3">
                <a href="#" onclick="toggleAuth('register')">Belum ada akaun? Daftar di sini</a>
            </div>
        </div>
    </div>

    <div class="card shadow d-none" id="registerCard">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Daftar Akaun Baru</h3>
            <form id="registerForm">
                <div class="mb-3">
                    <label class="form-label">Nama Penuh</label>
                    <input type="text" id="regName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mel</label>
                    <input type="email" id="regEmail" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kata Laluan</label>
                    <input type="password" id="regPass" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Peranan (Role)</label>
                    <select id="regRole" class="form-select">
                        <option value="Customer">Pelanggan (Customer)</option>
                        <option value="Mechanic">Mekanik (Mechanic)</option>
                        <option value="Admin">Pentadbir (Admin)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success w-100">Daftar Akaun</button>
            </form>
            <div class="text-center mt-3">
                <a href="#" onclick="toggleAuth('login')">Sudah ada akaun? Log Masuk</a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleAuth(type) {
    if (type === 'register') {
        document.getElementById('loginCard').classList.add('d-none');
        document.getElementById('registerCard').classList.remove('d-none');
    } else {
        document.getElementById('registerCard').classList.add('d-none');
        document.getElementById('loginCard').classList.remove('d-none');
    }
}

document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('regName').value;
    const email = document.getElementById('regEmail').value;
    const role = document.getElementById('regRole').value;

    const user = { name, email, role };
    localStorage.setItem('currentUser', JSON.stringify(user));

    alert('Pendaftaran Berjaya!');
    redirectUser(role);
});

document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value;

    let role = 'Customer';
    if (email.includes('admin')) role = 'Admin';
    if (email.includes('mechanic')) role = 'Mechanic';

    const user = { name: email.split('@')[0], email, role };
    localStorage.setItem('currentUser', JSON.stringify(user));

    redirectUser(role);
});

function redirectUser(role) {
    if (role === 'Admin') window.location.href = 'admin_dashboard.html';
    else if (role === 'Mechanic') window.location.href = 'mechanic_dashboard.html';
    else window.location.href = 'customer_dashboard.html';
}
</script>
</body>
</html>
