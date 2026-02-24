<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('petugas');

/* =======================
   PROSES PENGEMBALIAN
======================= */
if (isset($_GET['kembali'])) {
    $id = $_GET['kembali'];

    $pinjam = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT 
            peminjaman.*,
            alat.stok,
            alat.id AS alat_id,
            alat.denda_per_hari,
            users.id AS user_id
        FROM peminjaman
        JOIN alat ON peminjaman.alat_id = alat.id
        JOIN users ON peminjaman.user_id = users.id
        WHERE peminjaman.id='$id'
    "));

    if (!$pinjam) {
        echo "<script>alert('Data peminjaman tidak ditemukan');</script>";
        exit;
    }

    /* =======================
       HITUNG DENDA
    ======================= */
    $hari_ini    = new DateTime(date('Y-m-d'));
    $tgl_kembali = new DateTime($pinjam['tanggal_kembali']);

    $terlambat = 0;
    if ($hari_ini > $tgl_kembali) {
        $terlambat = $tgl_kembali->diff($hari_ini)->days;
    }

    $denda = $terlambat * (int)$pinjam['denda_per_hari'];

    /* =======================
       KEMBALIKAN STOK
    ======================= */
    $stok_baru = $pinjam['stok'] + $pinjam['jumlah'];

    mysqli_query($conn, "
        UPDATE alat 
        SET stok='$stok_baru',
            status='tersedia'
        WHERE id='$pinjam[alat_id]'
    ");

    /* =======================
       UPDATE PEMINJAMAN
    ======================= */
    mysqli_query($conn, "
        UPDATE peminjaman 
        SET status='dikembalikan',
            denda='$denda'
        WHERE id='$id'
    ");

    /* =======================
       LOG PETUGAS
    ======================= */
    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES (
            '$_SESSION[id]',
            'Memproses pengembalian alat (Denda user: Rp " . number_format($denda) . ")'
        )
    ");

    echo "<script>
        alert('Pengembalian berhasil. Denda user: Rp " . number_format($denda) . "');
        location='pengembalian.php';
    </script>";
    exit;
}

/* =======================
   DATA PEMINJAMAN AKTIF
======================= */
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
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex overflow-hidden">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8 overflow-y-auto">

<h1 class="text-2xl font-bold mb-6">Pengembalian Alat</h1>

<div class="bg-white p-6 rounded-xl shadow">
<div class="overflow-y-auto max-h-[70vh]">

<table class="w-full border">
<tr class="bg-gray-200 sticky top-0 z-10">
    <th class="border p-2">User</th>
    <th class="border p-2">Alat</th>
    <th class="border p-2">Jumlah</th>
    <th class="border p-2">Tgl Kembali</th>
    <th class="border p-2">Keterangan</th>
    <th class="border p-2">Aksi</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)): ?>
<tr class="hover:bg-gray-50">
    <td class="border p-2"><?= htmlspecialchars($row['nama']) ?></td>
    <td class="border p-2"><?= htmlspecialchars($row['nama_alat']) ?></td>
    <td class="border p-2 text-center"><?= $row['jumlah'] ?></td>
    <td class="border p-2"><?= $row['tanggal_kembali'] ?></td>
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
        <a href="?kembali=<?= $row['id'] ?>"
           onclick="return confirm('Proses pengembalian alat ini?')"
           class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
           Proses
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
