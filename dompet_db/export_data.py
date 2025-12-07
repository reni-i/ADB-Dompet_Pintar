import mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="root",
  password="",
  database="dompet_db"
)

cursor = mydb.cursor()

# Ambil data dari VIEW
cursor.execute("SELECT tanggal, label_lengkap, deskripsi, nominal FROM laporan_view ORDER BY tanggal DESC")
results = cursor.fetchall()

filename = "laporan_keuangan.txt"
with open(filename, "w") as f:
    f.write("=== LAPORAN KEUANGAN ===\n")
    f.write("Kategori & Label dari MySQL Function\n\n")
    
    # Header Rapi
    f.write(f"{'TANGGAL':<20} | {'KATEGORI':<18} | {'NOMINAL':<12} | {'KETERANGAN'}\n")
    f.write("-" * 80 + "\n")
    
    for row in results:
        tgl = str(row[0])
        label = row[1]     # Ini hasil function SQL (Misal: [-] Makanan)
        ket = row[2]
        nom = str(row[3])
        
        f.write(f"{tgl:<20} | {label:<18} | Rp {nom:<9} | {ket}\n")

print("Sukses! File laporan_keuangan_v2.txt berhasil dibuat.")