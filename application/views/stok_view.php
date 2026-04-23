<!DOCTYPE html>
<html>
<head>
  <title>Tracking Stok - Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 960px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; margin-bottom: 20px; border-bottom: 3px solid #27ae60; padding-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #27ae60; color: white; padding: 12px; text-align: center; }
    td { padding: 12px; border-bottom: 1px solid #eee; text-align: center; }
    tr:hover td { background: #f8f9fa; }
    .stok-habis { color: #e74c3c; font-weight: bold; }
    .stok-aman  { color: #27ae60; font-weight: bold; }
    .summary { background: #eafaf1; border: 1px solid #a9dfbf; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; display: flex; gap: 30px; }
    .summary-item span   { font-size: 13px; color: #666; display: block; }
    .summary-item strong { font-size: 18px; color: #2c3e50; }
    .pagination { display: flex; gap: 5px; margin-top: 20px; flex-wrap: wrap; }
    .pagination a { padding: 8px 14px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #27ae60; font-size: 14px; }
    .pagination a:hover, .pagination a.active { background: #27ae60; color: white; border-color: #27ae60; }
    a { color: #27ae60; }
  </style>
</head>
<body>
<div class="container">
  <h2>Tracking Stok Barang</h2>

  <div class="summary">
    <div class="summary-item">
      <span>Total Jenis Barang</span>
      <strong><?= $total_barang ?> barang</strong>
    </div>
    <div class="summary-item">
      <span>Stok Habis</span>
      <strong style="color:#e74c3c"><?= $stok_habis ?> barang</strong>
    </div>
  </div>

  <table>
    <tr>
      <th>Nama Barang</th>
      <th>Stok Awal</th>
      <th>Ditambah</th>
      <th>Terjual</th>
      <th>Diretur</th>
      <th>Stok Akhir</th>
    </tr>
    <?php foreach ($barang as $row): ?>
    <tr>
      <td style="text-align:left;"><?= htmlspecialchars($row['nama_barang']) ?></td>
      <td><?= $row['stok_awal'] ?></td>
      <td>
        <a href="<?= site_url('stok/history/'.$row['id_barang'].'?filter=tambah') ?>">
          <?= $row['ditambah'] ?>
        </a>
      </td>
      <td>
        <a href="<?= site_url('stok/history/'.$row['id_barang'].'?filter=kurang') ?>">
          <?= $row['terjual'] ?>
        </a>
      </td>
      <td>
        <a href="<?= site_url('stok/history/'.$row['id_barang'].'?filter=retur') ?>">
          <?= $row['diretur'] ?>
        </a>
      </td>
      <td class="<?= $row['stok_akhir'] <= 0 ? 'stok-habis' : 'stok-aman' ?>">
        <?= $row['stok_akhir'] ?>
        <?= $row['stok_akhir'] < 0 ? '(Minus!)' : ($row['stok_akhir'] == 0 ? '(Habis)' : '') ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

  <?php if ($total_hal > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="<?= site_url('stok?page='.($page-1)) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_hal; $i++): ?>
      <a href="<?= site_url('stok?page='.$i) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $total_hal): ?>
      <a href="<?= site_url('stok?page='.($page+1)) ?>">Next »</a>
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