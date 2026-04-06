<?php
require '../config/database.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Peminjaman.xls");

$where = "";

if(isset($_GET['dari']) && isset($_GET['sampai'])){

$dari=$_GET['dari'];
$sampai=$_GET['sampai'];

$where="WHERE tanggal_pinjam BETWEEN '$dari' AND '$sampai'";
}

$data=mysqli_query($conn,"
SELECT peminjaman.*,users.nama,alat.nama_alat
FROM peminjaman
JOIN users ON peminjaman.user_id=users.id
JOIN alat ON peminjaman.alat_id=alat.id
$where
ORDER BY tanggal_pinjam DESC
");

?>

<h3>Laporan Peminjaman</h3>

<table border="1">

<tr>
<th>User</th>
<th>Alat</th>
<th>Jumlah</th>
<th>Tanggal Pinjam</th>
<th>Jam Pinjam</th>
<th>Tanggal Kembali</th>
<th>Jam Kembali</th>
<th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($data)): ?>

<tr>

<td><?= $row['nama'] ?></td>

<td><?= $row['nama_alat'] ?></td>

<td><?= $row['jumlah'] ?></td>

<td><?= $row['tanggal_pinjam'] ?></td>

<td><?= $row['jam_pinjam'] ?></td>

<td><?= $row['tanggal_kembali'] ?></td>

<td><?= $row['jam_kembali'] ?></td>

<td><?= $row['status'] ?></td>

</tr>

<?php endwhile; ?>

</table>