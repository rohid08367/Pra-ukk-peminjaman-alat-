<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('petugas');

// =======================
// STATISTIK PETUGAS
// =======================
$pending = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM peminjaman WHERE status='pending'
"))['total'];

$dipinjam = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM peminjaman WHERE status='disetujui'
"))['total'];

$pengembalian = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM peminjaman WHERE status='dikembalikan'
"))['total'];

$pendingList = mysqli_query($conn, "
    SELECT peminjaman.*, users.nama, alat.nama_alat
    FROM peminjaman
    JOIN users ON peminjaman.user_id = users.id
    JOIN alat ON peminjaman.alat_id = alat.id
    WHERE peminjaman.status='pending'
    ORDER BY peminjaman.id DESC
    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Petugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-100 min-h-screen flex">

<!-- ================= SIDEBAR ================= -->
<aside class="w-64 bg-green-600 text-white p-6 flex flex-col">
    <h2 class="text-xl font-bold mb-8">Petugas</h2>

    <ul class="space-y-3 flex-1">
        <li>
            <a href="dashboard.php"
               class="block bg-white/20 p-2 rounded">
               🏠 Dashboard
            </a>
        </li>
        <li>
            <a href="approve_peminjaman.php"
               class="block hover:bg-white/20 p-2 rounded">
               ✅ Approve
            </a>
        </li>
        <li>
            <a href="pengembalian.php"
               class="block hover:bg-white/20 p-2 rounded">
               ↩️ Pengembalian
            </a>
        </li>
        <li>
            <a href="laporan.php"
               class="block hover:bg-white/20 p-2 rounded">
               📊 Laporan
            </a>
        </li>

    <a href="../auth/logout.php"
       class="block bg-red-500 hover:bg-red-600 p-2 rounded text-center">
       🚪 Logout
    </a>

    </ul>


</aside>

<!-- ================= MAIN ================= -->
<main class="flex-1 p-8">
    <h1 class="text-2xl font-bold mb-2">Dashboard Petugas</h1>
    <p class="mb-6 text-gray-600">
        Selamat datang, <b><?= $_SESSION['nama']; ?></b>
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded shadow">
            <p class="text-gray-500">Peminjaman Pending</p>
            <h2 class="text-3xl font-bold text-yellow-500">
                <?= $pending ?>
            </h2>
        </div>

        <div class="bg-white p-5 rounded shadow">
            <p class="text-gray-500">Sedang Dipinjam</p>
            <h2 class="text-3xl font-bold text-blue-600">
                <?= $dipinjam ?>
            </h2>
        </div>

        <div class="bg-white p-5 rounded shadow">
            <p class="text-gray-500">Sudah Dikembalikan</p>
            <h2 class="text-3xl font-bold text-green-600">
                <?= $pengembalian ?>
            </h2>
        </div>
    </div>
    <div class="bg-white p-5 rounded shadow mt-6 dashboard-card">
<h2 class="font-bold mb-3">Peminjaman Pending</h2>

<?php if(mysqli_num_rows($pendingList)==0): ?>
<p class="text-gray-500 text-sm">Tidak ada peminjaman pending</p>
<?php else: ?>
<ul class="text-sm space-y-2">
<?php while($p=mysqli_fetch_assoc($pendingList)): ?>
<li class="border-b pb-1 flex justify-between">
<span><?= $p['nama'] ?> - <?= $p['nama_alat'] ?></span>
<a href="approve_peminjaman.php" class="text-blue-600">Lihat</a>
</li>
<?php endwhile; ?>
</ul>
<?php endif; ?>
</div>

</main>

</body>
</html>
