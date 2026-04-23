<!DOCTYPE html>
<html>
<head>
  <title>History Stok - <?= htmlspecialchars($barang['nama_barang']) ?></title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 800px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #2c3e50; margin-bottom: 5px; border-bottom: 3px solid #8e44ad; padding-bottom: 10px; }
    .subtitle  { color: #888; font-size: 14px; margin: 10px 0 20px; }
    .stok-info { display: inline-block; background: #eaf4fb; border: 1px solid #aed6f1; border-radius: 8px; padding: 10px 20px; margin-bottom: 20px; font-size: 14px; color: #2471a3; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th { background: #8e44ad; color: white; padding: 10px 12px; text-align: center; }
    td { padding: 10px 12px; border-bottom: 1px solid #eee; text-align: center; }
    tr:hover td { background: #f8f9fa; }
    .badge-tambah { background: #d4edda; color: #155724; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-kurang { background: #f8d7da; color: #721c24; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: 600; display: inline-block; }
    .empty { text-align: center; color: #888; padding: 40px; font-size: 15px; }
    .btn-back { display: inline-block; background: #3498db; color: white; padding: 8px 18px; border-radius: 5px; text-decoration: none; font-size: 14px; }
    .btn-back:hover { background: #2980b9; }
    .pagination-simple { margin-top: 15px; display: flex; align-items: center; justify-content: center; gap: 15px; }
  </style>
</head>
<body>
<div class="container">
  <h2>History Stok — <?= htmlspecialchars($barang['nama_barang']) ?></h2>
  <p class="subtitle">Riwayat lengkap perubahan stok masuk dan keluar</p>
  <div class="stok-info">Stok saat ini: <strong><?= $barang['stok'] ?> unit</strong></div>

  <?php if (empty($history)): ?>
    <p class="empty">Belum ada history untuk barang ini.</p>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Tanggal & Waktu</th>
        <th>Jenis</th>
        <th>Jumlah</th>
        <th>Stok Sebelum</th>
        <th>Stok Sesudah</th>
        <th>Keterangan</th>
        <th>No. Transaksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($history as $row): ?>
      <tr>
        <td><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
        <td>
          <?php if ($row['jenis'] === 'tambah'): ?>
            <span class="badge-tambah">▲ Tambah</span>
          <?php else: ?>
            <span class="badge-kurang">▼ Kurang</span>
          <?php endif; ?>
        </td>
        <td><?= $row['jumlah'] ?></td>
        <td><?= $row['stok_sebelum'] ?></td>
        <td><?= $row['stok_sesudah'] ?></td>
        <td><?= htmlspecialchars($row['keterangan'] ?: '-') ?></td>
        <td><?= htmlspecialchars($row['no_transaksi'] ?: '-') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <?php if ($total_hal > 1): ?>
  <div class="pagination-simple">
    <?php if ($page > 1): ?>
      <a class="btn-back" href="<?= site_url('stok/history/'.$barang['id_barang'].'?filter='.$filter.'&page='.($page-1)) ?>">← Prev</a>
    <?php endif; ?>
    <span style="color:#888;">Halaman <?= $page ?> / <?= $total_hal ?></span>
    <?php if ($page < $total_hal): ?>
      <a class="btn-back" href="<?= site_url('stok/history/'.$barang['id_barang'].'?filter='.$filter.'&page='.($page+1)) ?>">Next →</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>
  <?php endif; ?>

  <div style="margin-top:20px;">
    <a class="btn-back" href="<?= site_url('stok') ?>">← Kembali ke Tracking Stok</a>
  </div>
</div>
</body>
</html>