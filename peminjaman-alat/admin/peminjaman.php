<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

$data = mysqli_query($conn, "
    SELECT peminjaman.*, users.nama, alat.nama_alat
    FROM peminjaman
    JOIN users ON peminjaman.user_id = users.id
    JOIN alat ON peminjaman.alat_id = alat.id
    ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
<h1 class="text-2xl font-bold mb-4">Data Peminjaman</h1>

<div class="bg-white p-6 rounded shadow">
<table class="w-full border">
<tr class="bg-gray-200">
    <th class="border p-2">User</th>
    <th class="border p-2">Alat</th>
    <th class="border p-2">Pinjam</th>
    <th class="border p-2">Kembali</th>
    <th class="border p-2">Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)): ?>
<tr>
    <td class="border p-2"><?= $row['nama'] ?></td>
    <td class="border p-2"><?= $row['nama_alat'] ?></td>
    <td class="border p-2"><?= $row['tanggal_pinjam'] ?></td>
    <td class="border p-2"><?= $row['tanggal_kembali'] ?></td>
    <td class="border p-2"><?= $row['status'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>
<a href="dashboard.php" class="inline-block mt-6 text-blue-600">
    ← Kembali ke Dashboard
</a>
</body>
</html>
