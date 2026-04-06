<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('user');

// =======================
// STATISTIK USER
// =======================
$aktif = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM peminjaman 
    WHERE user_id='$_SESSION[id]' 
    AND status IN ('disetujui','menunggu_pengembalian')
"))['total'];

$riwayat = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total FROM peminjaman 
    WHERE user_id='$_SESSION[id]'
"))['total'];

// =======================
// PINJAMAN AKTIF + DENDA
// =======================
$pinjamanAktifList = mysqli_query($conn, "
    SELECT 
        peminjaman.*,
        alat.nama_alat,
        alat.denda_per_hari,
        DATEDIFF(CURDATE(), peminjaman.tanggal_kembali) AS telat
    FROM peminjaman
    JOIN alat ON peminjaman.alat_id = alat.id
    WHERE peminjaman.user_id='$_SESSION[id]'
    AND peminjaman.status IN ('disetujui','menunggu_pengembalian')
    ORDER BY peminjaman.id DESC
");

// =======================
// TOTAL DENDA USER
// =======================
$total_denda = 0;
mysqli_data_seek($pinjamanAktifList, 0);
while ($d = mysqli_fetch_assoc($pinjamanAktifList)) {
    if ($d['telat'] > 0) {
        $total_denda += $d['telat'] * $d['denda_per_hari'];
    }
}
mysqli_data_seek($pinjamanAktifList, 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex">
<?php include 'layout/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8 fade-in">

    <p class="mb-6 text-gray-600">Halo, <b><?= $_SESSION['nama']; ?></b></p>

    <!-- STAT CARD -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded shadow">
            <p class="text-gray-500">Peminjaman Aktif</p>
            <h2 class="text-3xl font-bold text-blue-600"><?= $aktif ?></h2>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <p class="text-gray-500">Total Riwayat</p>
            <h2 class="text-3xl font-bold text-green-600"><?= $riwayat ?></h2>
        </div>

        <div class="bg-white p-6 rounded shadow border-l-4 border-red-500">
            <p class="text-gray-500">Total Denda</p>
            <h2 class="text-3xl font-bold text-red-600">
                Rp <?= number_format($total_denda,0,',','.') ?>
            </h2>
        </div>
    </div>

    <!-- PINJAMAN AKTIF -->
    <div class="bg-white p-6 rounded shadow mt-6">
        
        <h2 class="font-bold mb-4">Pinjaman Aktif & Denda</h2>

        <?php if(mysqli_num_rows($pinjamanAktifList)==0): ?>
            <p class="text-gray-500 text-sm">Tidak ada pinjaman aktif</p>
        <?php else: ?>
        <div class="overflow-y-auto max-h-[50vh]">
            <table class="w-full border">
                <tr class="bg-gray-200">
                    <th class="border p-2">Alat</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Telat</th>
                    <th class="border p-2">Denda</th>
                </tr>

                <?php while($p=mysqli_fetch_assoc($pinjamanAktifList)): ?>
                <?php
                    $telat = $p['telat'] > 0 ? $p['telat'] : 0;
                    $denda = $telat * $p['denda_per_hari'];
                ?>
                <tr>
                    <td class="border p-2"><?= $p['nama_alat'] ?></td>
                    <td class="border p-2 text-center"><?= $p['status'] ?></td>
                    <td class="border p-2 text-center">
                        <?= $telat > 0 ? $telat.' hari' : '-' ?>
                    </td>
                    <td class="border p-2 text-center">
                        <?php if($denda > 0): ?>
                            <span class="text-red-600 font-semibold">
                                Rp <?= number_format($denda,0,',','.') ?>
                            </span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <?php endif; ?>
    </div>

</main>
</body>
</html>
