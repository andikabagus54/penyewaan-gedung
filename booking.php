<?php
require_once 'config/database.php';
if(!isLoggedIn()) redirect('login.php');

$gedung_id = (int)$_GET['id'];
$gedung = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM gedung WHERE id=$gedung_id"));
if(!$gedung) redirect('index.php');

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mulai = $_POST['tanggal_mulai'];
    $selesai = $_POST['tanggal_selesai'];
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);
    
    $start = new DateTime($mulai);
    $end = new DateTime($selesai);
    $hari = $start->diff($end)->days + 1;
    $total = $hari * $gedung['harga_per_hari'];
    $kode = generateKodeBooking();
    
    $query = "INSERT INTO pemesanan (kode_booking, user_id, gedung_id, tanggal_mulai, tanggal_selesai, total_hari, total_harga, catatan, status) 
              VALUES ('$kode', {$_SESSION['user_id']}, $gedung_id, '$mulai', '$selesai', $hari, $total, '$catatan', 'menunggu_pembayaran')";
    
    if(mysqli_query($conn, $query)) {
        $id = mysqli_insert_id($conn);
        echo "<script>alert('✅ Booking berhasil! Kode: $kode'); window.location.href='payment.php?id=$id';</script>";
    } else {
        $error = "❌ Gagal booking: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Gedung</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header"><div class="navbar"><div class="logo"><h1><a href="index.php">🌿 MWCNU</a></h1></div>
    <div class="nav-links"><a href="index.php">🏠 Beranda</a><a href="dashboard.php">📊 Dashboard</a><a href="logout.php">🚪 Logout</a></div></div></div>
    <div class="container">
        <div style="max-width:500px; margin:30px auto">
            <div class="card">
                <h2>📅 Booking Gedung</h2>
                <div style="background:#e8f5e9; padding:15px; border-radius:10px; margin-bottom:20px">
                    <h3><?php echo $gedung['nama_gedung']; ?></h3>
                    <p>💰 <?php echo rupiah($gedung['harga_per_hari']); ?>/hari</p>
                    <p>👥 Kapasitas: <?php echo number_format($gedung['kapasitas']); ?> orang</p>
                </div>
                <?php if($error): ?><div class="alert alert-error"><?php echo $error; ?></div><?php endif; ?>
                <form method="POST">
                    <div class="form-group"><label>📅 Tanggal Mulai</label><input type="date" name="tanggal_mulai" min="<?php echo date('Y-m-d'); ?>" required></div>
                    <div class="form-group"><label>📅 Tanggal Selesai</label><input type="date" name="tanggal_selesai" min="<?php echo date('Y-m-d'); ?>" required></div>
                    <div class="form-group"><label>📝 Catatan</label><textarea name="catatan" rows="3" placeholder="Tambahkan catatan"></textarea></div>
                    <button type="submit" class="btn btn-primary" style="width:100%">✅ Booking Sekarang</button>
                    <a href="index.php" class="btn btn-danger" style="width:100%; text-align:center; margin-top:10px; display:block">❌ Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>