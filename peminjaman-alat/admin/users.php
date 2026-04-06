<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

// =======================
// TAMBAH USER
// =======================
if (isset($_POST['tambah'])) {

    $nama     = htmlspecialchars($_POST['nama']);
    $email    = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // CEK EMAIL SUDAH ADA ATAU BELUM
    $cek = mysqli_query($conn,"
        SELECT id FROM users 
        WHERE email='$email'
        LIMIT 1
    ");

    if(mysqli_num_rows($cek) > 0){

        echo "<script>
        alert('Email sudah digunakan!');
        location='users.php';
        </script>";

        exit;
    }

    // INSERT USER BARU
    mysqli_query($conn,"
        INSERT INTO users (nama,email,password,role)
        VALUES ('$nama','$email','$password','user')
    ");

    mysqli_query($conn,"
        INSERT INTO log_aktivitas (user_id,aktivitas)
        VALUES ('$_SESSION[id]','Menambah user')
    ");

    header("Location: users.php");
    exit;
}

// =======================
// HAPUS USER
// =======================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "
        DELETE FROM users 
        WHERE id='$id' AND role='user'
    ");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menghapus user')
    ");

    header("Location: users.php");
    exit;
}

// =======================
// DATA USER
// =======================
$data = mysqli_query($conn, "
    SELECT * FROM users 
    WHERE role='user'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data User</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8">


<!-- ================= FORM TAMBAH ================= -->
<div class="bg-white p-6 rounded-xl shadow mb-6 max-w-3xl">
    <h2 class="font-semibold mb-4">Tambah User</h2>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="nama"
               placeholder="Nama User"
               required class="border p-2 rounded">

        <input type="email" name="email"
               placeholder="Email"
               required class="border p-2 rounded">

        <input type="password" name="password"
               placeholder="Password"
               required class="border p-2 rounded">

        <button name="tambah"
                class="bg-green-600 text-white rounded px-4 py-2 hover:bg-green-700 transition">
            Tambah
        </button>
    </form>
</div>

<!-- ================= TABEL ================= -->
<div class="bg-white p-6 rounded-xl shadow max-w-3xl">
<table class="w-full border">
    <tr class="bg-gray-200">
        <th class="border p-2 w-12">No</th>
        <th class="border p-2">Nama</th>
        <th class="border p-2">Email</th>
        <th class="border p-2 w-32">Aksi</th>
    </tr>

    <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
    <tr>
        <td class="border p-2 text-center"><?= $no++ ?></td>
        <td class="border p-2"><?= $row['nama'] ?></td>
        <td class="border p-2"><?= $row['email'] ?></td>
        <td class="border p-2 text-center">
            <a href="?hapus=<?= $row['id'] ?>"
               onclick="return confirm('Yakin hapus user ini?')"
               class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
               Hapus
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</div>

</main>
</body>
</html>
