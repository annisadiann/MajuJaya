<!DOCTYPE html>
<html>
<head>
  <title>Transaksi Berhasil</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 500px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }
    .success { background: #d4edda; color: #155724; padding: 20px; border-radius: 8px; margin-bottom: 20px; font-size: 18px; }
    .detail-box { text-align: left; background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    .detail-box table { width: 100%; border-collapse: collapse; }
    .detail-box td { padding: 8px; border-bottom: 1px solid #eee; font-size: 14px; }
    .total { font-size: 24px; font-weight: bold; color: #2c3e50; margin: 10px 0; }
    .btn { display: inline-block; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin: 5px; font-size: 14px; }
    .btn-blue  { background: #3498db; color: white; }
    .btn-green { background: #2ecc71; color: white; }
    .btn:hover { opacity: 0.85; }
  </style>
</head>
<body>
  <div class="container">
    <div class="success">Transaksi Penjualan Berhasil!</div>
    <p>No. Transaksi: <strong><?= $no_transaksi ?></strong></p>
    <br>
    <div class="detail-box">
      <table>
        <?php foreach ($detail_barang as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['nama_barang']) ?></td>
          <td><?= $item['jumlah'] ?> x Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
          <td><strong>Rp <?= number_format($item['total_harga'], 0, ',', '.') ?></strong></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <p>Total Pembayaran:</p>
    <div class="total">Rp <?= number_format($total_harga, 0, ',', '.') ?></div>
    <br>
    <a class="btn btn-green" href="<?= site_url('kasir') ?>">Jual Lagi</a>
    <a class="btn btn-blue"  href="<?= site_url('riwayat') ?>">Lihat Riwayat</a>
    <a class="btn btn-blue"  href="<?= site_url('invoice?no='.urlencode($no_transaksi)) ?>">Cetak Invoice</a>
  </div>
</body>
</html>