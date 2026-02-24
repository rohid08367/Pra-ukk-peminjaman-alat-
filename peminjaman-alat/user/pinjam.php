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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- KONTEN UTAMA -->
<div class="flex-1 p-8">

    <!-- HEADER -->
    <h1 class="text-2xl font-bold mb-6 text-gray-800">
        Ajukan Peminjaman
    </h1>

    <!-- FORM CARD -->
    <div class="bg-white p-6 rounded-xl shadow max-w-xl">

        <form method="POST" class="space-y-4">

            <div>
                <label class="block mb-1 font-medium text-gray-700">
                    Pilih Alat
                </label>
                <select 
                    name="alat_id" 
                    required 
                    class="border rounded-lg p-2 w-full focus:ring focus:ring-blue-200">
                    <option value="">-- Pilih --</option>
                    <?php while($a = mysqli_fetch_assoc($alat)): ?>
                        <option value="<?= $a['id']; ?>">
                            <?= htmlspecialchars($a['nama_alat']); ?> (Stok: <?= $a['stok']; ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">
                    Jumlah Pinjam
                </label>
                <input 
                    type="number"
                    name="jumlah"
                    min="1"
                    required
                    class="border rounded-lg p-2 w-full focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-700">
                    Tanggal Kembali
                </label>
                <input 
                    type="date"
                    name="tanggal_kembali"
                    required
                    min="<?= date('Y-m-d'); ?>"
                    class="border rounded-lg p-2 w-full focus:ring focus:ring-blue-200">
            </div>

            <div class="pt-2">
                <button 
                    type="submit"
                    name="pinjam"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Ajukan Peminjaman
                </button>
            </div>

        </form>
    </div>

</div>


</body>
</html>