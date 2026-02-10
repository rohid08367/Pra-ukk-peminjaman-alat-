<?php
require '../config/database.php';
require '../middleware/auth_check.php';
cekRole('admin');

// =======================
// DATA LOG AKTIVITAS
// =======================
$data = mysqli_query($conn, "
    SELECT log_aktivitas.*, users.nama, users.role
    FROM log_aktivitas
    JOIN users ON log_aktivitas.user_id = users.id
    ORDER BY log_aktivitas.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

<h1 class="text-2xl font-bold mb-4">Log Aktivitas Sistem</h1>

<div class="bg-white p-6 rounded shadow overflow-x-auto">
    <table class="w-full border">
        <tr class="bg-gray-200">
            <th class="border p-2">No</th>
            <th class="border p-2">Nama User</th>
            <th class="border p-2">Role</th>
            <th class="border p-2">Aktivitas</th>
            <th class="border p-2">Waktu</th>
        </tr>

        <?php $no=1; while($row = mysqli_fetch_assoc($data)): ?>
        <tr>
            <td class="border p-2 text-center"><?= $no++; ?></td>
            <td class="border p-2"><?= htmlspecialchars($row['nama']); ?></td>
            <td class="border p-2 text-center">
                <?php if ($row['role'] == 'admin'): ?>
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm">Admin</span>
                <?php elseif ($row['role'] == 'petugas'): ?>
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">Petugas</span>
                <?php else: ?>
                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm">User</span>
                <?php endif; ?>
            </td>
            <td class="border p-2"><?= htmlspecialchars($row['aktivitas']); ?></td>
            <td class="border p-2 text-center"><?= $row['created_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<a href="dashboard.php" class="inline-block mt-6 text-blue-600">
    ← Kembali ke Dashboard
</a>

</body>
</html>
