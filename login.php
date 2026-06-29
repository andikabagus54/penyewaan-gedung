<?php
require_once 'config/database.php';

if(isLoggedIn()) {
    if(isAdmin()) redirect('admin/index.php');
    else redirect('dashboard.php');
}

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    
    if(mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        
        if($user['role'] == 'admin') redirect('admin/index.php');
        else redirect('dashboard.php');
    } else {
        $error = "❌ Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Login - MWCNU Sewa Gedung</title>
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
                <i class="fas fa-building"></i>
                <h3>🌿 MWCNU Sewa Gedung</h3>
                <p class="auth-subtitle">Silakan login untuk melanjutkan</p>
            </div>
            
            <?php if($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" placeholder="Masukkan username" required autofocus>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div style="margin: 25px 0; text-align: center;">
                <p style="font-size: 14px; color: #666;">
                    Belum punya akun? <a href="register.php" style="color: #2E7D32; font-weight: bold;">Daftar disini</a>
                </p>
            </div>
            
            <div style="background: #f5f5f5; padding: 14px; border-radius: 12px; text-align: center;">
                <p style="font-size: 13px; color: #888; margin: 0;">
                    <i class="fas fa-info-circle"></i> Demo Admin: <strong>admin</strong> / <strong>admin123</strong>
                </p>
            </div>
        </div>
    </div>
</body>
</html>