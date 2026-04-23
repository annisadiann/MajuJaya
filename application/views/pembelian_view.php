<!DOCTYPE html>
<html>
<head>
  <title>Riwayat Pembelian - Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 900px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; margin-bottom: 20px; border-bottom: 3px solid #2980b9; padding-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #2980b9; color: white; padding: 12px; text-align: center; }
    td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
    tr:hover td { background: #f8f9fa; }
    .empty { text-align: center; color: #888; padding: 30px; }
    .total-cell { font-weight: bold; color: #2980b9; }
    .summary { background: #eaf4fb; border: 1px solid #aed6f1; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; display: flex; gap: 30px; }
    .summary-item span   { font-size: 13px; color: #666; display: block; }
    .summary-item strong { font-size: 18px; color: #2c3e50; }
    .pagination { display: flex; gap: 5px; margin-top: 20px; flex-wrap: wrap; }
    .pagination a { padding: 8px 14px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #2980b9; font-size: 14px; }
    .pagination a:hover, .pagination a.active { background: #2980b9; color: white; border-color: #2980b9; }
  </style>
</head>
<body>
<div class="container">
  <h2>Riwayat Pembelian (Restok dari Supplier)</h2>

  <div class="summary">
    <div class="summary-item">
      <span>Total Transaksi Pembelian</span>
      <strong><?= $total_data ?> transaksi</strong>
    </div>
  </div>

  <table>
    <tr>
      <th>Tanggal</th>
      <th>Nama Barang</th>
      <th>Jumlah</th>
      <th>Harga Beli</th>
      <th>Total</th>
    </tr>
    <?php if (empty($pembelian)): ?>
      <tr><td colspan="5" class="empty">Belum ada riwayat pembelian.</td></tr>
    <?php else: ?>
      <?php foreach ($pembelian as $row):
        $total = $row['jumlah_tambah'] * $row['harga_beli'];
      ?>
      <tr>
        <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
        <td style="text-align:left;"><?= htmlspecialchars($row['nama_barang']) ?></td>
        <td><?= $row['jumlah_tambah'] ?></td>
        <td>Rp <?= number_format($row['harga_beli'], 0, ',', '.') ?></td>
        <td class="total-cell">Rp <?= number_format($total, 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>

  <?php if ($total_hal > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="<?= site_url('pembelian?page='.($page-1)) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_hal; $i++): ?>
      <a href="<?= site_url('pembelian?page='.$i) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $total_hal): ?>
      <a href="<?= site_url('pembelian?page='.($page+1)) ?>">Next »</a>
    <?php endif; ?>
  </div>
  <span style="color:#888; font-size:13px; margin-top:8px; display:block">Halaman <?= $page ?> dari <?= $total_hal ?></span>
  <?php endif; ?>

  <div style="margin-top:20px;">
    <a href="<?= site_url('barang') ?>" style="color:#3498db; text-decoration:none; font-weight:bold;">← Kembali ke Daftar Barang</a>
  </div>
</div>
</body>
</html>