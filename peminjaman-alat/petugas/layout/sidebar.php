<aside class="w-64 min-h-screen bg-gradient-to-b from-indigo-700 via-slate-800 to-slate-900 text-white flex flex-col p-5 relative overflow-hidden shadow-2xl">
    
    <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-slate-500/10 rounded-full blur-2xl -ml-16 -mb-16"></div>

    <div class="mb-10 relative z-10">
        <div class="flex items-center gap-3 px-2">
            <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 rotate-3 group-hover:rotate-0 transition-transform">
                <i class="fa-solid fa-clipboard-check text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-extrabold tracking-tight leading-none uppercase">Petugas</h2>
            </div>
        </div>
    </div>

    <div class="group bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 mb-8 flex items-center gap-4 transition-all duration-300 hover:bg-white/20 relative z-10">
        <div class="relative">
            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-400 to-slate-500 flex items-center justify-center font-bold text-white shadow-lg group-hover:scale-110 transition-transform duration-500">
                <?= strtoupper(substr($_SESSION['nama'] ?? 'P', 0, 1)); ?>
            </div>
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-slate-800 rounded-full"></span>
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-bold truncate leading-tight"><?= $_SESSION['nama'] ?? 'Staff Petugas'; ?></p>
        </div>
    </div>

    <nav class="flex-1 relative z-10 px-1"> 
        <ul class="space-y-1.5">
            <?php
            function navItem($href, $icon, $label, $active=false){
                // Gaya tombol saat aktif (bg putih) vs tidak aktif (transparan)
                $activeClass = $active
                    ? "bg-white text-indigo-700 font-bold shadow-lg shadow-black/20"
                    : "text-white/70 hover:bg-white/5 hover:text-white";
                
                $iconColor = $active ? "text-indigo-700" : "text-indigo-400";

                echo "
                <li>
                    <a href='$href' class='flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group $activeClass'>
                        <div class='w-8 flex justify-center'>
                            <i class='fa-solid fa-$icon transition-transform group-hover:scale-125 $iconColor'></i>
                        </div>
                        <span class='text-sm font-medium'>$label</span>
                        
                        " . ($active ? "<div class='ml-auto w-2 h-2 bg-indigo-600 rounded-full'></div>" : "") . "
                    </a>
                </li>";
            }
            $page = basename($_SERVER['PHP_SELF']);
            
            navItem('dashboard.php', 'chart-pie', 'Dashboard', $page=='dashboard.php');
            navItem('approve_peminjaman.php', 'circle-check', 'Setujui Pinjam', $page=='approve_peminjaman.php');
            navItem('pengembalian.php', 'rotate-left', 'Pengembalian', $page=='pengembalian.php');
            navItem('laporan.php', 'file-export', 'Laporan', $page=='laporan.php');
            ?>
        </ul>
    </nav>

    <div class="relative z-10 pt-4 border-t border-white/10">
        <a href="../auth/logout.php"
           class="flex items-center justify-center gap-3 w-full bg-white/5 hover:bg-rose-500 border border-white/10 hover:border-rose-500 p-3 rounded-xl transition-all duration-300 text-white font-bold text-sm shadow-sm group">
            <i class="fa-solid fa-right-from-bracket group-hover:-translate-x-1 transition-transform"></i>
            Keluar
        </a>
    </div>

</aside>

<style>
    aside {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-family: 'Inter', sans-serif;
    }
    
    nav li:hover {
        transform: translateX(4px);
        transition: transform 0.3s ease;
    }
</style>