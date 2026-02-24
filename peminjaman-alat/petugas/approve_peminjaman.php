<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('petugas');

/* =======================
   SETUJUI
======================= */
if (isset($_GET['setujui'])) {
    $id = $_GET['setujui'];

    $pinjam = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM peminjaman WHERE id='$id'
    "));

    $alat = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT stok FROM alat WHERE id='$pinjam[alat_id]'
    "));

    if ($pinjam['jumlah'] > $alat['stok']) {
        echo "<script>
            alert('Stok tidak mencukupi!');
            location='approve_peminjaman.php';
        </script>";
        exit;
    }

    $sisa = $alat['stok'] - $pinjam['jumlah'];

    mysqli_query($conn, "
        UPDATE alat 
        SET stok='$sisa', 
            status = IF('$sisa' = 0, 'dipinjam', status)
        WHERE id='$pinjam[alat_id]'
    ");

    mysqli_query($conn, "
        UPDATE peminjaman 
        SET status='disetujui'
        WHERE id='$id'
    ");

    echo "<script>
        alert('Peminjaman disetujui');
        location='approve_peminjaman.php';
    </script>";
}

/* =======================
   TOLAK
======================= */
if (isset($_GET['tolak'])) {
    $id = $_GET['tolak'];

    mysqli_query($conn, "
        UPDATE peminjaman SET status='ditolak' WHERE id='$id'
    ");

    echo "<script>
        alert('Peminjaman ditolak');
        location='approve_peminjaman.php';
    </script>";
}

/* =======================
   DATA PENDING
======================= */
$data = mysqli_query($conn, "
    SELECT peminjaman.*, users.nama, alat.nama_alat
    FROM peminjaman
    JOIN users ON peminjaman.user_id = users.id
    JOIN alat ON peminjaman.alat_id = alat.id
    WHERE peminjaman.status = 'pending'
    ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Approve Peminjaman</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex overflow-hidden">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8 overflow-y-auto">

<h1 class="text-2xl font-bold mb-6">Persetujuan Peminjaman</h1>

<div class="bg-white p-6 rounded-xl shadow">
<div class="overflow-x-auto">

<table class="w-full border">
<tr class="bg-gray-200">
    <th class="border p-2">User</th>
    <th class="border p-2">Alat</th>
    <th class="border p-2">Tgl Kembali</th>
    <th class="border p-2 text-center">Aksi</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)): ?>
<tr class="hover:bg-gray-50">
    <td class="border p-2"><?= $row['nama'] ?></td>
    <td class="border p-2"><?= $row['nama_alat'] ?></td>
    <td class="border p-2"><?= $row['tanggal_kembali'] ?></td>
    <td class="border p-2 text-center space-x-2">
        <a href="?setujui=<?= $row['id'] ?>"
           onclick="return confirm('Setujui peminjaman ini?')"
           class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
            Setujui
        </a>
        <a href="?tolak=<?= $row['id'] ?>"
           onclick="return confirm('Tolak peminjaman ini?')"
           class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
            Tolak
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>

</div>
</div>
</main>
</body>
</html>
