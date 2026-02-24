<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

/* =======================
   TAMBAH ALAT
======================= */
if (isset($_POST['tambah'])) {
    $nama     = htmlspecialchars($_POST['nama_alat']);
    $kategori = $_POST['kategori_id'];
    $stok     = $_POST['stok'];
    $status   = $_POST['status'];
    $denda    = (int) $_POST['denda_per_hari'];

    mysqli_query($conn, "
        INSERT INTO alat (nama_alat, kategori_id, stok, status, denda_per_hari)
        VALUES ('$nama','$kategori','$stok','$status','$denda')
    ");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menambah alat')
    ");

    header("Location: alat.php");
    exit;
}

/* =======================
   UPDATE ALAT
======================= */
if (isset($_POST['update'])) {
    $id       = $_POST['id'];
    $nama     = htmlspecialchars($_POST['nama_alat']);
    $kategori = $_POST['kategori_id'];
    $stok     = $_POST['stok'];
    $status   = $_POST['status'];
    $denda    = (int) $_POST['denda_per_hari'];

    mysqli_query($conn, "
        UPDATE alat SET
            nama_alat='$nama',
            kategori_id='$kategori',
            stok='$stok',
            status='$status',
            denda_per_hari='$denda'
        WHERE id='$id'
    ");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Mengedit alat')
    ");

    header("Location: alat.php");
    exit;
}

/* =======================
   HAPUS ALAT
======================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM alat WHERE id='$id'");

    mysqli_query($conn, "
        INSERT INTO log_aktivitas (user_id, aktivitas)
        VALUES ('$_SESSION[id]', 'Menghapus alat')
    ");

    header("Location: alat.php");
    exit;
}

/* =======================
   DATA
======================= */
$kategori = mysqli_query($conn, "SELECT * FROM kategori");

$data = mysqli_query($conn, "
    SELECT alat.*, kategori.nama_kategori
    FROM alat
    JOIN kategori ON alat.kategori_id = kategori.id
    ORDER BY alat.id DESC
");

/* =======================
   DATA EDIT
======================= */
$edit = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM alat WHERE id='$id'
    "));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Alat</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8 overflow-y-auto">

<h1 class="text-2xl font-bold mb-6">Data Alat</h1>

<!-- ================= FORM ================= -->
<div class="bg-white p-6 rounded-xl shadow mb-6">
    <h2 class="font-semibold mb-4">
        <?= $edit ? 'Edit Alat' : 'Tambah Alat' ?>
    </h2>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-4">

        <?php if ($edit): ?>
            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <?php endif; ?>

        <input type="text" name="nama_alat" placeholder="Nama alat"
               value="<?= $edit['nama_alat'] ?? '' ?>"
               required class="border p-2 rounded">

        <select name="kategori_id" required class="border p-2 rounded">
            <option value="">-- Pilih Kategori --</option>
            <?php
            mysqli_data_seek($kategori, 0);
            while($k=mysqli_fetch_assoc($kategori)):
            ?>
                <option value="<?= $k['id'] ?>"
                    <?= ($edit && $edit['kategori_id']==$k['id'])?'selected':'' ?>>
                    <?= $k['nama_kategori'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input type="number" name="stok" min="1" placeholder="Stok"
               value="<?= $edit['stok'] ?? '' ?>"
               required class="border p-2 rounded">

        <input type="number" name="denda_per_hari" min="0"
               placeholder="Denda / hari (Rp)"
               value="<?= $edit['denda_per_hari'] ?? '' ?>"
               required class="border p-2 rounded">

        <select name="status" class="border p-2 rounded">
            <option value="tersedia" <?= ($edit && $edit['status']=='tersedia')?'selected':'' ?>>Tersedia</option>
            <option value="rusak" <?= ($edit && $edit['status']=='rusak')?'selected':'' ?>>Rusak</option>
        </select>

        <button name="<?= $edit ? 'update' : 'tambah' ?>"
                class="<?= $edit ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-blue-600 hover:bg-blue-700' ?>
                       text-white rounded px-4 py-2">
            <?= $edit ? 'Update' : 'Tambah' ?>
        </button>
    </form>
</div>

<!-- ================= TABEL ================= -->
<div class="bg-white p-6 rounded-xl shadow">
    <div class="overflow-y-auto max-h-[55vh]">

        <table class="w-full border">
        <thead class="bg-gray-200 sticky top-0 z-10">
            <tr class="bg-gray-100">
                <th class="border p-2">No</th>
                <th class="border p-2">Nama Alat</th>
                <th class="border p-2">Kategori</th>
                <th class="border p-2">Stok</th>
                <th class="border p-2">Denda / Hari</th>
                <th class="border p-2">Status</th>
                <th class="border p-2">Aksi</th>
            </tr>
        </thead>      
        <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
        <tr>
            <td class="border p-2 text-center"><?= $no++ ?></td>
            <td class="border p-2"><?= $row['nama_alat'] ?></td>
            <td class="border p-2"><?= $row['nama_kategori'] ?></td>
            <td class="border p-2 text-center"><?= $row['stok'] ?></td>
            <td class="border p-2 text-center">
                Rp <?= number_format($row['denda_per_hari'],0,',','.') ?>
            </td>
            <td class="border p-2 text-center">
                <?= $row['status']=='tersedia'
                    ? '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Tersedia</span>'
                    : '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Rusak</span>' ?>
            </td>
            <td class="border p-2 text-center space-x-1">
                <a href="?edit=<?= $row['id'] ?>"
                   class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                   Edit
                </a>
                <a href="?hapus=<?= $row['id'] ?>"
                   onclick="return confirm('Hapus alat ini?')"
                   class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
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
