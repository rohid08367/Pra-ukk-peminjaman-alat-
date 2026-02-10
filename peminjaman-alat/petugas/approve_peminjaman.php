<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('petugas');

if (isset($_GET['setujui'])) {
    $id = $_GET['setujui'];

    $pinjam = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM peminjaman WHERE id='$id'
    "));

    $alat = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT stok FROM alat WHERE id='$pinjam[alat_id]'
    "));

    // CEK STOK ULANG (AMAN)
    if ($pinjam['jumlah'] > $alat['stok']) {
        echo "<script>
            alert('Stok tidak mencukupi!');
            location='approve_peminjaman.php';
        </script>";
        exit;
    }

    // KURANGI STOK
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

    echo "<script>alert('Peminjaman disetujui');location='approve_peminjaman.php';</script>";
}

// =======================
// TOLAK
// =======================
if (isset($_GET['tolak'])) {
    $id = $_GET['tolak'];

    mysqli_query($conn, "
        UPDATE peminjaman SET status='ditolak' WHERE id='$id'
    ");

    echo "<script>alert('Peminjaman ditolak');location='approve_peminjaman.php';</script>";
}

// =======================
// DATA PENDING
// =======================
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
<html>
<head>
    <title>Approve Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
<h1 class="text-2xl font-bold mb-4">Persetujuan Peminjaman</h1>

<div class="bg-white p-6 rounded shadow">
<table class="w-full border">
<tr class="bg-gray-200">
    <th class="border p-2">User</th>
    <th class="border p-2">Alat</th>
    <th class="border p-2">Tgl Kembali</th>
    <th class="border p-2">Aksi</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)): ?>
<tr>
    <td class="border p-2"><?= $row['nama'] ?></td>
    <td class="border p-2"><?= $row['nama_alat'] ?></td>
    <td class="border p-2"><?= $row['tanggal_kembali'] ?></td>
    <td class="border p-2 text-center">
        <a href="?setujui=<?= $row['id'] ?>" class="bg-green-500 text-white px-3 py-1 rounded">Setujui</a>
        <a href="?tolak=<?= $row['id'] ?>" class="bg-red-500 text-white px-3 py-1 rounded">Tolak</a>
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
