<!DOCTYPE html>
<html>
<head>
  <title>Kasir - Toko Maju Jaya</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; background: #f0f2f5; }
    .container { max-width: 950px; margin: 40px auto; background: white; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    h2 { color: #2c3e50; border-bottom: 3px solid #27ae60; padding-bottom: 10px; }
    .user-badge { background: #f0f2f5; border: 1px solid #dde; border-radius: 20px; padding: 6px 14px; font-size: 13px; color: #555; }
    .role-tag { background: #27ae60; color: white; border-radius: 10px; padding: 2px 8px; font-size: 11px; margin-left: 5px; }
    .btn-logout { background: #e74c3c; color: white; padding: 7px 15px; border-radius: 20px; text-decoration: none; font-size: 13px; margin-left: 10px; }
    .btn-logout:hover { background: #c0392b; }
    table { width: 100%; border-collapse: collapse; }
    th { padding: 12px; text-align: center; color: white; background: #27ae60; font-size: 14px; }
    td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; }
    tr:hover td { background: #f8f9fa; }
    input[type=number] { width: 90px; padding: 6px 8px; border: 1px solid #ddd; border-radius: 5px; text-align: center; font-size: 14px; }
    .stok-low { color: #e74c3c; font-weight: bold; }
    .total-box { background: #f8f9fa; border-radius: 8px; padding: 12px 18px; text-align: right; margin-top: 15px; font-size: 15px; }
    .total-box span { font-size: 20px; font-weight: bold; color: #27ae60; }
    .footer-bar { display: flex; justify-content: space-between; align-items: center; margin-top: 18px; }
    .btn-proses { background: #27ae60; color: white; padding: 10px 25px; border: none; border-radius: 5px; cursor: pointer; font-size: 15px; }
    .btn-proses:hover { background: #1e8449; }
    .alert-error   { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    .pagination { display: flex; gap: 5px; margin-top: 15px; flex-wrap: wrap; }
    .pagination a { padding: 8px 14px; border: 1px solid #ddd; border-radius: 5px; text-decoration: none; color: #27ae60; font-size: 14px; }
    .pagination a:hover, .pagination a.active { background: #27ae60; color: white; border-color: #27ae60; }
    .link-riwayat { color: #3498db; text-decoration: none; font-weight: bold; font-size: 14px; }
  </style>
</head>
<body>
<div class="container">
  <div class="topbar">
    <h2>Penjualan - Toko Maju Jaya</h2>
    <div style="display:flex; align-items:center; gap:10px;">
      <div class="user-badge">
        <strong><?= htmlspecialchars($this->session->userdata('nama')) ?></strong>
        <span class="role-tag"><?= $this->session->userdata('role') ?></span>
      </div>
      <a href="<?= site_url('auth/logout') ?>" class="btn-logout">Logout</a>
    </div>
  </div>

  <?php if ($error):   ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <?php if ($success): ?><div class="alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

  <form action="<?= site_url('kasir/simpan') ?>" method="POST">
  <table>
    <tr>
      <th style="text-align:left;">Nama Barang</th>
      <th>Harga Jual</th>
      <th>Stok</th>
      <th>Jumlah Dijual</th>
      <th>Subtotal</th>
    </tr>
    <?php foreach ($barang as $i => $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['nama_barang']) ?></td>
      <td style="text-align:center;">Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
      <td style="text-align:center;" class="<?= $row['stok'] < 10 ? 'stok-low' : '' ?>"><?= $row['stok'] ?></td>
      <td style="text-align:center;">
        <input type="number"
               name="jumlah[<?= $row['id_barang'] ?>]"
               min="0" max="<?= $row['stok'] ?>"
               value="0"
               data-harga="<?= $row['harga_jual'] ?>"
               class="input-jual" id="jual-<?= $i ?>"
               oninput="hitungTotal()">
      </td>
      <td style="text-align:right;" id="sub-<?= $i ?>">-</td>
    </tr>
    <?php endforeach; ?>
  </table>

  <div class="total-box">
    Total Transaksi: <span id="total-display">Rp 0</span>
  </div>

  <div class="footer-bar">
    <a href="<?= site_url('riwayat') ?>" class="link-riwayat">Riwayat Penjualan</a>
    <button type="submit" class="btn-proses">Proses Penjualan</button>
  </div>
  </form>

  <?php if ($total_halaman > 1): ?>
  <div class="pagination">
    <?php if ($page > 1): ?>
      <a href="<?= site_url('kasir?page='.($page-1)) ?>">« Prev</a>
    <?php endif; ?>
    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
      <a href="<?= site_url('kasir?page='.$i) ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if ($page < $total_halaman): ?>
      <a href="<?= site_url('kasir?page='.($page+1)) ?>">Next »</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>

<script>
function hitungTotal() {
  let total = 0;
  document.querySelectorAll('.input-jual').forEach(function(input, i) {
    const qty   = parseInt(input.value) || 0;
    const harga = parseInt(input.dataset.harga) || 0;
    const sub   = qty * harga;
    total      += sub;
    const subEl = document.getElementById('sub-' + i);
    if (subEl) subEl.textContent = qty > 0 ? 'Rp ' + sub.toLocaleString('id-ID') : '-';
  });
  document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
</body>
</html>