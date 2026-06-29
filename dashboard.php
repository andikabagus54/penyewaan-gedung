<?php
require_once 'config/database.php';
if(!isLoggedIn()) redirect('login.php');
if(isAdmin()) redirect('admin/index.php');

$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT p.*, g.nama_gedung FROM pemesanan p JOIN gedung g ON p.gedung_id=g.id WHERE p.user_id=$user_id ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Dashboard - MWCNU Sewa Gedung</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Tambahan style untuk dashboard */
        .welcome-card {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: white;
            padding: 35px;
            border-radius: 20px;
            margin-bottom: 30px;
        }
        .welcome-card h2 {
            color: white;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .welcome-card p {
            font-size: 16px;
            opacity: 0.9;
        }
        .stat-card-user {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            border-bottom: 3px solid #2E7D32;
        }
        .stat-card-user h4 {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .stat-card-user .number {
            font-size: 32px;
            font-weight: bold;
            color: #2E7D32;
        }
        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }
        table th {
            background: #2E7D32;
            color: white;
            padding: 15px;
            font-weight: 600;
            font-size: 14px;
        }
        table td {
            padding: 14px 15px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        table tr:hover {
            background: #f1f8e9;
        }
        .empty-state {
            text-align: center;
            padding: 60px;
            background: #f9f9f9;
            border-radius: 16px;
        }
        .empty-state i {
            font-size: 64px;
            color: #ccc;
            margin-bottom: 20px;
        }
        .empty-state p {
            font-size: 16px;
            color: #888;
        }
        .btn-sewa {
            background: #2E7D32;
            color: white;
            padding: 12px 28px;
            border-radius: 35px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            margin-top: 15px;
        }
        .btn-sewa:hover {
            background: #1B5E20;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="navbar">
            <div class="logo">
                <h1><a href="index.php"><i class="fas fa-building"></i> MWCNU Sewa Gedung</a></h1>
                <p><i class="fas fa-mosque"></i> Nahdlatul Ulama | Penyewaan Gedung Terpercaya</p>
            </div>
            <div class="nav-links">
                <a href="index.php"><i class="fas fa-home"></i> Beranda</a>
                <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="history.php"><i class="fas fa-history"></i> Riwayat</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h2><i class="fas fa-user-check"></i> Selamat Datang, <?php echo $_SESSION['nama_lengkap']; ?>!</h2>
            <p><i class="fas fa-info-circle"></i> Ini adalah dashboard penyewaan gedung MWCNU Anda. Kelola pemesanan Anda di sini.</p>
        </div>

        <!-- Statistik Ringkas -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="stat-card-user">
                <h4><i class="fas fa-shopping-cart"></i> Total Pesanan</h4>
                <div class="number"><?php echo mysqli_num_rows($result); ?></div>
            </div>
            <div class="stat-card-user">
                <h4><i class="fas fa-clock"></i> Menunggu Pembayaran</h4>
                <div class="number">
                    <?php 
                    $pending_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM pemesanan WHERE user_id=$user_id AND status='menunggu_pembayaran'");
                    $pending = mysqli_fetch_assoc($pending_query);
                    echo $pending['total'];
                    ?>
                </div>
            </div>
            <div class="stat-card-user">
                <h4><i class="fas fa-check-circle"></i> Selesai</h4>
                <div class="number">
                    <?php 
                    $selesai_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM pemesanan WHERE user_id=$user_id AND status='selesai'");
                    $selesai = mysqli_fetch_assoc($selesai_query);
                    echo $selesai['total'];
                    ?>
                </div>
            </div>
        </div>

        <!-- Tabel Pemesanan -->
        <div class="card">
            <h2 style="font-size: 24px; margin-bottom: 20px;"><i class="fas fa-list-alt"></i> Pemesanan Saya</h2>
            
            <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th><i class="fas fa-barcode"></i> Kode Booking</th>
                                <th><i class="fas fa-building"></i> Gedung</th>
                                <th><i class="fas fa-calendar"></i> Tanggal Sewa</th>
                                <th><i class="fas fa-money-bill"></i> Total Harga</th>
                                <th><i class="fas fa-tag"></i> Status</th>
                                <th><i class="fas fa-cog"></i> Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reset result pointer
                            mysqli_data_seek($result, 0);
                            while($row = mysqli_fetch_assoc($result)): 
                            ?>
                            <tr>
                                <td><strong><?php echo $row['kode_booking']; ?></strong></td>
                                <td><?php echo $row['nama_gedung']; ?></td>
                                <td><?php echo formatTanggal($row['tanggal_mulai']); ?> <br> <small>sd</small> <br> <?php echo formatTanggal($row['tanggal_selesai']); ?></td>
                                <td><?php echo rupiah($row['total_harga']); ?></td>
                                <td>
                                    <?php
                                    $status_text = '';
                                    $status_color = '';
                                    switch($row['status']) {
                                        case 'menunggu_pembayaran':
                                            $status_text = 'Menunggu Pembayaran';
                                            $status_color = '#ff9800';
                                            break;
                                        case 'pending':
                                            $status_text = 'Pending Verifikasi';
                                            $status_color = '#FFC107';
                                            break;
                                        case 'dikonfirmasi':
                                            $status_text = 'Dikonfirmasi';
                                            $status_color = '#4CAF50';
                                            break;
                                        case 'selesai':
                                            $status_text = 'Selesai';
                                            $status_color = '#2E7D32';
                                            break;
                                        case 'dibatalkan':
                                            $status_text = 'Dibatalkan';
                                            $status_color = '#f44336';
                                            break;
                                        default:
                                            $status_text = $row['status'];
                                            $status_color = '#999';
                                    }
                                    ?>
                                    <span style="background: <?php echo $status_color; ?>; color: white; padding: 5px 12px; border-radius: 25px; font-size: 12px; font-weight: bold;">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($row['status'] == 'menunggu_pembayaran'): ?>
                                        <a href="payment.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm" style="background: #2E7D32; padding: 8px 16px; border-radius: 25px; text-decoration: none; color: white;">
                                            <i class="fas fa-upload"></i> Upload Bayar
                                        </a>
                                    <?php elseif($row['status'] == 'pending'): ?>
                                        <span style="color: #ff9800;"><i class="fas fa-hourglass-half"></i> Menunggu Konfirmasi</span>
                                    <?php elseif($row['status'] == 'dikonfirmasi'): ?>
                                        <span style="color: #4CAF50;"><i class="fas fa-check-circle"></i> Booking Terkonfirmasi</span>
                                    <?php elseif($row['status'] == 'selesai'): ?>
                                        <span style="color: #2E7D32;"><i class="fas fa-check-double"></i> Selesai</span>
                                    <?php elseif($row['status'] == 'dibatalkan'): ?>
                                        <span style="color: #f44336;"><i class="fas fa-times-circle"></i> Dibatalkan</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada pemesanan.</p>
                    <a href="index.php" class="btn-sewa"><i class="fas fa-calendar-alt"></i> Sewa Gedung Sekarang</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="footer">
        <p><i class="fas fa-copyright"></i> 2026 MWCNU Sewa Gedung - Nahdlatul Ulama | All Rights Reserved</p>
        <p><i class="fas fa-heart" style="color: #FFC107;"></i> Melayani dengan hati, mengutamakan kepuasan Anda</p>
    </div>
</body>
</html>