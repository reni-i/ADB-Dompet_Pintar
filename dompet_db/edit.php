<?php
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transaksi WHERE id='$id'"));

if (isset($_POST['update'])) {
    $ket = $_POST['deskripsi'];
    $jenis = $_POST['jenis'];
    $kat = $_POST['kategori'];
    $nom = $_POST['nominal'];

    // Query UPDATE biasa. Trigger database 'update_saldo_update' yang akan kerja keras hitung saldo.
    $query = "UPDATE transaksi SET 
              deskripsi='$ket', 
              jenis='$jenis', 
              kategori='$kat', 
              nominal='$nom' 
              WHERE id='$id'";
              
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Data berhasil diupdate!'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container" style="max-width:600px; margin:50px auto; min-height:auto;">
        <h2>✏️ Edit Transaksi</h2>
        
        <form method="POST" style="flex-direction:column;">
            <label>Jenis Transaksi</label>
            <select name="jenis" id="pilihJenis" onchange="updateKategori()" required>
                <option value="Pengeluaran" <?php if($data['jenis']=='Pengeluaran') echo 'selected'; ?>>Pengeluaran (-)</option>
                <option value="Pemasukan" <?php if($data['jenis']=='Pemasukan') echo 'selected'; ?>>Pemasukan (+)</option>
            </select>

            <label>Kategori</label>
            <select name="kategori" id="pilihKategori" required>
                </select>
            <input type="hidden" id="kategoriLama" value="<?php echo $data['kategori']; ?>">

            <label>Keterangan</label>
            <input type="text" name="deskripsi" value="<?php echo $data['deskripsi']; ?>" required>

            <label>Nominal (Rp)</label>
            <input type="number" name="nominal" value="<?php echo $data['nominal']; ?>" required>

            <div style="display:flex; gap:10px; margin-top:20px;">
                <button type="submit" name="update" class="btn-simpan" style="flex:1">Update Data</button>
                <a href="index.php" class="btn-hapus" style="flex:1; text-align:center; background:#95a5a6;">Batal</a>
            </div>
        </form>
    </div>

    <script>
        function updateKategori() {
            var jenis = document.getElementById("pilihJenis").value;
            var katSelect = document.getElementById("pilihKategori");
            var katLama = document.getElementById("kategoriLama").value;
            
            katSelect.innerHTML = "";
            var opsi = (jenis == "Pemasukan") 
                ? ["Gaji", "Bonus", "Penjualan", "Investasi", "Lainnya"]
                : ["Makanan", "Transport", "Tagihan", "Belanja", "Hiburan", "Lainnya"];
            
            opsi.forEach(function(item){
                var opt = document.createElement("option");
                opt.value = item; 
                opt.innerHTML = item;
                if(item === katLama) opt.selected = true; // Auto select yang lama
                katSelect.appendChild(opt);
            });
        }
        // Jalankan saat load
        updateKategori();
    </script>
</body>
</html>