<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('petugas');

// =======================
// PROSES PENGEMBALIAN
// =======================
if (isset($_GET['kembali'])) {
    $id = $_GET['kembali'];

    // Ambil data peminjaman
    $pinjam = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM peminjaman WHERE id='$id'
    "));

    if (!$pinjam) {
        echo "<script>alert('Data peminjaman tidak ditemukan');</script>";
        exit;
    }

    // Ambil data alat
    $alat = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM alat WHERE id='$pinjam[alat_id]'
    "));

    // =======================
    // TAMBAH STOK KEMBALI
    // =======================
    $stok_baru = $alat['stok'] + $pinjam['jumlah'];

    mysqli_query($conn, "
        UPDATE alat 
        SET stok='$stok_baru',
            status='tersedia'
        WHERE id='$pinjam[alat_id]'
    ");

    // =======================
    // UPDATE STATUS PEMINJAMAN
    // =======================
    mysqli_query($conn, "
        UPDATE peminjaman 
        SET status='dikembalikan'
        WHERE id='$id'
    ");

    // =======================
    // LOG AKTIVITAS
    // =======================
    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Memproses pengembalian alat')
    ");

    echo "<script>
        alert('Pengembalian berhasil diproses');
        location='pengembalian.php';
    </script>";
}

// =======================
// DATA PEMINJAMAN AKTIF
// =======================
$data = mysqli_query($conn, "
    SELECT peminjaman.*, users.nama, alat.nama_alat
    FROM peminjaman
    JOIN users ON peminjaman.user_id = users.id
    JOIN alat ON peminjaman.alat_id = alat.id
    WHERE peminjaman.status IN ('disetujui','menunggu_pengembalian')
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

<div class="bg-white p-6 rounded shadow">
    <table class="w-full border">
        <tr class="bg-gray-200">
            <th class="border p-2">User</th>
            <th class="border p-2">Alat</th>
            <th class="border p-2">Jumlah</th>
            <th class="border p-2">Tgl Kembali</th>
            <th class="border p-2">Keterangan</th>
            <th class="border p-2">Aksi</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td class="border p-2"><?= $row['nama']; ?></td>
            <td class="border p-2"><?= $row['nama_alat']; ?></td>
            <td class="border p-2 text-center"><?= $row['jumlah']; ?></td>
            <td class="border p-2"><?= $row['tanggal_kembali']; ?></td>
            <td class="border p-2 text-center">
                <?php if ($row['tanggal_kembali'] < date('Y-m-d')): ?>
                    <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-sm">
                        Terlambat
                    </span>
                <?php else: ?>
                    <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-sm">
                        Tepat Waktu
                    </span>
                <?php endif; ?>
            </td>
            <td class="border p-2 text-center">
                <a href="?kembali=<?= $row['id']; ?>"
                   onclick="return confirm('Proses pengembalian alat ini?')"
                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                   Proses
                </a>
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
