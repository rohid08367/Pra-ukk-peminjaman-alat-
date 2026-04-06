<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('user');

$data = mysqli_query($conn, "
    SELECT peminjaman.*, alat.nama_alat
    FROM peminjaman
    JOIN alat ON peminjaman.alat_id = alat.id
    WHERE peminjaman.user_id='$_SESSION[id]'
    ORDER BY peminjaman.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Peminjaman</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8 overflow-y-auto">



<div class="bg-white p-6 rounded-xl shadow">

<div class="overflow-x-auto">
<div class="overflow-y-auto max-h-[70vh]">
    <table class="w-full border">

        <tr class="bg-gray-200">
            <th class="border p-2">Alat</th>
            <th class="border p-2">Tanggal Pinjam</th>
            <th class="border p-2">Tanggal Kembali</th>
            <th class="border p-2 text-center">Status</th>
            <th class="border p-2 text-center">Denda</th>
        </tr>

        <?php while($row=mysqli_fetch_assoc($data)): ?>
        <tr class="hover:bg-gray-50">
            <td class="border p-2"><?= htmlspecialchars($row['nama_alat']) ?></td>
            <td class="border p-2"><?= $row['tanggal_pinjam'] ?></td>
            <td class="border p-2"><?= $row['tanggal_kembali'] ?></td>
        
            <td class="border p-2 text-center">
                <?php if($row['status']=='dikembalikan'): ?>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                        Dikembalikan
                    </span>
                <?php elseif($row['status']=='disetujui'): ?>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                        Dipinjam
                    </span>
                <?php elseif($row['status']=='menunggu_pengembalian'): ?>
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                        Menunggu Petugas
                    </span>
                <?php else: ?>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                        <?= ucfirst($row['status']) ?>
                    </span>
                <?php endif; ?>
            </td>
                
            <td class="border p-2 text-center">
                <?php if ($row['denda'] > 0): ?>
                    <span class="text-red-600 font-semibold">
                        Rp <?= number_format($row['denda'],0,',','.') ?>
                    </span>
                <?php else: ?>
                    <span class="text-green-600">-</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>       
    </table>
</div>
</div>

</div>

</main>
</body>
</html>
