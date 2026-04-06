<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('user');

/* =======================
   AJUKAN PEMINJAMAN
======================= */
if (isset($_POST['pinjam'])) {

    $alat_id = $_POST['alat_id'];
    $jumlah  = (int) $_POST['jumlah'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $hari_ini = date('Y-m-d');

    if ($tanggal_kembali < $hari_ini) {
        echo "<script>
        alert('Tanggal kembali tidak boleh kurang dari hari ini');
        location='pinjam.php';
        </script>";
        exit;
    }

    $alat = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT * FROM alat WHERE id='$alat_id'
    "));

    if (!$alat) {
        echo "<script>
        alert('Alat tidak ditemukan');
        location='pinjam.php';
        </script>";
        exit;
    }

    if ($jumlah <= 0) {
        echo "<script>
        alert('Jumlah pinjam tidak valid');
        location='pinjam.php';
        </script>";
        exit;
    }

    if ($jumlah > $alat['stok']) {
        echo "<script>
        alert('Jumlah melebihi stok tersedia');
        location='pinjam.php';
        </script>";
        exit;
    }

    mysqli_query($conn,"
    INSERT INTO peminjaman 
    (user_id, alat_id, jumlah, tanggal_pinjam, jam_pinjam, tanggal_kembali, status)
    VALUES 
    ('$_SESSION[id]', '$alat_id', '$jumlah', CURDATE(), CURTIME(), '$tanggal_kembali', 'pending')
    ");

    mysqli_query($conn,"
        INSERT INTO log_aktivitas (user_id,aktivitas)
        VALUES ('$_SESSION[id]','Mengajukan peminjaman alat')
    ");

    echo "<script>
    alert('Peminjaman berhasil diajukan, menunggu persetujuan petugas');
    location='pinjam.php';
    </script>";

    exit;
}

/* =======================
   DATA ALAT TERSEDIA
======================= */
$alat = mysqli_query($conn,"
SELECT * FROM alat
WHERE status='tersedia' AND stok > 0
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<title>Pinjam Alat</title>

<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>


<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>


<!-- MAIN -->
<main class="flex-1 p-8 overflow-y-auto">



<form method="POST">


<!-- LIST ALAT -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-6">

<?php while($a = mysqli_fetch_assoc($alat)): ?>

<label class="cursor-pointer">

<input type="radio"
name="alat_id"
value="<?= $a['id'] ?>"
required
class="hidden peer">

<div class="bg-white border rounded-xl shadow p-4
peer-checked:ring-2 peer-checked:ring-blue-500
hover:shadow-lg transition">

<?php if($a['gambar']!=''): ?>

<img src="../assets/img_alat/<?= $a['gambar'] ?>"
class="w-full h-32 object-cover rounded mb-3">

<?php else: ?>

<div class="w-full h-32 bg-gray-200 flex items-center justify-center rounded mb-3 text-gray-500">
No Image
</div>

<?php endif; ?>


<h3 class="font-semibold text-gray-800">
<?= htmlspecialchars($a['nama_alat']) ?>
</h3>

<p class="text-sm text-gray-500">
Stok : <?= $a['stok'] ?>
</p>

<p class="text-sm text-red-500">
Denda / hari : Rp <?= number_format($a['denda_per_hari'],0,',','.') ?>
</p>

</div>

</label>

<?php endwhile; ?>

</div>


<!-- FORM JUMLAH -->
<div class="bg-white p-6 rounded-xl shadow max-w-xl mb-4">

<div class="mb-4">

<label class="block mb-1 font-medium text-gray-700">
Jumlah Pinjam
</label>

<input type="number"
name="jumlah"
min="1"
required
class="border rounded-lg p-2 w-full focus:ring focus:ring-blue-200">

</div>


<div class="mb-4">

<label class="block mb-1 font-medium text-gray-700">
Tanggal Kembali
</label>

<input type="date"
name="tanggal_kembali"
required
min="<?= date('Y-m-d') ?>"
class="border rounded-lg p-2 w-full focus:ring focus:ring-blue-200">

</div>


<button
type="submit"
name="pinjam"
class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">

Ajukan Peminjaman

</button>

</div>


</form>


</main>
</body>
</html>