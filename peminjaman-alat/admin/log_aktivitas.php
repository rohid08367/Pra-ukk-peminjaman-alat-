<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

$data = mysqli_query($conn, "
    SELECT log_aktivitas.*, users.nama, users.role
    FROM log_aktivitas
    JOIN users ON log_aktivitas.user_id = users.id
    ORDER BY log_aktivitas.id DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Log Aktivitas</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen flex overflow-hidden">

<!-- SIDEBAR -->
<?php include 'layout/sidebar.php'; ?>

<!-- MAIN -->
<main class="flex-1 p-8 overflow-y-auto">



<!-- CARD -->
<div class="bg-white p-6 rounded-xl shadow">

    <div class="overflow-y-auto max-h-[70vh]">
        <table class="w-full border">
            <thead class="bg-gray-200 sticky top-0 z-10">
                <tr>
                    <th class="border p-2 text-center w-12">No</th>
                    <th class="border p-2 text-left">Nama User</th>
                    <th class="border p-2 text-center w-32">Role</th>
                    <th class="border p-2 text-left">Aktivitas</th>
                    <th class="border p-2 text-center w-48">Waktu</th>
                </tr>
            </thead>

            <tbody>
            <?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 text-center"><?= $no++ ?></td>
                    <td class="border p-2"><?= $row['nama'] ?></td>

                    <td class="border p-2 text-center">
                        <?php if($row['role']=='admin'): ?>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Admin
                            </span>
                        <?php elseif($row['role']=='petugas'): ?>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Petugas
                            </span>
                        <?php else: ?>
                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                User
                            </span>
                        <?php endif; ?>
                    </td>

                    <td class="border p-2"><?= $row['aktivitas'] ?></td>

                    <td class="border p-2 text-center">
                        <?= date('Y-m-d H:i:s', strtotime($row['created_at'] ?? $row['waktu'] ?? $row['tanggal'] ?? $row['id'])) ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</main>
</body>
</html>
