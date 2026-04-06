<aside class="w-64 min-h-screen bg-gradient-to-b from-blue-700 via-blue-600 to-purple-800 text-white flex flex-col p-5 relative overflow-hidden shadow-2xl">
    
    <div class="absolute -top-10 -left-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 -right-10 w-24 h-24 bg-purple-400/20 rounded-full blur-2xl"></div>

    <div class="mb-8 relative z-10 px-2">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-white/20 backdrop-blur-md rounded-lg shadow-inner">
                <i class="fa-solid fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black tracking-tighter uppercase">BG Admin</h2>
                <p class="text-[10px] text-blue-200 font-medium tracking-widest uppercase opacity-80 leading-none"></p>
            </div>
        </div>
    </div>

    <div class="group bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 mb-8 flex items-center gap-4 transition-all duration-300 hover:bg-white/20 hover:scale-[1.02] cursor-default relative z-10">
        <div class="relative">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-blue-400 to-purple-400 flex items-center justify-center font-bold text-white shadow-lg group-hover:rotate-12 transition-transform duration-500">
                <?= strtoupper(substr($_SESSION['nama'] ?? 'A', 0, 1)); ?>
            </div>
            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-blue-600 rounded-full"></div>
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-bold truncate leading-tight"><?= $_SESSION['nama'] ?? 'Admin User'; ?></p>
        </div>
    </div>

    <nav class="flex-1 relative z-10">
        <ul class="space-y-1.5">
            <?php
            function navItem($href, $icon, $label, $active=false){
                $activeClass = $active
                    ? "bg-white text-blue-600 font-bold shadow-lg shadow-blue-900/20 scale-[1.02]"
                    : "text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1";
                
                echo "
                <li class='transition-all duration-300'>
                    <a href='$href' class='flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 group $activeClass'>
                        <i class='fa-solid fa-$icon w-5 text-center transition-transform duration-300 group-hover:scale-110'></i>
                        <span class='text-sm'>$label</span>
                        " . ($active ? "<div class='ml-auto w-1.5 h-1.5 bg-blue-600 rounded-full'></div>" : "") . "
                    </a>
                </li>";
            }

            $page = basename($_SERVER['PHP_SELF']);
            
            navItem('dashboard.php','chart-pie','Dashboard',$page=='dashboard.php');
            navItem('petugas.php','user-shield','Petugas',$page=='petugas.php');
            navItem('users.php','users','User',$page=='users.php');
            navItem('alat.php','toolbox','Alat',$page=='alat.php');
            navItem('kategori.php','tags','Kategori',$page=='kategori.php');
            ?>

            <div class="pt-4 pb-2 px-3">
                <hr class="border-white/10">
            </div>
 

            <?php 
            navItem('peminjaman.php','arrow-right-arrow-left','Peminjaman',$page=='peminjaman.php');
            navItem('pengembalian.php','rotate-left','Pengembalian',$page=='pengembalian.php');
            navItem('log_aktivitas.php','fingerprint','Log Aktivitas',$page=='log_aktivitas.php');
            ?>
        </ul>
    </nav>

    <div class="pt-5 border-t border-white/10 relative z-10">
        <a href="../auth/logout.php"
           class="group flex items-center justify-center gap-3 bg-red-500/10 hover:bg-red-500 border border-red-500/20 p-3 rounded-xl transition-all duration-300 text-red-400 hover:text-white font-bold text-sm shadow-sm hover:shadow-red-500/40">
            <i class="fa-solid fa-power-off group-hover:rotate-90 transition-transform duration-300"></i>
            Keluar
        </a>
    </div>

</aside>

<style>
    /* Custom Scrollbar untuk Sidebar jika menu sangat banyak */
    aside::-webkit-scrollbar {
        width: 4px;
    }
    aside::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
    }
</style>