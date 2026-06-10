<!-- Vicki Ferdinand - 2572030 -->
<?php
include_once "koneksi.php";

$msg = "";
$alert_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama'] ?? '');
    $asal = trim($_POST['asal'] ?? '');
    $komentar = trim($_POST['komentar'] ?? '');

    
    if (empty($nama) || empty($asal) || empty($komentar)) {
        $msg = "Semua field (Nama, Asal Kota, dan Komentar) tidak boleh kosong!";
        $alert_type = "alert-danger"; 
    } else {
        try {
            $sql = "INSERT INTO buku_tamu (nama, asal, komentar) VALUES (:nama, :asal, :komentar)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nama' => $nama,
                ':asal' => $asal,
                ':komentar' => $komentar
            ]);
            $msg = "Data berhasil disimpan!";
            $alert_type = "alert-success"; 
        } catch (PDOException $e) {
            $msg = "Error: " . $e->getMessage();
            $alert_type = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - 2572030</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5 mb-5" style="max-width: 800px;">
        <h1 class="mb-4">Buku Tamu</h1>

        <?php if ($msg != ""): ?>
            <div class="alert <?= $alert_type ?>" role="alert">
                <?= htmlspecialchars($msg) ?>
            </div>
        <?php endif; ?>
        
        <fieldset class="border p-4 rounded mb-5 bg-light">
            <legend class="w-auto px-2 fw-bold">Isian Data</legend>
            
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap">
                </div>
                
                <div class="mb-3">
                    <label for="asal" class="form-label">Asal Kota</label>
                    <input type="text" class="form-control" id="asal" name="asal" placeholder="Contoh: Bandung">
                </div>
                
                <div class="mb-3">
                    <label for="komentar" class="form-label">Komentar</label>
                    <textarea class="form-control" id="komentar" name="komentar" rows="4" placeholder="Tulis komentar..."></textarea>
                </div>
                
                <input type="submit" name="btnsubmit" class="btn btn-primary" value="Tulis">
            </form>
        </fieldset>

        <h3 class="mb-3">Komentar Tamu</h3>

        <?php
        try {
        
            $sql = "SELECT * FROM buku_tamu ORDER BY waktu DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            
        
            $jumlah_komentar = $stmt->rowCount();
            echo "<p class='mb-4'>Total Komentar: <strong>" . $jumlah_komentar . "</strong></p>";

            
            if ($jumlah_komentar > 0) {
           
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    
                    echo "<div class='list-group-item list-group-item-action flex-column align-items-start border rounded p-3 mb-3'>";
                    echo "<div class='d-flex w-100 justify-content-between'>";
                    echo "<h5 class='mb-1 text-primary'>" . htmlspecialchars($row['nama']) . " <span class='text-muted fs-6'>dari " . htmlspecialchars($row['asal']) . "</span></h5>";
                    echo "<small class='text-muted'>" . htmlspecialchars($row['waktu']) . "</small>";
                    echo "</div>";
                    echo "<p class='mb-1 mt-2'>\"" . nl2br(htmlspecialchars($row['komentar'])) . "\"</p>";
                    echo "</div>";
                }
            } else {
             
                echo "<div class='alert alert-secondary' role='alert'>Belum ada komentar</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Error memuat komentar: " . $e->getMessage() . "</div>";
        }

    
        $conn = null;
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>