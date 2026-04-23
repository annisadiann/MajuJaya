<!DOCTYPE html>
<html>
<head>
  <title>Retur Berhasil</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 500px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
    .success { background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; margin-bottom: 20px; font-size: 18px; }
    .total { font-size: 24px; font-weight: bold; color: #2c3e50; margin: 10px 0; }
    .btn { display: inline-block; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin: 5px; font-size: 14px; }
    .btn-blue  { background: #3498db; color: white; }
    .btn-green { background: #2ecc71; color: white; }
    .btn:hover { opacity: 0.85; }
  </style>
</head>
<body>
  <div class="container">
    <div class="success">Retur Berhasil!</div>
    <p>No. Retur: <strong><?= htmlspecialchars($no_retur) ?></strong></p>
    <br>
    <p>Total Pengembalian:</p>
    <div class="total">Rp <?= number_format($total_retur, 0, ',', '.') ?></div>
    <br>
    <a class="btn btn-blue"  href="<?= site_url('riwayat') ?>">Kembali ke Riwayat</a>
    <a class="btn btn-green" href="<?= site_url('retur') ?>">Lihat Riwayat Retur</a>
  </div>
</body>
</html>