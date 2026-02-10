<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

// =======================
// DATA PENGEMBALIAN
// =======================
$data = mysqli_query($conn, "
    SELECT peminjaman.*, 
           users.nama AS nama_user, 
           alat.nama_alat
    FROM peminjaman
    JOIN users ON peminjaman.user_id = users.id
    JOIN alat ON peminjaman.alat_id = alat.id
    WHERE peminjaman.status='dikembalikan'
    ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pengembalian</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

<h1 class="text-2xl font-bold mb-4">Data Pengembalian Alat</h1>

<div class="bg-white p-6 rounded shadow overflow-x-auto">
<table class="w-full border">
    <tr class="bg-gray-200">
        <th class="border p-2">No</th>
        <th class="border p-2">User</th>
        <th class="border p-2">Alat</th>
        <th class="border p-2">Jumlah</th>
        <th class="border p-2">Tgl Pinjam</th>
        <th class="border p-2">Tgl Kembali</th>
        <th class="border p-2">Keterangan</th>
    </tr>

    <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
    <tr>
        <td class="border p-2 text-center"><?= $no++ ?></td>
        <td class="border p-2"><?= htmlspecialchars($row['nama_user']) ?></td>
        <td class="border p-2"><?= htmlspecialchars($row['nama_alat']) ?></td>
        <td class="border p-2 text-center"><?= $row['jumlah'] ?></td>
        <td class="border p-2"><?= $row['tanggal_pinjam'] ?></td>
        <td class="border p-2"><?= $row['tanggal_kembali'] ?></td>
        <td class="border p-2 text-center">
            <?php if ($row['tanggal_kembali'] < $row['tanggal_pinjam']): ?>
                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm">
                    Terlambat
                </span>
            <?php else: ?>
                <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm">
                    Tepat Waktu
                </span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

<a href="dashboard.php" class="inline-block mt-6 text-blue-600">
    ← Kembali ke Dashboard
</a>

</body>
</html>
