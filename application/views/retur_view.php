<!DOCTYPE html>
<html>
<head>
  <title>Riwayat Retur - Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 960px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; margin-bottom: 20px; border-bottom: 3px solid #e74c3c; padding-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #e74c3c; color: white; padding: 12px; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid #eee; }
    tr:hover td { background: #f8f9fa; }
    .total-cell { font-weight: bold; color: #e74c3c; }
    .empty { text-align: center; color: #888; padding: 30px; }
    .summary { background: #fff5f5; border: 1px solid #f5c6cb; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; display: flex; gap: 30px; }
    .summary-item span   { font-size: 13px; color: #666; display: block; }
    .summary-item strong { font-size: 18px; color: #2c3e50; }
    .pagination { display: flex; gap: 5px; margin-top: 20px; flex-wrap: wrap; }
    .pagination a { padding: 8px 14px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #e74c3c; font-size: 14px; }
    .pagination a:hover, .pagination a.active { background: #e74c3c; color: white; border-color: #e74c3c; }
  </style>
</head>
<body>
<div class="container">
  <h2>Riwayat Retur</h2>

  <div class="summary">
    <div class="summary-item">
      <span>Total Retur</span>
      <strong><?= $total_data ?> retur</strong>
    </div>
    <div class="summary-item">
      <span>Total Pengembalian</span>
      <strong>Rp <?= number_format($grand_total, 0, ',', '.') ?></strong>
    </div>
  </div>

  <table>
    <tr>
      <th>No. Retur</th>
      <th>No. Transaksi</th>
      <th>Tanggal Retur</th>
      <th>Nama Barang</th>
      <th>Jumlah Retur</th>
      <th>Harga Satuan</th>
      <th>Total Pengembalian</th>
    </tr>
    <?php if (empty($retur)): ?>
      <tr><td colspan="7" class="empty">Belum ada retur.</td></tr>
    <?php else: ?>
      <?php foreach ($retur as $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['no_retur']) ?></td>
        <td><?= htmlspecialchars($row['no_transaksi']) ?></td>
        <td><?= $row['tanggal_retur'] ?></td>
        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
        <td><?= $row['jumlah_retur'] ?></td>
        <td>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></td>
        <td class="total-cell">Rp <?= number_format($row['total_retur'], 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </table>

  <?php if ($total_hal > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="<?= site_url('retur?page='.($page-1)) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_hal; $i++): ?>
      <a href="<?= site_url('retur?page='.$i) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $total_hal): ?>
      <a href="<?= site_url('retur?page='.($page+1)) ?>">Next »</a>
    <?php endif; ?>
  </div>
  <span style="color:#888; font-size:13px; margin-top:8px; display:block">Halaman <?= $page ?> dari <?= $total_hal ?></span>
  <?php endif; ?>

  <div style="margin-top:20px;">
    <a href="<?= site_url('riwayat') ?>" style="color:#3498db; text-decoration:none; font-weight:bold;">← Kembali ke Riwayat Transaksi</a>
  </div>
</div>
</body>
</html>