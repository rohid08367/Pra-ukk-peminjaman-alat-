<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('user');

// =======================
// AJUKAN PEMINJAMAN
// =======================
if (isset($_POST['pinjam'])) {
    $alat_id = $_POST['alat_id'];
    $jumlah  = (int) $_POST['jumlah'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $hari_ini = date('Y-m-d');

    // VALIDASI TANGGAL
    if ($tanggal_kembali < $hari_ini) {
        echo "<script>
            alert('Tanggal kembali tidak boleh kurang dari hari ini');
            location='pinjam.php';
        </script>";
        exit;
    }

    // AMBIL DATA ALAT
    $alat = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM alat WHERE id='$alat_id'
    "));

    if (!$alat) {
        echo "<script>
            alert('Alat tidak ditemukan');
            location='pinjam.php';
        </script>";
        exit;
    }

    // VALIDASI JUMLAH
    if ($jumlah <= 0) {
        echo "<script>
            alert('Jumlah pinjam tidak valid');
            location='pinjam.php';
        </script>";
        exit;
    }

    // VALIDASI STOK
    if ($jumlah > $alat['stok']) {
        echo "<script>
            alert('Jumlah melebihi stok tersedia');
            location='pinjam.php';
        </script>";
        exit;
    }

    // SIMPAN PEMINJAMAN (STATUS DEFAULT = PENDING)
    mysqli_query($conn, "
        INSERT INTO peminjaman 
        (user_id, alat_id, jumlah, tanggal_pinjam, tanggal_kembali, status)
        VALUES 
        ('$_SESSION[id]', '$alat_id', '$jumlah', CURDATE(), '$tanggal_kembali', 'pending')
    ");

    // LOG AKTIVITAS
    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Mengajukan peminjaman alat')
    ");

    echo "<script>
        alert('Peminjaman berhasil diajukan, menunggu persetujuan petugas');
        location='pinjam.php';
    </script>";
    exit;
}

// =======================
// DATA ALAT TERSEDIA
// =======================
$alat = mysqli_query($conn, "
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
</head>

<body class="bg-gray-100 p-8">

<h1 class="text-2xl font-bold mb-4">Ajukan Peminjaman</h1>

<div class="bg-white p-6 rounded shadow max-w-xl">
<form method="POST">

    <label class="block mb-2">Pilih Alat</label>
    <select name="alat_id" required class="border p-2 w-full mb-4">
        <option value="">-- Pilih --</option>
        <?php while($a = mysqli_fetch_assoc($alat)): ?>
            <option value="<?= $a['id']; ?>">
                <?= htmlspecialchars($a['nama_alat']); ?> (Stok: <?= $a['stok']; ?>)
            </option>
        <?php endwhile; ?>
    </select>

    <label class="block mb-2">Jumlah Pinjam</label>
    <input 
        type="number"
        name="jumlah"
        min="1"
        required
        class="border p-2 w-full mb-4">

    <label class="block mb-2">Tanggal Kembali</label>
    <input 
        type="date"
        name="tanggal_kembali"
        required
        min="<?= date('Y-m-d'); ?>"
        class="border p-2 w-full mb-4">

    <button 
        type="submit"
        name="pinjam"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        Ajukan
    </button>

</form>
</div>

<a href="dashboard.php" class="inline-block mt-6 text-blue-600">
    ← Kembali ke Dashboard
</a>

</body>
</html>
