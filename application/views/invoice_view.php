<!DOCTYPE html>
<html>
<head>
  <title>Invoice <?= htmlspecialchars($transaksi['no_transaksi']) ?></title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .invoice-box { max-width: 700px; margin: 40px auto; background: white; padding: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-radius: 10px; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; }
    .toko-nama { font-size: 24px; font-weight: bold; color: #2c3e50; }
    .toko-sub  { font-size: 13px; color: #888; margin-top: 4px; }
    .invoice-label h2 { font-size: 28px; color: #3498db; letter-spacing: 2px; }
    .invoice-label p  { font-size: 13px; color: #888; margin-top: 4px; text-align:right; }
    .divider { border: none; border-top: 2px solid #eee; margin: 20px 0; }
    .info-box { display: flex; justify-content: space-between; margin-bottom: 25px; }
    .info-item span   { font-size: 12px; color: #888; display: block; }
    .info-item strong { font-size: 15px; color: #2c3e50; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #3498db; color: white; padding: 10px 12px; text-align: left; font-size: 14px; }
    td { padding: 10px 12px; border-bottom: 1px solid #eee; font-size: 14px; }
    .text-right { text-align: right; }
    .total-row td { font-weight: bold; font-size: 16px; background: #f8f9fa; }
    .footer { text-align: center; margin-top: 30px; color: #888; font-size: 13px; }
    .btn-area { display: flex; gap: 10px; margin-bottom: 20px; }
    .btn { padding: 10px 20px; border-radius: 5px; border: none; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-block; }
    .btn:hover { opacity: 0.85; }
    @media print {
      .btn-area { display: none; }
      body { background: white; }
      .invoice-box { box-shadow: none; margin: 0; border-radius: 0; }
      @page { margin: 10mm; }
    }
  </style>
</head>
<body>
<div class="invoice-box">
  <div class="btn-area">
    <button class="btn" style="background:#3498db; color:white;" onclick="window.print()">Cetak A4</button>
    <a class="btn" style="background:#27ae60; color:white;" href="<?= site_url('invoice/thermal?no='.urlencode($transaksi['no_transaksi'])) ?>">Cetak Thermal</a>
    <a class="btn" style="background:#95a5a6; color:white;" href="<?= site_url('riwayat') ?>">← Kembali</a>
  </div>

  <div class="header">
    <div>
      <div class="toko-nama">Toko Maju Jaya</div>
      <div class="toko-sub">Toko Alat Tulis</div>
    </div>
    <div class="invoice-label">
      <h2>INVOICE</h2>
      <p><?= htmlspecialchars($transaksi['no_transaksi']) ?></p>
    </div>
  </div>

  <hr class="divider">

  <div class="info-box">
    <div class="info-item">
      <span>Tanggal Transaksi</span>
      <strong><?= date('d/m/Y H:i', strtotime($transaksi['tanggal'])) ?></strong>
    </div>
    <div class="info-item" style="text-align:right;">
      <span>Jumlah Item</span>
      <strong><?= $transaksi['jumlah'] ?> item</strong>
    </div>
  </div>

  <table>
    <tr>
      <th>Nama Barang</th>
      <th class="text-right">Harga Satuan</th>
      <th class="text-right">Jumlah</th>
      <th class="text-right">Subtotal</th>
    </tr>
    <?php foreach ($detail as $item): ?>
    <tr>
      <td><?= htmlspecialchars($item['nama_barang']) ?></td>
      <td class="text-right">Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
      <td class="text-right"><?= $item['jumlah'] ?></td>
      <td class="text-right">Rp <?= number_format($item['total_harga'], 0, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="total-row">
      <td colspan="3" class="text-right">Total Pembayaran</td>
      <td class="text-right">Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></td>
    </tr>
  </table>

  <hr class="divider">
  <div class="footer">
    <p>Terima kasih telah berbelanja di Toko Maju Jaya!</p>
    <p style="margin-top:5px;">Invoice dicetak pada <?= date('d/m/Y H:i') ?></p>
  </div>
</div>
</body>
</html>