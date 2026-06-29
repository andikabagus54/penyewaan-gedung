<?php
require_once 'config/database.php';
if(!isLoggedIn()) redirect('login.php');

$id = (int)$_GET['id'];
$query = "SELECT p.*, g.nama_gedung FROM pemesanan p JOIN gedung g ON p.gedung_id=g.id WHERE p.id=$id AND p.user_id={$_SESSION['user_id']}";
$booking = mysqli_fetch_assoc(mysqli_query($conn, $query));
if(!$booking) redirect('dashboard.php');

$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['bukti'])) {
    $dir = "uploads/";
    if(!file_exists($dir)) mkdir($dir, 0777, true);
    $nama_file = time() . '_' . basename($_FILES['bukti']['name']);
    if(move_uploaded_file($_FILES['bukti']['tmp_name'], $dir . $nama_file)) {
        mysqli_query($conn, "UPDATE pemesanan SET bukti_pembayaran='$nama_file', status='pending' WHERE id=$id");
        $message = "✅ Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.";
    } else {
        $message = "❌ Gagal upload file.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Pembayaran - MWCNU</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <div class="navbar">
            <div class="logo"><h1><a href="index.php"><i class="fas fa-building"></i> MWCNU</a></h1></div>
            <div class="nav-links">
                <a href="index.php"><i class="fas fa-home"></i> Beranda</a>
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div style="max-width: 550px; margin: 40px auto;">
            <div class="card">
                <h2><i class="fas fa-money-bill-wave"></i> Upload Bukti Pembayaran</h2>
                <hr style="margin: 15px 0;">
                
                <div style="background: #e8f5e9; padding: 20px; border-radius: 15px; margin-bottom: 25px;">
                    <p><strong><i class="fas fa-barcode"></i> Kode Booking:</strong> <?php echo $booking['kode_booking']; ?></p>
                    <p><strong><i class="fas fa-building"></i> Gedung:</strong> <?php echo $booking['nama_gedung']; ?></p>
                    <p><strong><i class="fas fa-calendar"></i> Tanggal Sewa:</strong> <?php echo formatTanggal($booking['tanggal_mulai']); ?> - <?php echo formatTanggal($booking['tanggal_selesai']); ?></p>
                    <p><strong><i class="fas fa-money-bill"></i> Total Pembayaran:</strong> <?php echo rupiah($booking['total_harga']); ?></p>
                </div>
                
                <div style="background: #fff3cd; padding: 18px; border-radius: 15px; margin-bottom: 25px; border-left: 5px solid #FFC107;">
                    <strong><i class="fas fa-university"></i> Nomor Rekening Pembayaran:</strong><br><br>
                    <i class="fas fa-building"></i> Bank BNI: <strong>1234567890</strong> a.n MWCNU<br>
                    <i class="fas fa-building"></i> Bank Mandiri: <strong>0987654321</strong> a.n MWCNU<br>
                    <small style="color: #856404;"><i class="fas fa-info-circle"></i> Transfer sesuai total pembayaran dan upload bukti</small>
                </div>
                
                <?php if($message): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
                    <script>setTimeout(()=>{location.href='dashboard.php'},2000)</script>
                <?php endif; ?>
                
                <?php if(!$booking['bukti_pembayaran']): ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label><i class="fas fa-file-image"></i> Upload Bukti Transfer</label>
                        <input type="file" name="bukti" accept="image/*,.pdf" required>
                        <small><i class="fas fa-info-circle"></i> Format: JPG, PNG, PDF (Max 2MB)</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-upload"></i> Upload Bukti
                    </button>
                </form>
                <?php else: ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Bukti pembayaran sudah diupload. Menunggu konfirmasi admin.
                </div>
                <a href="dashboard.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p><i class="fas fa-copyright"></i> 2026 MWCNU Sewa Gedung | All Rights Reserved</p>
    </div>
</body>
</html>