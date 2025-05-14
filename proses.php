<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "melalak_travel");

// Cek koneksi
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama = $_POST['nama'];
$hari = intval($_POST['hari']);
$peserta = intval($_POST['peserta']);
$layanan = $_POST['layanan'] ?? [];

// Hitung total harga
$totalPerOrang = 0;
foreach ($layanan as $harga) {
  $totalPerOrang += intval($harga);
}
$total = $totalPerOrang * $hari * $peserta;

// Simpan ke database
$layananJson = json_encode($layanan);
$stmt = $conn->prepare("INSERT INTO pemesanan (nama, hari, peserta, layanan, total) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("siisi", $nama, $hari, $peserta, $layananJson, $total);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konfirmasi Pemesanan</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-5">
    <div class="card">
      <div class="card-header bg-success text-white">
        <h3 class="mb-0">✅ Pemesanan Berhasil!</h3>
      </div>
      <div class="card-body">
        <p><strong>Nama:</strong> <?= htmlspecialchars($nama); ?></p>
        <p><strong>Jumlah Hari:</strong> <?= $hari; ?></p>
        <p><strong>Jumlah Peserta:</strong> <?= $peserta; ?></p>
        <p><strong>Total Biaya:</strong> Rp <?= number_format($total, 0, ',', '.'); ?></p>
        
        <div class="btn mt-3">
          <a href="form_pemesanan.php" class="btn btn-primary">Pesan Lagi</a>
          <a href="index.html" class="btn btn-secondary">Kembali</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>