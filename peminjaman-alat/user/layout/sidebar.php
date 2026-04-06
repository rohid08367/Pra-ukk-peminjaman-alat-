<aside class="w-64 min-h-screen bg-gradient-to-b from-emerald-600 via-teal-600 to-cyan-700 text-white flex flex-col p-5 relative overflow-hidden shadow-2xl">
    
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 -left-10 w-24 h-24 bg-emerald-400/20 rounded-full blur-2xl"></div>

    <div class="mb-8 relative z-10 px-2">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-white/20 backdrop-blur-lg rounded-xl shadow-inner border border-white/20">
                <i class="fa-solid fa-circle-user text-xl text-emerald-100"></i>
            </div>
            <div>
                <h2 class="text-xl font-black tracking-tight uppercase leading-none">User</h2>
            </div>
        </div>
    </div>

    <div class="group bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 mb-8 flex items-center gap-4 transition-all duration-300 hover:bg-white/20 relative z-10">
        <div class="relative">
            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-yellow-300 to-emerald-400 flex items-center justify-center font-bold text-emerald-900 shadow-lg group-hover:scale-110 transition-transform duration-500">
                <?= strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1)); ?>
            </div>
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-teal-600 rounded-full"></span>
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-bold truncate leading-tight"><?= $_SESSION['nama'] ?? 'User Member'; ?></p>
        </div>
    </div>

    <nav class="flex-1 relative z-10">
        <ul class="space-y-2">
            <?php
            function navItem($href, $icon, $label, $active=false){
                $activeClass = $active
                    ? "bg-white text-teal-700 font-bold shadow-lg shadow-teal-900/20 scale-[1.02]"
                    : "text-white/80 hover:bg-white/10 hover:text-white hover:translate-x-1";
                
                echo "
                <li class='transition-all duration-300'>
                    <a href='$href' class='flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group $activeClass'>
                        <i class='fa-solid fa-$icon w-5 text-center transition-transform duration-300 group-hover:rotate-12'></i>
                        <span class='text-sm'>$label</span>
                        " . ($active ? "<div class='ml-auto w-1.5 h-1.5 bg-teal-600 rounded-full'></div>" : "") . "
                    </a>
                </li>";
            }

            $page = basename($_SERVER['PHP_SELF']);
            
            navItem('dashboard.php','chart-pie','Dashboard',$page=='dashboard.php');
            navItem('pinjam.php','file','Pinjam Alat',$page=='pinjam.php');
            navItem('pengembalian.php','rotate-left','Pengembalian',$page=='pengembalian.php');
            navItem('riwayat.php','history','Riwayat Pinjam',$page=='riwayat.php');
            ?>
        </ul>

        <!--<div class="mt-8 mx-2 p-4 bg-gradient-to-br from-white/5 to-white/10 rounded-2xl border border-white/5 text-center">
            <i class="fa-solid fa-circle-info text-emerald-200 mb-2 opacity-50"></i>
            <p class="text-[10px] text-emerald-100/70 leading-relaxed">Butuh bantuan saat meminjam alat? Hubungi Admin.</p>
        </div>-->
    </nav>

    <div class="pt-5 border-t border-white/10 relative z-10">
        <a href="../auth/logout.php"
           class="group flex items-center justify-center gap-3 bg-white/5 hover:bg-red-500 border border-white/10 hover:border-red-500 p-3 rounded-xl transition-all duration-300 text-white font-bold text-sm shadow-sm">
            <i class="fa-solid fa-arrow-right-from-bracket group-hover:-translate-x-1 transition-transform"></i>
            Keluar
        </a>
    </div>

</aside>

<style>
    /* Haluskan transisi font */
    aside {
        font-family: 'Inter', sans-serif;
        -webkit-font-smoothing: antialiased;
    }
</style>