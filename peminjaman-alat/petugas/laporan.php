<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('petugas');

// =======================
// FILTER TANGGAL
// =======================
$where = "";
if (isset($_GET['dari']) && isset($_GET['sampai'])) {
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    $where = "WHERE tanggal_pinjam BETWEEN '$dari' AND '$sampai'";
}

// =======================
// DATA LAPORAN
// =======================
$data = mysqli_query($conn, "
    SELECT peminjaman.*, users.nama, alat.nama_alat
    FROM peminjaman
    JOIN users ON peminjaman.user_id = users.id
    JOIN alat ON peminjaman.alat_id = alat.id
    $where
    ORDER BY peminjaman.tanggal_pinjam DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Petugas</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex overflow-hidden">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8 overflow-y-auto">

<h1 class="text-2xl font-bold mb-6">Laporan Peminjaman (Petugas)</h1>

<!-- ================= FILTER ================= -->
<form method="GET" class="bg-white p-4 rounded-xl shadow mb-6 flex flex-wrap gap-4 items-end">
    
    <div class="flex flex-col">
        <label class="text-sm text-gray-600 mb-1">Dari</label>
        <input type="date" name="dari"
               value="<?= $_GET['dari'] ?? '' ?>"
               required
               class="border p-2 rounded">
    </div>

    <div class="flex flex-col">
        <label class="text-sm text-gray-600 mb-1">Sampai</label>
        <input type="date" name="sampai"
               value="<?= $_GET['sampai'] ?? '' ?>"
               required
               class="border p-2 rounded">
    </div>

    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Filter
    </button>
</form>

<!-- ================= TABEL ================= -->
<div class="bg-white p-6 rounded-xl shadow">

<div class="overflow-y-auto max-h-[60vh]">
<table class="w-full border">
<tr class="bg-gray-200 sticky top-0 z-10">
    <th class="border p-2">User</th>
    <th class="border p-2">Alat</th>
    <th class="border p-2">Jumlah</th>
    <th class="border p-2">Pinjam</th>
    <th class="border p-2">Kembali</th>
    <th class="border p-2">Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)): ?>
<tr class="hover:bg-gray-50">
    <td class="border p-2"><?= $row['nama']; ?></td>
    <td class="border p-2"><?= $row['nama_alat']; ?></td>
    <td class="border p-2 text-center"><?= $row['jumlah']; ?></td>
    <td class="border p-2"><?= $row['tanggal_pinjam']; ?></td>
    <td class="border p-2"><?= $row['tanggal_kembali']; ?></td>
    <td class="border p-2 text-center">
        <?php if ($row['status'] == 'disetujui'): ?>
            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">
                Disetujui
            </span>
        <?php elseif ($row['status'] == 'pending'): ?>
            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">
                Pending
            </span>
        <?php elseif ($row['status'] == 'ditolak'): ?>
            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm">
                Ditolak
            </span>
        <?php else: ?>
            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm">
                <?= $row['status']; ?>
            </span>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>

</div>

</main>
</body>
</html>
