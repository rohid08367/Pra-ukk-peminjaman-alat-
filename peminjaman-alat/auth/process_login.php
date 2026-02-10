<?php
require '../config/database.php';

$email    = $_POST['email'];
$password = $_POST['password'];

// =======================
// CEK EMAIL
// =======================
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if (mysqli_num_rows($query) === 0) {
    echo "<script>
        alert('Email tidak terdaftar');
        location='login.php';
    </script>";
    exit;
}

$user = mysqli_fetch_assoc($query);

// =======================
// CEK PASSWORD
// =======================
if (!password_verify($password, $user['password'])) {
    echo "<script>
        alert('Password salah');
        location='login.php';
    </script>";
    exit;
}

// =======================
// LOGIN BERHASIL
// =======================
session_start();
$_SESSION['login'] = true;
$_SESSION['id']    = $user['id'];
$_SESSION['nama']  = $user['nama'];
$_SESSION['role']  = $user['role'];

// LOG AKTIVITAS
mysqli_query($conn, "
    INSERT INTO log_aktivitas (user_id, aktivitas)
    VALUES ('$user[id]', 'Login ke sistem')
");

// REDIRECT ROLE
if ($user['role'] == 'admin') {
    header("Location: ../admin/dashboard.php");
} elseif ($user['role'] == 'petugas') {
    header("Location: ../petugas/dashboard.php");
} else {
    header("Location: ../user/dashboard.php");
}
exit;
