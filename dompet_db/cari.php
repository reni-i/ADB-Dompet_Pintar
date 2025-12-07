<?php
include 'koneksi.php';
$q = $_GET['q'];

$query = "SELECT * FROM transaksi WHERE deskripsi LIKE '%$q%' OR label_kategori LIKE '%$q%' ORDER BY id DESC";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)) {
        $badgeClass = ($row['jenis'] == 'Pemasukan') ? 'badge-masuk' : 'badge-keluar';
        $nominalFmt = "Rp " . number_format($row['nominal']);

        echo "<tr>
            <td><small>{$row['tanggal']}</small></td>
            <td><strong>{$row['deskripsi']}</strong></td>
            <td><span class='badge $badgeClass'>{$row['jenis']}</span></td>
            <td>$nominalFmt</td>
            <td><small>{$row['label_kategori']}</small></td>
            <td>
                <a href='index.php?hapus_id={$row['id']}' class='btn-hapus' onclick='return confirm(\"Hapus?\")'>🗑️ Hapus</a>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center;'>Data tidak ditemukan</td></tr>";
}
?>