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
    .summary-item strong { font-size: 18px; color: #2c3e50; display: block; }
    .summary-item small  { display: block; margin-top: 2px; }
    .pagination { display: flex; gap: 5px; margin-top: 20px; flex-wrap: wrap; }
    .pagination a { padding: 8px 14px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #27ae60; font-size: 14px; }
    .pagination a:hover, .pagination a.active { background: #27ae60; color: white; border-color: #27ae60; }
    a { color: #27ae60; }
    .filter-box { background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px; padding: 15px 20px; margin-bottom: 20px; }
    .filter-box form { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
    .filter-box label { font-size: 12px; color: #666; display: block; margin-bottom: 4px; }
    .filter-box input { padding: 7px 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 13px; }
    .btn-filter { background: #27ae60; color: white; padding: 7px 16px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; }
    .btn-reset  { background: #95a5a6; color: white; padding: 7px 16px; border: none; border-radius: 5px; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-block; }
  </style>
</head>
<body>
<div class="container">
  <h2>Tracking Stok Barang</h2>

  <div class="filter-box">
    <form method="GET" action="<?= site_url('stok') ?>">
      <div>
        <label>Nama Barang</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" placeholder="Cari nama barang...">
      </div>
      <div>
        <label>Dari Tanggal</label>
        <input type="date" name="dari" value="<?= $dari ?>">
      </div>
      <div>
        <label>Sampai Tanggal</label>
        <input type="date" name="sampai" value="<?= $sampai ?>">
      </div>
      <div>
        <button type="submit" class="btn-filter">Filter</button>
        <a href="<?= site_url('stok') ?>" class="btn-reset">Reset</a>
      </div>
    </form>
  </div>

  <div class="summary">
    <div class="summary-item">
      <span>Total Jenis Barang</span>
      <strong><?= $total_barang ?> barang</strong>
      <small style="color:#888; font-size:12px;">
        Halaman <?= $page ?>: menampilkan <?= (($page-1)*10)+1 ?>–<?= min($page*10, $total_barang) ?>
      </small>
    </div>
    <div class="summary-item">
      <span>Stok Habis</span>
      <strong style="color:<?= $stok_habis > 0 ? '#e74c3c' : '#27ae60' ?>"><?= $stok_habis ?> barang</strong>
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
      <td><?= number_format($row['stok_awal'], 0, ',', '.') ?></td>
      <td>
        <?php if ($dari || $sampai): ?>
          <?= number_format($row['ditambah'], 0, ',', '.') ?>
        <?php else: ?>
          <a href="<?= site_url('stok/history/'.$row['id_barang'].'?filter=tambah') ?>">
            <?= number_format($row['ditambah'], 0, ',', '.') ?>
          </a>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($dari || $sampai): ?>
          <?= number_format($row['terjual'], 0, ',', '.') ?>
        <?php else: ?>
          <a href="<?= site_url('stok/history/'.$row['id_barang'].'?filter=kurang') ?>">
            <?= number_format($row['terjual'], 0, ',', '.') ?>
          </a>
        <?php endif; ?>
      </td>
      <td>
        <?php if ($dari || $sampai): ?>
          <?= number_format($row['diretur'], 0, ',', '.') ?>
        <?php else: ?>
          <a href="<?= site_url('stok/history/'.$row['id_barang'].'?filter=retur') ?>">
            <?= number_format($row['diretur'], 0, ',', '.') ?>
          </a>
        <?php endif; ?>
      </td>
      <td class="<?= $row['stok_akhir'] <= 0 ? 'stok-habis' : 'stok-aman' ?>">
        <?= number_format($row['stok_akhir'], 0, ',', '.') ?>
        <?= $row['stok_akhir'] < 0 ? '(Minus!)' : ($row['stok_akhir'] == 0 ? '(Habis)' : '') ?>
      </td>
    </tr>
    <?php if (($dari || $sampai) && !empty($detail_transaksi[$row['id_barang']])): ?>
      <?php foreach ($detail_transaksi[$row['id_barang']] as $d): ?>
      <tr style="background:#f8f9fa; font-size:12px;">
        <td style="text-align:left; padding-left:30px; color:#888;">
          → <?= date('d/m/Y H:i', strtotime($d['tanggal'])) ?>
        </td>
        <td colspan="2" style="color:#888;">
          <?php if ($d['jenis'] === 'tambah' && strpos($d['keterangan'], 'Retur') === false): ?>
            <span style="color:#27ae60;">▲ +<?= number_format($d['jumlah'], 0, ',', '.') ?></span>
          <?php elseif ($d['jenis'] === 'kurang'): ?>
            <span style="color:#e74c3c;">▼ -<?= number_format($d['jumlah'], 0, ',', '.') ?></span>
          <?php else: ?>
            <span style="color:#3498db;">↩ +<?= number_format($d['jumlah'], 0, ',', '.') ?></span>
          <?php endif; ?>
        </td>
        <td colspan="3" style="text-align:left; color:#888;"><?= htmlspecialchars($d['keterangan'] ?: '-') ?></td>
      </tr>
      <?php endforeach; ?>
    <?php endif; ?>
    <?php endforeach; ?>
  </table>

  <?php if ($total_hal > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="<?= site_url('stok?page='.($page-1).'&nama='.urlencode($nama).'&dari='.$dari.'&sampai='.$sampai) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_hal; $i++): ?>
      <a href="<?= site_url('stok?page='.$i.'&nama='.urlencode($nama).'&dari='.$dari.'&sampai='.$sampai) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $total_hal): ?>
      <a href="<?= site_url('stok?page='.($page+1).'&nama='.urlencode($nama).'&dari='.$dari.'&sampai='.$sampai) ?>">Next »</a>
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