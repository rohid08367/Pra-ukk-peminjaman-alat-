<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

/* ===== PROSES CRUD (TETAP) ===== */
if (isset($_POST['tambah'])) {
    $nama = htmlspecialchars($_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    header("Location: kategori.php");
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM kategori WHERE id='$_GET[hapus]'");
    header("Location: kategori.php");
}

$data = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kategori</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>


<!-- MAIN -->
<main class="flex-1 p-8 overflow-y-auto">


    <div class="bg-white p-6 rounded shadow mb-6 max-w-xl">

        <h2 class="font-semibold mb-3">Tambah Kategori</h2>
        <form method="POST" class="flex gap-3">
            <input type="text" name="nama_kategori" required
                   class="border p-2 rounded w-full"
                   placeholder="Nama kategori">
            <button name="tambah"
                class="bg-purple-600 text-white px-5 rounded hover:bg-purple-700">
                Tambah
            </button>
        </form>
    </div>

    <div class="bg-white p-6 rounded shadow max-w-xl">
        <div class="overflow-y-auto max-h-[55vh]">
            <table class="w-full border">
                <tr class="bg-gray-200">
                    <th class="border p-2 w-16">No</th>
                    <th class="border p-2">Kategori</th>
                    <th class="border p-2 w-32">Aksi</th>
                </tr>

                <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td class="border p-2 text-center"><?= $no++ ?></td>
                    <td class="border p-2"><?= $row['nama_kategori'] ?></td>
                    <td class="border p-2 text-center">
                        <a href="?hapus=<?= $row['id'] ?>"
                           onclick="return confirm('Hapus kategori?')"
                           class="bg-red-500 text-white px-3 py-1 rounded">
                           Hapus
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
