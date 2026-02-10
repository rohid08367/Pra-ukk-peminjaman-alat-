<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

// =======================
// TAMBAH KATEGORI
// =======================
if (isset($_POST['tambah'])) {
    $nama = htmlspecialchars($_POST['nama_kategori']);

    mysqli_query($conn, "
        INSERT INTO kategori (nama_kategori)
        VALUES ('$nama')
    ");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menambah kategori alat')
    ");

    echo "<script>
        alert('Kategori berhasil ditambahkan');
        location='kategori.php';
    </script>";
}

// =======================
// HAPUS KATEGORI
// =======================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM kategori WHERE id='$id'");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menghapus kategori alat')
    ");

    echo "<script>
        alert('Kategori berhasil dihapus');
        location='kategori.php';
    </script>";
}

// =======================
// DATA KATEGORI
// =======================
$data = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
<h1 class="text-2xl font-bold mb-4">CRUD Kategori Alat</h1>

<!-- ================= FORM TAMBAH ================= -->
<div class="bg-white p-6 rounded shadow mb-6 max-w-xl">
    <h2 class="font-semibold mb-3">Tambah Kategori</h2>
    <form method="POST" class="flex gap-3">
        <input type="text"
               name="nama_kategori"
               placeholder="Nama kategori (contoh: Olahraga)"
               required
               class="border p-2 rounded w-full">

        <button name="tambah"
            class="bg-purple-600 text-white px-5 rounded hover:bg-purple-700 transition">
            Tambah
        </button>
    </form>
</div>

<!-- ================= TABEL ================= -->
<div class="bg-white p-6 rounded shadow max-w-xl">
    <table class="w-full border">
        <tr class="bg-gray-200">
            <th class="border p-2 w-16">No</th>
            <th class="border p-2">Nama Kategori</th>
            <th class="border p-2 w-32">Aksi</th>
        </tr>

        <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
        <tr>
            <td class="border p-2 text-center"><?= $no++ ?></td>
            <td class="border p-2"><?= $row['nama_kategori'] ?></td>
            <td class="border p-2 text-center">
                <a href="?hapus=<?= $row['id'] ?>"
                   onclick="return confirm('Yakin hapus kategori ini?')"
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
