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
    SELECT COUNT(*) total FROM peminjaman WHERE status='disetujui'
"))['total'];

$terlambat = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM peminjaman 
    WHERE status='disetujui' AND tanggal_kembali < CURDATE()
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

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .card {
        transition: all .3s ease;
    }
    .card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 15px 30px rgba(0,0,0,0.12);
    }

    .fade-in {
        animation: fadeIn .6s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .badge {
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }
    .badge-green { background:#dcfce7; color:#166534; }
    .badge-yellow{ background:#fef9c3; color:#854d0e; }
    .badge-red   { background:#fee2e2; color:#991b1b; }

    .list-hover li:hover {
        background:#f9fafb;
        padding-left:6px;
        transition:.2s;
    }
</style>
</head>

<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>



<!-- MAIN -->
<main class="flex-1 p-8 fade-in">
<h1 class="text-3xl font-bold mb-2"></h1>
<p class="text-gray-600 mb-6">Selamat datang, <b><?= $_SESSION['nama']; ?></b></p>

<!-- STAT -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
<?php
$cards = [
    ['Total Alat',$total_alat,'toolbox','blue'],
    ['Total User',$total_user,'users','green'],
    ['Total Petugas',$total_petugas,'user-shield','purple'],
    ['Peminjaman Aktif',$peminjaman_aktif,'hand-holding','yellow'],
    ['Terlambat',$terlambat,'clock','red']
];
foreach($cards as $c):
?>
<div class="card bg-white p-5 rounded-xl shadow <?= $c[4]=='red'?'border-l-4 border-red-500':'' ?>">
    <div class="flex items-center gap-4">
        <i class="fa-solid fa-<?= $c[2] ?> text-3xl text-<?= $c[3] ?>-500"></i>
        <div>
            <p class="text-gray-500 text-sm"><?= $c[0] ?></p>
            <h2 class="text-2xl font-bold"><?= $c[1] ?></h2>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<!-- LIST -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

<div class="bg-white p-5 rounded shadow">
<h2 class="font-bold mb-3">Peminjaman Terbaru</h2>
<ul class="text-sm space-y-2 list-hover">
<?php while($p=mysqli_fetch_assoc($pinjamTerbaru)): 
    $badge='badge-yellow';
    if($p['status']=='disetujui')$badge='badge-green';
    if($p['status']=='ditolak')$badge='badge-red';
?>
<li class="border-b pb-1">
<b><?= $p['nama'] ?></b> meminjam <b><?= $p['nama_alat'] ?></b>
<span class="badge <?= $badge ?>"><?= ucfirst($p['status']) ?></span>
</li>
<?php endwhile; ?>
</ul>
</div>

<div class="bg-white p-5 rounded shadow">
<h2 class="font-bold mb-3">Aktivitas Terakhir</h2>
<ul class="text-sm space-y-2 list-hover">
<?php while($l=mysqli_fetch_assoc($log)): ?>
<li class="border-b pb-1 flex items-center gap-2">
<span class="w-2 h-2 bg-blue-500 rounded-full"></span>
<b><?= $l['nama'] ?></b> — <?= $l['aktivitas'] ?>
</li>
<?php endwhile; ?>
</ul>
</div>

</div>
</main>
</body>
</html>
