
<?php
session_start();
include_once "koneksi.php";

$pesan_error = "";
$pesan_sukses = "";

if (isset($_GET['aksi']) && $_GET['aksi'] == 'logout') {
    session_destroy();
    header("Location: Tugas2_2572030.php");
    exit;
}

if (isset($_POST['tombol_register'])) {
    $input_username = $_POST['username'];
    $input_email = $_POST['email'];
    $input_password = $_POST['password'];

    $sql_cek = "SELECT * FROM users WHERE email = :email OR username = :username";
    $stmt_cek = $conn->prepare($sql_cek);
    $stmt_cek->execute([':email' => $input_email, ':username' => $input_username]);

    if ($stmt_cek->rowCount() > 0) {
        $pesan_error = "Email sudah terdaftar.";
    } else {
        $password_terenkripsi = password_hash($input_password, PASSWORD_DEFAULT);
        
        $sql_simpan = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt_simpan = $conn->prepare($sql_simpan);
        $stmt_simpan->execute([
            ':username' => $input_username,
            ':email' => $input_email,
            ':password' => $password_terenkripsi
        ]);

        $pesan_sukses = "Data sudah disimpan! Silakan Login.";
    }
}

// LOGIKA LOGIN
if (isset($_POST['tombol_login'])) {
    $input_kredensial = $_POST['kredensial']; 
    $input_password = $_POST['password'];

    $sql_cari = "SELECT * FROM users WHERE email = :kredensial OR username = :kredensial";
    $stmt_cari = $conn->prepare($sql_cari);
    $stmt_cari->execute([':kredensial' => $input_kredensial]);
    
    $data_pengguna = $stmt_cari->fetch(PDO::FETCH_ASSOC);

    if ($data_pengguna && password_verify($input_password, $data_pengguna['password'])) {
        
        $_SESSION['sesi_username'] = $data_pengguna['username'];
        header("Location: Tugas2_2572030.php");
        exit;
    } else {
        $pesan_error = "Password salah!";
    }
}

$halaman_aktif = isset($_GET['halaman']) ? $_GET['halaman'] : 'login';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register - 2572030</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body { background-color: #f8f9fa; }
        .kotak-tengah { max-width: 400px; margin: 50px auto; }
        .kotak-dashboard { max-width: 600px; margin: 50px auto; }
    </style>
</head>
<body>

    <?php if (isset($_SESSION['sesi_username'])): ?>
        <div class="container kotak-dashboard">
            <div class="alert alert-success d-flex justify-content-between align-items-center" role="alert">
                <h4 class="mb-0">Selamat datang, <strong><?= htmlspecialchars($_SESSION['sesi_username']) ?></strong></h4>
            </div>
            <a href="Tugas2_2572030.php?aksi=logout" class="btn btn-danger">Logout</a>
        </div>

    <?php elseif ($halaman_aktif == 'register'): ?>
        <div class="container kotak-tengah">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Register</h3>
                    
                    <?php if ($pesan_error != ""): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($pesan_error) ?></div>
                    <?php endif; ?>
                    
                    <form action="Tugas2_2572030.php?halaman=register" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="tombol_register" class="btn btn-primary w-100 mb-3">Register</button>
                    </form>
                    <div class="text-center">
                        Sudah punya akun? <a href="Tugas2_2572030.php?halaman=login">Login</a>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="container kotak-tengah">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4">Login</h3>
                    
                    <?php if ($pesan_error != ""): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($pesan_error) ?></div>
                    <?php endif; ?>
                    
                    <?php if ($pesan_sukses != ""): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($pesan_sukses) ?></div>
                    <?php endif; ?>

                    <form action="Tugas2_2572030.php?halaman=login" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email / Username</label>
                            <input type="text" name="kredensial" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="tombol_login" class="btn btn-success w-100 mb-3">Login</button>
                    </form>
                    <div class="text-center">
                        Belum punya akun? <a href="Tugas2_2572030.php?halaman=register">Register</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>