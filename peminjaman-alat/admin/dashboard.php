<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');



// =======================
// STATISTIK
// =======================
$total_alat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM alat"))['total'];
$total_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM users WHERE role='user'"))['total'];
$total_petugas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) total FROM users WHERE role='petugas'"))['total'];
$peminjaman_aktif = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total 
    FROM peminjaman 
    WHERE status='disetujui'
"))['total'];

$terlambat = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total 
    FROM peminjaman 
    WHERE status='disetujui' 
    AND tanggal_kembali < CURDATE()
"))['total'];

$log = mysqli_query($conn, "
    SELECT log_aktivitas.*, users.nama 
    FROM log_aktivitas 
    JOIN users ON log_aktivitas.user_id = users.id
    ORDER BY log_aktivitas.id DESC 
    LIMIT 5
");

$pinjamTerbaru = mysqli_query($conn, "
    SELECT peminjaman.*, users.nama, alat.nama_alat 
    FROM peminjaman
    JOIN users ON peminjaman.user_id = users.id
    JOIN alat ON peminjaman.alat_id = alat.id
    ORDER BY peminjaman.id DESC
    LIMIT 5
");

?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .card {
            transition: all .3s ease;
        }
        .card:hover {
            transform: translateY(-6px) scale(1.02);
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
 
<body class="bg-gray-100 min-h-screen flex">

<!-- ================= SIDEBAR ================= -->
<aside class="w-64 bg-gradient-to-b from-blue-600 to-purple-700 text-white min-h-screen p-5">
    <h2 class="text-2xl font-bold mb-8">Admin Panel</h2>

    <ul class="space-y-3">
        <li><a href="dashboard.php" class="block hover:bg-white/20 p-2 rounded">📊 Dashboard</a></li>
        <li><a href="petugas.php" class="block hover:bg-white/20 p-2 rounded">👮 Petugas</a></li>
        <li><a href="users.php" class="block hover:bg-white/20 p-2 rounded">👤 User</a></li>
        <li><a href="alat.php" class="block hover:bg-white/20 p-2 rounded">🛠️ Alat</a></li>
        <li><a href="kategori.php" class="block hover:bg-white/20 p-2 rounded">📂 Kategori</a></li>
        <li><a href="peminjaman.php" class="block hover:bg-white/20 p-2 rounded">📄 Peminjaman</a></li>
        <li><a href="pengembalian.php" class="block hover:bg-white/20 p-2 rounded">↩️ Pengembalian</a></li>
        <li><a href="laporan.php" class="block hover:bg-white/20 p-2 rounded">🖨️ Laporan</a></li>
        <li><a href="log_aktivitas.php" class="block hover:bg-white/20 p-2 rounded">🧾 Log Aktivitas</a></li>
        <li>
            <a href="../auth/logout.php"
               class="block bg-red-500 hover:bg-red-600 p-2 rounded mt-6 text-center">
                🚪 Logout
            </a>
        </li>
    </ul>
</aside>

<!-- ================= MAIN ================= -->
<main class="flex-1 p-8">
    <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
    <p class="text-gray-600 mb-6">Selamat datang, <b><?= $_SESSION['nama']; ?></b></p>

    <!-- ================= STAT CARD ================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">

        <div class="card bg-white p-5 rounded-xl shadow">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-toolbox text-3xl text-blue-600"></i>
                <div>
                    <p class="text-gray-500 text-sm">Total Alat</p>
                    <h2 class="text-2xl font-bold"><?= $total_alat ?></h2>
                </div>
            </div>
        </div>

        <div class="card bg-white p-5 rounded-xl shadow">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-users text-3xl text-green-600"></i>
                <div>
                    <p class="text-gray-500 text-sm">Total User</p>
                    <h2 class="text-2xl font-bold"><?= $total_user ?></h2>
                </div>
            </div>
        </div>

        <div class="card bg-white p-5 rounded-xl shadow">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-user-shield text-3xl text-purple-600"></i>
                <div>
                    <p class="text-gray-500 text-sm">Total Petugas</p>
                    <h2 class="text-2xl font-bold"><?= $total_petugas ?></h2>
                </div>
            </div>
        </div>

        <div class="card bg-white p-5 rounded-xl shadow">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-hand-holding text-3xl text-yellow-500"></i>
                <div>
                    <p class="text-gray-500 text-sm">Peminjaman Aktif</p>
                    <h2 class="text-2xl font-bold"><?= $peminjaman_aktif ?></h2>
                </div>
            </div>
        </div>

        <div class="card bg-white p-5 rounded-xl shadow border-l-4 border-red-500">
            <div class="flex items-center gap-4">
                <i class="fa-solid fa-clock text-3xl text-red-500"></i>
                <div>
                    <p class="text-gray-500 text-sm">Terlambat</p>
                    <h2 class="text-2xl font-bold"><?= $terlambat ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

<!-- Peminjaman Terbaru -->
<div class="bg-white p-5 rounded shadow dashboard-card">
<h2 class="font-bold mb-3">Peminjaman Terbaru</h2>
<ul class="text-sm space-y-2">
<?php while($p=mysqli_fetch_assoc($pinjamTerbaru)): ?>
<li class="border-b pb-1">
<b><?= $p['nama'] ?></b> meminjam <b><?= $p['nama_alat'] ?></b>
<span class="text-gray-500">(<?= $p['status'] ?>)</span>
</li>
<?php endwhile; ?>
</ul>
</div>

<!-- Log Aktivitas -->
<div class="bg-white p-5 rounded shadow dashboard-card">
<h2 class="font-bold mb-3">Aktivitas Terakhir</h2>
<ul class="text-sm space-y-2">
<?php while($l=mysqli_fetch_assoc($log)): ?>
<li class="border-b pb-1">
<b><?= $l['nama'] ?></b> — <?= $l['aktivitas'] ?>
</li>
<?php endwhile; ?>
</ul>
</div>

</div>

</main>

</body>
</html>
