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
    
</head>

<body class="bg-gray-100 p-8">

<h1 class="text-2xl font-bold mb-4">Laporan Peminjaman (Petugas)</h1>

<!-- FILTER -->
<form method="GET" class="bg-white p-4 rounded shadow mb-6 flex flex-wrap gap-4">
    <input type="date" name="dari" required class="border p-2 rounded">
    <input type="date" name="sampai" required class="border p-2 rounded">
    <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Filter
    </button>
</form>

<!-- TABEL -->
<div class="bg-white p-6 rounded shadow overflow-x-auto">
<table class="w-full border">
<tr class="bg-gray-200">
    <th class="border p-2">User</th>
    <th class="border p-2">Alat</th>
    <th class="border p-2">Jumlah</th>
    <th class="border p-2">Pinjam</th>
    <th class="border p-2">Kembali</th>
    <th class="border p-2">Status</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)): ?>
<tr>
    <td class="border p-2"><?= $row['nama']; ?></td>
    <td class="border p-2"><?= $row['nama_alat']; ?></td>
    <td class="border p-2 text-center"><?= $row['jumlah']; ?></td>
    <td class="border p-2"><?= $row['tanggal_pinjam']; ?></td>
    <td class="border p-2"><?= $row['tanggal_kembali']; ?></td>
    <td class="border p-2 text-center"><?= $row['status']; ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>

<a href="dashboard.php" class="inline-block mt-6 text-green-600">← Kembali</a>

</body>
</html>
