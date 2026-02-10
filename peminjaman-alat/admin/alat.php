<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

// =======================
// TAMBAH ALAT
// =======================
if (isset($_POST['tambah'])) {
    $nama = htmlspecialchars($_POST['nama_alat']);
    $kategori = $_POST['kategori_id'];
    $stok = $_POST['stok'];
    $status = $_POST['status'];

    mysqli_query($conn, "
        INSERT INTO alat (nama_alat, kategori_id, stok, status)
        VALUES ('$nama','$kategori','$stok','$status')
    ");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menambah alat')
    ");

    echo "<script>
        alert('Alat berhasil ditambahkan');
        location='alat.php';
    </script>";
}

// =======================
// HAPUS ALAT
// =======================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM alat WHERE id='$id'");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menghapus alat')
    ");

    echo "<script>
        alert('Alat berhasil dihapus');
        location='alat.php';
    </script>";
}

// =======================
// DATA KATEGORI
// =======================
$kategori = mysqli_query($conn, "SELECT * FROM kategori");

// =======================
// DATA ALAT
// =======================
$data = mysqli_query($conn, "
    SELECT alat.*, kategori.nama_kategori
    FROM alat
    JOIN kategori ON alat.kategori_id = kategori.id
    ORDER BY alat.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<h1 class="text-2xl font-bold mb-4">CRUD Alat</h1>

<!-- ================= FORM TAMBAH ================= -->
<div class="bg-white p-6 rounded shadow mb-6">
    <h2 class="font-semibold mb-4">Tambah Alat</h2>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <input type="text" name="nama_alat" placeholder="Nama alat" required class="border p-2 rounded">

        <select name="kategori_id" required class="border p-2 rounded">
            <option value="">-- Pilih Kategori --</option>
            <?php while($k = mysqli_fetch_assoc($kategori)): ?>
                <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
            <?php endwhile; ?>
        </select>

        <input type="number" name="stok" placeholder="Stok" min="1" required class="border p-2 rounded">

        <select name="status" class="border p-2 rounded">
            <option value="tersedia">Tersedia</option>
            <option value="rusak">Rusak</option>
        </select>

        <button name="tambah"
            class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700 transition">
            Tambah
        </button>
    </form>
</div>

<!-- ================= TABEL ================= -->
<div class="bg-white p-6 rounded shadow">
    <table class="w-full border">
        <tr class="bg-gray-200">
            <th class="border p-2">No</th>
            <th class="border p-2">Nama Alat</th>
            <th class="border p-2">Kategori</th>
            <th class="border p-2">Stok</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Aksi</th>
        </tr>

        <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
        <tr>
            <td class="border p-2 text-center"><?= $no++ ?></td>
            <td class="border p-2"><?= $row['nama_alat'] ?></td>
            <td class="border p-2"><?= $row['nama_kategori'] ?></td>
            <td class="border p-2 text-center"><?= $row['stok'] ?></td>
            <td class="border p-2 text-center">
                <?php if ($row['status'] == 'tersedia'): ?>
                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                        Tersedia
                    </span>
                <?php elseif ($row['status'] == 'dipinjam'): ?>
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                        Dipinjam
                    </span>
                <?php else: ?>
                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                        Rusak
                    </span>
                <?php endif; ?>
            </td>
            <td class="border p-2 text-center">
                <a href="?hapus=<?= $row['id'] ?>"
                   onclick="return confirm('Yakin hapus alat ini?')"
                   class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                    Hapus
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<a href="dashboard.php" class="inline-block mt-6 text-blue-600">← Kembali ke Dashboard</a>

</body>
</html>
