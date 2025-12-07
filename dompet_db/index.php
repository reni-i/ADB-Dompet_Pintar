<?php
include 'koneksi.php';

// --- LOGIKA SIMPAN & EXPORT TETAP SAMA ---
if (isset($_POST['simpan'])) {
    $ket = $_POST['deskripsi'];
    $jenis = $_POST['jenis'];
    $kat = $_POST['kategori'];
    $nom = $_POST['nominal'];
    mysqli_query($conn, "CALL tambah_transaksi('$ket', '$jenis', '$kat', '$nom')");
    header("Location: index.php");
}

if (isset($_POST['export_python'])) {
    $output = shell_exec("python export_data.py 2>&1");
    echo "<script>alert('Laporan Python berhasil dibuat!');</script>";
}

// --- LOGIKA HAPUS (Update Saldo ditangani Trigger Database) ---
if (isset($_GET['hapus_id'])) {
    $id = $_GET['hapus_id'];
    mysqli_query($conn, "DELETE FROM transaksi WHERE id='$id'");
    header("Location: index.php");
}

// Ambil Saldo
$rowSaldo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT saldo_akhir FROM dompet WHERE id=1"));
$saldo = number_format($rowSaldo['saldo_akhir']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dompet Pintar</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="container">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>💰 Dompet Pintar</h2>
            <div class="saldo-box" style="margin:0; padding:15px 30px;">
                <small>Saldo Saat Ini</small>
                <h1 style="margin:0; font-size:2em;">Rp <?php echo $saldo; ?></h1>
            </div>
        </div>

        <form method="POST">
            <select name="jenis" id="pilihJenis" onchange="updateKategori()" required>
                <option value="Pengeluaran">Pengeluaran (-)</option>
                <option value="Pemasukan">Pemasukan (+)</option>
            </select>
            <select name="kategori" id="pilihKategori" required></select>
            <input type="text" name="deskripsi" placeholder="Keterangan" required autocomplete="off">
            <input type="number" name="nominal" placeholder="Jumlah (Rp)" required>
            <button type="submit" name="simpan" class="btn-simpan"><i class="fas fa-save"></i> Simpan</button>
        </form>

        <form method="POST">
            <button type="submit" name="export_python" class="btn-python"><i class="fas fa-file-export"></i> Export Laporan (Python)</button>
        </form>

        <br>
        <input type="text" id="keyword" placeholder="🔍 Cari transaksi..." onkeyup="cariData()">
        
        <table id="tabel-dompet">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Gunakan tabel transaksi langsung biar aman
                $result = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY tanggal DESC");

                while ($row = mysqli_fetch_assoc($result)) {
                    $badgeClass = ($row['jenis'] == 'Pemasukan') ? 'badge-masuk' : 'badge-keluar';
                    
                    // Format Label
                    $tanda = ($row['jenis'] == 'Pemasukan') ? '[+]' : '[-]';
                    $label_lengkap = "$tanda {$row['kategori']}";

                    echo "<tr>
                        <td><small>{$row['tanggal']}</small></td>
                        <td><span class='badge $badgeClass'>$label_lengkap</span></td>
                        <td><strong>{$row['deskripsi']}</strong></td>
                        <td>Rp " . number_format($row['nominal']) . "</td>
                        <td>
                            <a href='edit.php?id={$row['id']}' class='btn-aksi btn-edit'><i class='fas fa-edit'></i> Edit</a>
                            <a href='index.php?hapus_id={$row['id']}' class='btn-aksi btn-hapus' onclick='return confirm(\"Hapus & kembalikan saldo?\")'><i class='fas fa-trash'></i> Hapus</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="script.js"></script>
    <script>
        // JS Dropdown (Sama kayak sebelumnya)
        function updateKategori() {
            var jenis = document.getElementById("pilihJenis").value;
            var katSelect = document.getElementById("pilihKategori");
            katSelect.innerHTML = "";
            var opsi = (jenis == "Pemasukan") 
                ? ["Gaji", "Bonus", "Penjualan", "Investasi", "Lainnya"]
                : ["Makanan", "Transport", "Tagihan", "Belanja", "Hiburan", "Lainnya"];
            
            opsi.forEach(function(item){
                var opt = document.createElement("option");
                opt.value = item; opt.innerHTML = item;
                katSelect.appendChild(opt);
            });
        }
        window.onload = updateKategori;
    </script>
</body>
</html>