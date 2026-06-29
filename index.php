<?php
require_once 'config/database.php';
$query = "SELECT * FROM gedung WHERE status = 'tersedia' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>MWCNU Sewa Gedung - Penyewaan Gedung MWCNU</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <div class="navbar">
            <div class="logo">
                <h1><a href="index.php"><i class="fas fa-building"></i> MWCNU Sewa Gedung</a></h1>
                <p><i class="fas fa-mosque"></i> Nahdlatul Ulama | Penyewaan Gedung Terpercaya</p>
            </div>
            <div class="nav-links">
                <a href="index.php" class="active"><i class="fas fa-home"></i> Beranda</a>
                <?php if(isLoggedIn()): ?>
                    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                <?php else: ?>
                    <a href="login.php"><i class="fas fa-key"></i> Login</a>
                    <a href="register.php"><i class="fas fa-user-plus"></i> Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="hero">
            <h1><i class="fas fa-calendar-check"></i> Selamat Datang di MWCNU Sewa Gedung</h1>
            <p>Sewa gedung untuk berbagai acara Anda dengan mudah, cepat, dan aman</p>
            <div class="acara-list">
                <span><i class="fas fa-glass-cheers"></i> Pernikahan</span>
                <span><i class="fas fa-chalkboard-user"></i> Seminar</span>
                <span><i class="fas fa-users"></i> Rapat</span>
                <span><i class="fas fa-pray"></i> Pengajian</span>
                <span><i class="fas fa-handshake"></i> Halal Bihalal</span>
            </div>
        </div>

        <div class="card">
            <h2><i class="fas fa-building"></i> Daftar Gedung Tersedia</h2>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="gedung-grid">
                    <?php while($gedung = mysqli_fetch_assoc($result)): ?>
                        <div class="gedung-card">
                            <div class="gedung-info">
                                <div class="gedung-icon">
                                    <i class="fas fa-landmark"></i>
                                </div>
                                <span class="gedung-badge"><i class="fas fa-check-circle"></i> Tersedia</span>
                                <h3><?php echo htmlspecialchars($gedung['nama_gedung']); ?></h3>
                                <div class="info-item">
                                    <span><i class="fas fa-users"></i> Kapasitas:</span>
                                    <span><?php echo number_format($gedung['kapasitas']); ?> orang</span>
                                </div>
                                <div class="info-item">
                                    <span><i class="fas fa-tags"></i> Harga:</span>
                                    <span><?php echo rupiah($gedung['harga_per_hari']); ?> / hari</span>
                                </div>
                                <div class="fasilitas">
                                    <i class="fas fa-clipboard-list"></i> <strong>Fasilitas:</strong><br>
                                    <?php echo htmlspecialchars($gedung['fasilitas']); ?>
                                </div>
                                <div class="price">
                                    <?php echo rupiah($gedung['harga_per_hari']); ?> <small>/ hari</small>
                                </div>
                                <?php if(isLoggedIn()): ?>
                                    <a href="booking.php?id=<?php echo $gedung['id']; ?>" class="btn btn-primary btn-block">
                                        <i class="fas fa-calendar-alt"></i> Sewa Sekarang
                                    </a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-primary btn-block">
                                        <i class="fas fa-key"></i> Login untuk Sewa
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info" style="text-align: center;">
                    <i class="fas fa-info-circle"></i> Tidak ada gedung tersedia saat ini.
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="footer">
        <p><i class="fas fa-copyright"></i> 2026 MWCNU Sewa Gedung - Nahdlatul Ulama | All Rights Reserved</p>
        <p><i class="fas fa-envelope"></i> info@mwcnu.com | <i class="fas fa-phone"></i> (021) 1234567</p>
        <p><i class="fas fa-heart" style="color: #FFC107;"></i> Melayani dengan hati, mengutamakan kepuasan Anda</p>
    </div>
</body>
</html>