<!DOCTYPE html>
<html>
<head>
  <title>Struk <?= htmlspecialchars($transaksi['no_transaksi']) ?></title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Courier New', monospace; background: #f0f2f5; display: flex; justify-content: center; padding: 20px; flex-direction: column; align-items: center; }
    .btn-area { display: flex; gap: 10px; margin-bottom: 15px; }
    .btn { padding: 8px 18px; border-radius: 5px; border: none; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-block; }
    .btn:hover { opacity: 0.85; }
    .struk { background: white; width: 80mm; padding: 5mm; font-size: 11px; }
    .toko-nama { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 2px; }
    .toko-sub  { text-align: center; font-size: 10px; margin-bottom: 5px; }
    .garis { border-top: 1px dashed #000; margin: 5px 0; }
    .info-row  { display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 2px; }
    .item { margin-bottom: 4px; }
    .item-nama   { font-size: 11px; }
    .item-detail { display: flex; justify-content: space-between; font-size: 10px; padding-left: 3mm; }
    .total-row { display: flex; justify-content: space-between; font-weight: bold; font-size: 12px; margin-top: 3px; }
    .footer { text-align: center; font-size: 10px; margin-top: 5px; }
    @media print {
      .btn-area { display: none; }
      body { background: white; padding: 0; }
      @page { size: 80mm auto; margin: 0; }
    }
  </style>
</head>
<body>
  <div class="btn-area">
    <button class="btn" style="background:#27ae60; color:white;" onclick="window.print()">Cetak Struk</button>
    <a class="btn" style="background:#3498db; color:white;" href="<?= site_url('invoice?no='.urlencode($transaksi['no_transaksi'])) ?>">← Invoice A4</a>
    <a class="btn" style="background:#95a5a6; color:white;" href="<?= site_url('riwayat') ?>">← Riwayat</a>
  </div>

  <div class="struk">
    <div class="toko-nama">TOKO MAJU JAYA</div>
    <div class="toko-sub">Toko Alat Tulis</div>
    <div class="garis"></div>

    <div class="info-row">
      <span>No</span>
      <span><?= htmlspecialchars($transaksi['no_transaksi']) ?></span>
    </div>
    <div class="info-row">
      <span>Tgl</span>
      <span><?= date('d/m/Y H:i', strtotime($transaksi['tanggal'])) ?></span>
    </div>

    <div class="garis"></div>

    <?php foreach ($detail as $item): ?>
    <div class="item">
      <div class="item-nama"><?= htmlspecialchars($item['nama_barang']) ?></div>
      <div class="item-detail">
        <span><?= $item['jumlah'] ?> x Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></span>
        <span>Rp <?= number_format($item['total_harga'], 0, ',', '.') ?></span>
      </div>
    </div>
    <?php endforeach; ?>

    <div class="garis"></div>

    <div class="total-row">
      <span>TOTAL</span>
      <span>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span>
    </div>

    <div class="garis"></div>

    <div class="footer">
      <p>Terima kasih!</p>
      <p>Selamat berbelanja kembali :)</p>
    </div>
  </div>
</body>
</html>