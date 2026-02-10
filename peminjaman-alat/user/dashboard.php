<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('user');

// =======================
// STATISTIK USER
// =======================
$aktif = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM peminjaman 
    WHERE user_id='$_SESSION[id]' AND status='disetujui'
"))['total'];

$riwayat = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM peminjaman 
    WHERE user_id='$_SESSION[id]'
"))['total'];

$pinjamanAktifList = mysqli_query($conn, "
    SELECT peminjaman.*, alat.nama_alat 
    FROM peminjaman
    JOIN alat ON peminjaman.alat_id = alat.id
    WHERE peminjaman.user_id='$_SESSION[id]'
    AND peminjaman.status IN ('disetujui','menunggu_pengembalian')
    ORDER BY peminjaman.id DESC
    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<aside class="w-64 bg-blue-600 text-white p-6">
    <h2 class="text-xl font-bold mb-6">User</h2>
    <ul class="space-y-3">
        <li><a href="dashboard.php" class="block hover:bg-white/20 p-2 rounded">🏠 Dashboard</a></li>
        <li><a href="pinjam.php" class="block hover:bg-white/20 p-2 rounded">📄 Pinjam Alat</a></li>
        <li><a href="pengembalian.php" class="block hover:bg-white/20 p-2 rounded">↩️ Pengembalian</a></li>
        <li><a href="riwayat.php" class="block hover:bg-white/20 p-2 rounded">🕘 Riwayat</a></li>
        <li><a href="../auth/logout.php" class="block bg-red-500 p-2 rounded text-center">🚪 Logout</a></li>
    </ul>
</aside>

<!-- MAIN -->
<main class="flex-1 p-8">
    <h1 class="text-2xl font-bold mb-2">Dashboard User</h1>
    <p class="mb-6 text-gray-600">Halo, <b><?= $_SESSION['nama']; ?></b></p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <p class="text-gray-500">Peminjaman Aktif</p>
            <h2 class="text-3xl font-bold text-blue-600"><?= $aktif ?></h2>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <p class="text-gray-500">Total Riwayat</p>
            <h2 class="text-3xl font-bold text-green-600"><?= $riwayat ?></h2>
        </div>
    </div>

    <div class="bg-white p-5 rounded shadow mt-6 dashboard-card">
<h2 class="font-bold mb-3">Pinjaman Aktif</h2>

<?php if(mysqli_num_rows($pinjamanAktifList)==0): ?>
<p class="text-gray-500 text-sm">Tidak ada pinjaman aktif</p>
<?php else: ?>
<ul class="text-sm space-y-2">
<?php while($p=mysqli_fetch_assoc($pinjamanAktifList)): ?>
<li class="border-b pb-1 flex justify-between">
<span><?= $p['nama_alat'] ?></span>
<span class="text-blue-600"><?= $p['status'] ?></span>
</li>
<?php endwhile; ?>
</ul>
<?php endif; ?>
</div>

</main>

</body>
</html>
