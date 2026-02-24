<?php if (!isset($_SESSION)) session_start(); ?>

<aside class="w-64 bg-gradient-to-b from-blue-600 to-purple-700 text-white min-h-screen p-5 flex flex-col">

    <!-- BRAND -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold tracking-wide">Admin Panel</h2>
        <p class="text-xs text-white/70 mt-1">Manajemen Peminjaman</p>
    </div>

    <!-- PROFILE -->
    <div class="bg-white/10 rounded-lg p-3 mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-white/30 flex items-center justify-center font-bold">
            <?= strtoupper(substr($_SESSION['nama'],0,1)); ?>
        </div>
        <div>
            <p class="text-sm font-semibold"><?= $_SESSION['nama']; ?></p>
            <span class="text-xs text-white/70">Administrator</span>
        </div>
    </div>

    <!-- MENU -->
    <ul class="space-y-1 flex-1">
        <li><a href="dashboard.php" class="block p-2 rounded hover:bg-white/20">📊 Dashboard</a></li>
        <li><a href="petugas.php" class="block p-2 rounded hover:bg-white/20">👮 Petugas</a></li>
        <li><a href="users.php" class="block p-2 rounded hover:bg-white/20">👤 User</a></li>
        <li><a href="alat.php" class="block p-2 rounded hover:bg-white/20">🛠️ Alat</a></li>
        <li><a href="kategori.php" class="block p-2 rounded hover:bg-white/20">📂 Kategori</a></li>

        <hr class="border-white/20 my-3">

        <li><a href="peminjaman.php" class="block p-2 rounded hover:bg-white/20">📄 Peminjaman</a></li>
        <li><a href="pengembalian.php" class="block p-2 rounded hover:bg-white/20">↩️ Pengembalian</a></li>
        <li><a href="laporan.php" class="block p-2 rounded hover:bg-white/20">🖨️ Laporan</a></li>
        <li><a href="log_aktivitas.php" class="block p-2 rounded hover:bg-white/20">🧾 Log Aktivitas</a></li>
    </ul>

    <!-- LOGOUT -->
    <a href="../auth/logout.php"
       class="mt-4 bg-red-500 hover:bg-red-600 p-2 rounded text-center font-semibold">
        🚪 Logout
    </a>
</aside>
