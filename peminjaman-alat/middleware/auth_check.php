<?php
if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

function cekRole($role) {
    if ($_SESSION['role'] !== $role) {
        echo "<h3>Akses ditolak</h3>";
        exit;
    }
}
