<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

// =======================
// TAMBAH PETUGAS
// =======================
if (isset($_POST['tambah'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn, "
        INSERT INTO users (nama, email, password, role)
        VALUES ('$nama','$email','$password','petugas')
    ");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menambah petugas')
    ");

    echo "<script>
        alert('Petugas berhasil ditambahkan');
        location='petugas.php';
    </script>";
}

// =======================
// HAPUS PETUGAS
// =======================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND role='petugas'");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menghapus petugas')
    ");

    echo "<script>
        alert('Petugas berhasil dihapus');
        location='petugas.php';
    </script>";
}

// =======================
// DATA PETUGAS
// =======================
$data = mysqli_query($conn, "SELECT * FROM users WHERE role='petugas'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Petugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

<h1 class="text-2xl font-bold mb-4">CRUD Petugas</h1>

<!-- ================= FORM TAMBAH ================= -->
<div class="bg-white p-6 rounded shadow mb-6">
    <h2 class="font-semibold mb-3">Tambah Petugas</h2>
    <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" name="nama" placeholder="Nama" required class="border p-2 rounded">
        <input type="email" name="email" placeholder="Email" required class="border p-2 rounded">
        <input type="password" name="password" placeholder="Password" required class="border p-2 rounded">
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
            <th class="border p-2">Nama</th>
            <th class="border p-2">Email</th>
            <th class="border p-2">Aksi</th>
        </tr>

        <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
        <tr>
            <td class="border p-2"><?= $no++ ?></td>
            <td class="border p-2"><?= $row['nama'] ?></td>
            <td class="border p-2"><?= $row['email'] ?></td>
            <td class="border p-2 text-center">
                <a href="?hapus=<?= $row['id'] ?>"
                   onclick="return confirm('Hapus petugas ini?')"
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
