<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

$data = mysqli_query($conn, "
    SELECT peminjaman.*, users.nama, alat.nama_alat
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
<title>Pengembalian</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex">

<?php include 'layout/sidebar.php'; ?>

<main class="flex-1 p-8">


<div class="bg-white p-6 rounded-xl shadow">
<table class="w-full border">
<tr class="bg-gray-200">
    <th class="border p-2">No</th>
    <th class="border p-2">User</th>
    <th class="border p-2">Alat</th>
    <th class="border p-2">Tanggal Kembali</th>
</tr>

<?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
<tr>
    <td class="border p-2 text-center"><?= $no++ ?></td>
    <td class="border p-2"><?= $row['nama'] ?></td>
    <td class="border p-2"><?= $row['nama_alat'] ?></td>
    <td class="border p-2 text-center"><?= $row['tanggal_kembali'] ?></td>
</tr>
<?php endwhile; ?>
</table>
</div>
</main>
</body>
</html>
