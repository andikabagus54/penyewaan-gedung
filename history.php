<?php
require_once 'config/database.php';
if(!isLoggedIn()) redirect('login.php');

$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT p.*, g.nama_gedung FROM pemesanan p JOIN gedung g ON p.gedung_id=g.id WHERE p.user_id=$user_id ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Riwayat - MWCNU Sewa Gedung</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .history-header {
            background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
            color: white;
            padding: 35px;
            border-radius: 20px;
            margin-bottom: 30px;
        }
        .history-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .history-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .stat-card-history {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            border-bottom: 3px solid #2E7D32;
        }
        .stat-card-history h4 {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .stat-card-history .number {
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
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        .filter-section input {
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            flex: 1;
            min-width: 200px;
        }
        .filter-section input:focus {
            outline: none;
            border-color: #2E7D32;
        }
    </style>
    <script>
        function filterTable() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("historyTable");
            let tr = table.getElementsByTagName("tr");
            
            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName("td");
                let found = false;
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }
    </script>
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
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="history.php" class="active"><i class="fas fa-history"></i> Riwayat</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Header Riwayat -->
        <div class="history-header">
            <h1><i class="fas fa-history"></i> Riwayat Pemesanan</h1>
            <p><i class="fas fa-info-circle"></i> Berikut adalah daftar semua pemesanan gedung yang pernah Anda lakukan.</p>
        </div>

        <!-- Statistik -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="stat-card-history">
                <h4><i class="fas fa-shopping-cart"></i> Total Pesanan</h4>
                <div class="number"><?php echo mysqli_num_rows($result); ?></div>
            </div>
            <div class="stat-card-history">
                <h4><i class="fas fa-check-circle"></i> Selesai</h4>
                <div class="number">
                    <?php 
                    $selesai_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM pemesanan WHERE user_id=$user_id AND status='selesai'");
                    $selesai = mysqli_fetch_assoc($selesai_query);
                    echo $selesai['total'];
                    ?>
                </div>
            </div>
            <div class="stat-card-history">
                <h4><i class="fas fa-times-circle"></i> Dibatalkan</h4>
                <div class="number">
                    <?php 
                    $batal_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM pemesanan WHERE user_id=$user_id AND status='dibatalkan'");
                    $batal = mysqli_fetch_assoc($batal_query);
                    echo $batal['total'];
                    ?>
                </div>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="card">
            <h2 style="font-size: 24px; margin-bottom: 20px;"><i class="fas fa-list-alt"></i> Daftar Riwayat Pemesanan</h2>
            
            <!-- Filter Pencarian -->
            <div class="filter-section">
                <i class="fas fa-search" style="color: #2E7D32; font-size: 18px;"></i>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Cari berdasarkan kode booking, gedung, atau status...">
            </div>
            
            <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="table-responsive">
                    <table id="historyTable">
                        <thead>
                            <tr>
                                <th><i class="fas fa-barcode"></i> Kode Booking</th>
                                <th><i class="fas fa-building"></i> Gedung</th>
                                <th><i class="fas fa-calendar"></i> Tanggal Sewa</th>
                                <th><i class="fas fa-money-bill"></i> Total Harga</th>
                                <th><i class="fas fa-tag"></i> Status</th>
                                <th><i class="fas fa-clock"></i> Tanggal Booking</th>
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
                                <td><?php echo formatTanggal($row['tanggal_mulai']); ?> <br> <small style="color:#888;">sd</small> <br> <?php echo formatTanggal($row['tanggal_selesai']); ?></td>
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
                                <td><?php echo formatTanggal($row['created_at']); ?> <br> <small><?php echo date('H:i', strtotime($row['created_at'])); ?></small></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada riwayat pemesanan.</p>
                    <a href="index.php" class="btn-sewa" style="background: #2E7D32; color: white; padding: 12px 28px; border-radius: 35px; text-decoration: none; display: inline-block; margin-top: 15px;">
                        <i class="fas fa-calendar-alt"></i> Sewa Gedung Sekarang
                    </a>
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