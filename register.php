<?php
require_once 'config/database.php';
if(isLoggedIn()) redirect('index.php');

$error = ''; $success = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    if(mysqli_num_rows($check) > 0) {
        $error = "❌ Username atau email sudah terdaftar!";
    } else {
        $query = "INSERT INTO users (username, password, nama_lengkap, email, no_telepon, alamat, role) 
                  VALUES ('$username', '$password', '$nama_lengkap', '$email', '$no_telepon', '$alamat', 'user')";
        if(mysqli_query($conn, $query)) {
            $success = "✅ Pendaftaran berhasil! Silahkan login.";
        } else {
            $error = "❌ Pendaftaran gagal!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Daftar - MWCNU Sewa Gedung</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="fas fa-user-plus"></i>
                <h3>🌿 Daftar Akun Baru</h3>
                <p class="auth-subtitle">Bergabunglah dengan kami</p>
            </div>
            
            <?php if($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
                <script>setTimeout(()=>{location.href='login.php'},1500)</script>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Username <span style="color:red">*</span></label>
                    <input type="text" name="username" placeholder="Buat username" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password <span style="color:red">*</span></label>
                    <input type="password" name="password" placeholder="Buat password" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> Nama Lengkap <span style="color:red">*</span></label>
                    <input type="text" name="nama_lengkap" placeholder="Nama lengkap Anda" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email <span style="color:red">*</span></label>
                    <input type="email" name="email" placeholder="Email aktif" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> No. Telepon</label>
                    <input type="text" name="no_telepon" placeholder="Contoh: 081234567890">
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <textarea name="alamat" rows="3" placeholder="Alamat lengkap"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>
            
            <div style="margin: 25px 0; text-align: center;">
                <p style="font-size: 14px; color: #666;">
                    Sudah punya akun? <a href="login.php" style="color: #2E7D32; font-weight: bold;">Login disini</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>