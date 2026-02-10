<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('user');

// =======================
// AJUKAN PENGEMBALIAN
// =======================
if (isset($_GET['kembalikan'])) {
    $id = $_GET['kembalikan'];

    // pastikan peminjaman milik user ini
    $cek = mysqli_query($conn, "
        SELECT * FROM peminjaman 
        WHERE id='$id' 
        AND user_id='$_SESSION[id]' 
        AND status='disetujui'
    ");

    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "
            UPDATE peminjaman 
            SET status='menunggu_pengembalian'
            WHERE id='$id'
        ");

        mysqli_query($conn, "
            INSERT INTO log_aktivitas (user_id, aktivitas)
            VALUES ('$_SESSION[id]', 'Mengajukan pengembalian alat')
        ");

        echo "<script>
            alert('Pengembalian berhasil diajukan, silakan serahkan alat ke petugas');
            location='pengembalian.php';
        </script>";
    }
}

// =======================
// DATA PEMINJAMAN USER
// =======================
$data = mysqli_query($conn, "
    SELECT peminjaman.*, alat.nama_alat
    FROM peminjaman
    JOIN alat ON peminjaman.alat_id = alat.id
    WHERE peminjaman.user_id='$_SESSION[id]'
    AND peminjaman.status IN ('disetujui','menunggu_pengembalian')
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengembalian Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

<h1 class="text-2xl font-bold mb-4">Pengembalian Alat</h1>

<div class="bg-white p-6 rounded shadow overflow-x-auto">
<table class="w-full border">
<tr class="bg-gray-200">
    <th class="border p-2">Alat</th>
    <th class="border p-2">Jumlah</th>
    <th class="border p-2">Tanggal Kembali</th>
    <th class="border p-2">Status</th>
    <th class="border p-2">Aksi</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)): ?>
<tr>
    <td class="border p-2"><?= htmlspecialchars($row['nama_alat']) ?></td>
    <td class="border p-2 text-center"><?= $row['jumlah'] ?></td>
    <td class="border p-2"><?= $row['tanggal_kembali'] ?></td>
    <td class="border p-2 text-center">
        <?php if ($row['status'] == 'disetujui'): ?>
            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">
                Dipinjam
            </span>
        <?php else: ?>
            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-sm">
                Menunggu Petugas
            </span>
        <?php endif; ?>
    </td>
    <td class="border p-2 text-center">
        <?php if ($row['status'] == 'disetujui'): ?>
            <a href="?kembalikan=<?= $row['id'] ?>"
               onclick="return confirm('Ajukan pengembalian alat ini?')"
               class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                Kembalikan
            </a>
        <?php else: ?>
            <span class="text-gray-500 text-sm">Diproses</span>
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
